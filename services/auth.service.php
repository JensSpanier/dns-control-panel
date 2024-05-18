<?php

class AuthService
{
    private $decryptedCookie;
    private $cookieName;

    public function __construct(
        private ConfigService $configService,
        private EncryptionService $encryptionService
    ) {
        $this->cookieName = $this->configService->getConfig('cookieName');
    }

    public function isLoggedIn()
    {
        if (!isset($_COOKIE[$this->cookieName])) {
            return false;
        }

        [
            'username' => $username,
            'password' => $password,
        ] = $this->decryptAndCacheCookie();

        try {
            $this->login($username, $password);
            return true;
        } catch (Throwable $th) {
            return false;
        }
    }

    public function login($username, $password)
    {
        $userConfig = $this->configService->getConfig('user');

        if (!array_key_exists($username, $userConfig)) {
            throw new Exception('Incorrect access data', 401);
        }

        $user = $userConfig[$username];

        if (!password_verify($password, $user['password'])) {
            throw new Exception('Incorrect access data', 401);
        }

        $serializedCookieContent = serialize([
            'username' => $username,
            'password' => $password,
        ]);
        $encryptedCookieContent = $this->encryptionService->encrypt($serializedCookieContent);

        $this->setCookie($encryptedCookieContent);
    }

    public function logout()
    {
        $this->setCookie('');
    }

    public function getUsername()
    {
        [
            'username' => $username,
        ] = $this->decryptAndCacheCookie();

        return $username;
    }

    private function decryptAndCacheCookie()
    {
        if (isset($this->decryptedCookie)) {
            return $this->decryptedCookie;
        }

        $decryptedCookie = $this->encryptionService->decrypt($_COOKIE[$this->cookieName]);

        $this->decryptedCookie = unserialize($decryptedCookie);

        return $this->decryptedCookie;
    }

    private function setCookie($content)
    {
        $cookieLifetime = $this->configService->getConfig('cookieLifetime');

        setcookie(
            $this->cookieName,
            $content,
            time() + $cookieLifetime,
            '/',
            '',
            isset($_SERVER['HTTPS']),
            true
        );
    }
}
