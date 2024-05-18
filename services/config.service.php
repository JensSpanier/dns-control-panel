<?php

class ConfigService
{
    private $config = [];

    public function __construct()
    {
        require_once __DIR__ . '/../config/defaults.php';
        $this->config = array_merge($this->config, $config);

        require_once __DIR__ . '/../config/config.php';
        $this->config = array_merge($this->config, $config);
    }

    public function getConfig($key)
    {
        return $this->config[$key];
    }
}
