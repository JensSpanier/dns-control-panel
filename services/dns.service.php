<?php

class DnsService
{
    public function __construct(
        private ZoneService $zoneService,
        private ConfigService $configService
    ) {
    }

    public function deleteDnsRecord($zoneName, $host, $type, $ttl, $data)
    {
        $handle = $this->getNsupdateHandle($zoneName);
        fwrite($handle, $this->buildNsupdateCommand('delete', $zoneName, $host, $type, $ttl, $data) . PHP_EOL);
        $this->sendAndCloseNsupdateHandle($handle);
    }

    public function addDnsRecord($zoneName, $host, $type, $ttl, $data)
    {
        $handle = $this->getNsupdateHandle($zoneName);
        fwrite($handle, $this->buildNsupdateCommand('add', $zoneName, $host, $type, $ttl, $data) . PHP_EOL);
        $this->sendAndCloseNsupdateHandle($handle);
    }

    public function updateDnsRecord($zoneName, $currentHost, $currentType, $currentTtl, $currentData, $newHost, $newType, $newTtl, $newData)
    {
        $handle = $this->getNsupdateHandle($zoneName);
        fwrite($handle, $this->buildNsupdateCommand('delete', $zoneName, $currentHost, $currentType, $currentTtl, $currentData) . PHP_EOL);
        fwrite($handle, $this->buildNsupdateCommand('add', $zoneName, $newHost, $newType, $newTtl, $newData) . PHP_EOL);
        $this->sendAndCloseNsupdateHandle($handle);
    }

    public function getDnsRecords($zoneName)
    {
        $zone = $this->zoneService->getZone($zoneName);

        $commandParts = [
            $this->configService->getConfig('digPath'),
        ];

        $commandParts[] = "@" . ($zone['server'] ?? 'localhost');
        $commandParts[] = '-t AXFR';

        if (array_key_exists('transferKey', $zone)) {
            $commandParts[] = "-y {$zone['transferKey']}";
        }

        $commandParts[] = $zoneName;

        $command = implode(' ', $commandParts);

        $handle = popen($command, 'r');

        if (!$handle) {
            throw new Exception('The command for reading the current DNS entries could not be opened', 500);
        }

        $result = [];
        $allowedRecordTypes = $this->configService->getConfig('recordTypes');

        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            if (empty($line) || $line[0] === ';') {
                continue;
            }

            [$host, $ttl, $class, $type, $data] = preg_split('/[\s]+/', $line, 5);

            if (!in_array(strtoupper($type), $allowedRecordTypes)) {
                continue;
            }

            $result[] = [
                'host' => rtrim($host, '.'),
                'ttl' => $ttl,
                'class' => $class,
                'type' => $type,
                'data' => $data,
            ];
        }

        pclose($handle);

        if ($result[array_key_last($result)]['type'] === 'SOA') {
            array_pop($result);
        }

        return $result;
    }

    private function getNsupdateHandle($zoneName)
    {
        $zone = $this->zoneService->getZone($zoneName);

        $commandParts = [
            $this->configService->getConfig('nsupdatePath'),
        ];

        if (array_key_exists('updateKey', $zone)) {
            $commandParts[] = "-y {$zone['updateKey']}";
        }

        $command = implode(' ', $commandParts);

        $handle = popen($command, 'w');

        if (!$handle) {
            throw new Exception('The command for sending updates could not be opened', 500);
        }

        fwrite($handle, 'server ' . ($zone['server'] ?? 'localhost') . PHP_EOL);
        fwrite($handle, "zone $zoneName" . PHP_EOL);

        return $handle;
    }

    private function sendAndCloseNsupdateHandle($handle)
    {
        fwrite($handle, 'send' . PHP_EOL);

        $code = pclose($handle);

        if ($code === 0) {
            return;
        }

        throw new Exception('Sending request failed', 400);
    }

    private function buildNsupdateCommand($action, $zoneName, $host, $type, $ttl, $data)
    {
        $fqdn = $host === '' ? $zoneName : "$host.$zoneName";

        return "update $action $fqdn $ttl IN $type $data";
    }
}
