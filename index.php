<?php

require_once __DIR__ . '/services/alert.service.php';
require_once __DIR__ . '/services/auth.service.php';
require_once __DIR__ . '/services/config.service.php';
require_once __DIR__ . '/services/dns.service.php';
require_once __DIR__ . '/services/encryption.service.php';
require_once __DIR__ . '/services/helper.service.php';
require_once __DIR__ . '/services/user.service.php';
require_once __DIR__ . '/services/zone.service.php';

define('APPLICATION_CONTEXT', true);

try {
    $configService = new ConfigService();
    $alertService = new AlertService($configService);
    $zoneService = new ZoneService($configService);
    $dnsService = new DnsService($zoneService, $configService);
    $helperService = new HelperService($alertService);
    $encryptionService = new EncryptionService($configService);
    $authService = new AuthService($configService, $encryptionService);
    $userService = new UserService($configService, $authService);

    if (empty($_GET['_page'])) {
        return header('Location: /?_page=dns');
    }

    $page = $_GET['_page'];

    // Make sure user is logged in

    if ($page === 'login') {
        require_once __DIR__ . '/pages/login/login.page.php';
        return new LoginPage($helperService, $authService, $alertService);
    }

    if (!$authService->isLoggedIn()) {
        return header('Location: /?_page=login&_redirect=' . urlencode($_SERVER['REQUEST_URI']));
    }

    if (isset($_GET['_redirect'])) {
        return header("Location: {$_GET['_redirect']}");
    }

    switch ($page) {
        case 'logout':
            require_once __DIR__ . '/pages/logout/logout.page.php';
            return new LogoutPage($authService);
        case 'dns':
            require_once __DIR__ . '/pages/dns/dns.page.php';
            return new DnsPage($helperService, $userService, $dnsService, $configService, $alertService);
        default:
            throw new Exception("Page '$page' not found", 404);
    }
} catch (Exception $e) {
    $errorCode = $e->getCode();
    http_response_code($errorCode ?? 500);
    header('Content-Type: text/plain; charset=UTF-8');
    echo $e->getMessage();
}
