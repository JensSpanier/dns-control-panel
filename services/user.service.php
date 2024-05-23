<?php

class UserService
{
    public function __construct(
        private ConfigService $configService,
        private AuthService $authService
    ) {
    }

    public function getZones()
    {
        $userConfig = $this->configService->getConfig('user');
        $username = $this->authService->getUsername();

        $user = $userConfig[$username];

        return $user['zones'];
    }

    public function getDefaultTtl()
    {
        $userConfig = $this->configService->getConfig('user');
        $username = $this->authService->getUsername();

        $user = $userConfig[$username];

        return $user['defaultTtl'] ?? null;
    }

    public function getUsername()
    {
        return $this->authService->getUsername();
    }
}
