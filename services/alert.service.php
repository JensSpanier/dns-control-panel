<?php

class AlertService
{
    private $cookieName;
    private $messages = [];

    public function __construct(private ConfigService $configService)
    {
        $this->cookieName = $this->configService->getConfig('cookieName') . '_alerts';
        $this->messages = $this->getMessagesFromCookie();
        $this->deleteCookie();
    }

    public function addMessage($html, $class = 'danger')
    {
        $this->messages[] = [
            'html' => $html,
            'class' => $class,
        ];

        $this->setCookie(serialize($this->messages));
    }

    public function getMessages()
    {
        return $this->messages;
    }

    private function getMessagesFromCookie()
    {
        if (!isset($_COOKIE[$this->cookieName])) {
            return [];
        }

        $cookieContent = $_COOKIE[$this->cookieName];

        $messages = unserialize($cookieContent);
        return $messages;
    }

    private function deleteCookie()
    {
        $this->setCookie('');
    }

    private function setCookie($content)
    {
        setcookie(
            $this->cookieName,
            $content,
            0,
            '/',
            '',
            isset($_SERVER['HTTPS']),
            true,
        );
    }
}
