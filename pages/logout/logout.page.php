<?php

class LogoutPage
{
    public function __construct(private AuthService $authService)
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                return $this->processGet();
            default:
                throw new Exception("Unknown request method '{$_SERVER['REQUEST_METHOD']}'", 405);
        }
    }

    private function processGet()
    {
        $this->authService->logout();
        header('Location: ?_page=login');
    }
}
