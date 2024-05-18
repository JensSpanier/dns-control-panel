<?php

class HelperService
{
    public function __construct(private AlertService $alertService)
    {
    }

    public function printStart($pageTitle = '')
    {
        require __DIR__ . '/../pages/basic/start.php';
    }

    function printEnd()
    {
        require __DIR__ . '/../pages/basic/end.php';
    }

    function printContentStart()
    {
        require __DIR__ . '/../pages/basic/content-start.php';

        $alertMessages = $this->alertService->getMessages();

        foreach ($alertMessages as ['html' => $html, 'class' => $class]) {
            require __DIR__ . '/../pages/basic/alert.php';
        }
    }

    function printContentEnd()
    {
        require __DIR__ . '/../pages/basic/content-end.php';
    }

    function printNavbar($username = '', $zones = [])
    {
        require __DIR__ . '/../pages/basic/navbar.php';
    }
}
