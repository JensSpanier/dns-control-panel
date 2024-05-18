<?php

class ZoneService
{
    public function __construct(private ConfigService $configService)
    {
    }

    public function getZone($name)
    {
        $zones = $this->configService->getConfig('zones');

        if (!array_key_exists($name, $zones)) {
            throw new Exception("Zone '$name' not found", 404);
        }

        return $zones[$name];
    }
}
