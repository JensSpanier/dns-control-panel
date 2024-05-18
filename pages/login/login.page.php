<?php

class LoginPage
{
    public function __construct(
        private HelperService $helperService,
        private AuthService $authService,
        private AlertService $alertService,
    ) {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                return $this->processGet();
            case 'POST':
                return $this->processPost();
            default:
                throw new Exception("Unknown request method '{$_SERVER['REQUEST_METHOD']}'", 405);
        }
    }

    private function processPost()
    {
        [
            'username' => $username,
            'password' => $password,
        ] = $_POST;

        try {
            $this->authService->login($username, $password);
            header("Location: {$_SERVER['REQUEST_URI']}&_page=dns");
        } catch (Exception $e) {
            $this->alertService->addMessage($e->getMessage(), 'danger');
            header("Location: {$_SERVER['REQUEST_URI']}");
        }
    }

    private function processGet()
    {
        require __DIR__ . '/html.php';
    }
}
