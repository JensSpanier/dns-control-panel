<?php

class DnsPage
{
    private $zone;
    private $userZones;

    public function __construct(
        private HelperService $helperService,
        private UserService $userService,
        private DnsService $dnsService,
        private ConfigService $configService,
        private AlertService $alertService
    ) {
        $this->zone = $_GET['_zone'] ?? '';
        $this->userZones = $userService->getZones();

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                if (!$this->zone && $this->userZones) {
                    return header("Location: ?_page=dns&_zone={$this->userZones[0]}");
                }
                return $this->processGet();
            case 'POST':
                $this->checkZoneValid();
                return $this->processPost();
            default:
                throw new Exception("Unknown request method '{$_SERVER['REQUEST_METHOD']}'", 405);
        }
    }

    private function processPost()
    {
        $zoneName = $this->zone;
        [
            'action' => $action,
            'newHost' => $newHost,
            'currentHost' => $currentHost,
            'newType' => $newType,
            'currentType' => $currentType,
            'newTtl' => $newTtl,
            'currentTtl' => $currentTtl,
            'newData' => $newData,
            'currentData' => $currentData,
        ] = $_POST;

        try {
            switch ($action) {
                case 'add':
                    $this->dnsService->addDnsRecord($zoneName, $newHost, $newType, $newTtl, $newData);
                    $this->alertService->addMessage('Record successfully added', 'success');
                    break;
                case 'update':
                    $this->dnsService->updateDnsRecord($zoneName, $currentHost, $currentType, $currentTtl, $currentData, $newHost, $newType, $newTtl, $newData);
                    $this->alertService->addMessage('Record successfully updated', 'success');
                    break;
                case 'delete':
                    $this->dnsService->deleteDnsRecord($zoneName, $currentHost, $currentType, $currentTtl, $currentData);
                    $this->alertService->addMessage('Record successfully deleted', 'success');
                    break;
                default:
                    throw new Exception("Unknown action '$action'", 400);
            }
        } catch (Exception $e) {
            $this->alertService->addMessage($e->getMessage());
        }

        header("Location: {$_SERVER['REQUEST_URI']}");
    }

    private function processGet()
    {
        $username = $this->userService->getUsername();
        $zones = $this->userZones;
        $zone = $this->zone;
        $recordTypes = $this->configService->getConfig('recordTypes');

        if ($zone) {
            $this->checkZoneValid();
            $records = $this->dnsService->getDnsRecords($zone);
        }

        require __DIR__ . '/html.php';
    }

    private function checkZoneValid()
    {
        if (in_array($this->zone, $this->userZones)) {
            return;
        }

        throw new Exception("Zone '{$this->zone}' not found", 404);
    }
}
