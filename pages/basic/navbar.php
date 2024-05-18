<?php
if (!defined('APPLICATION_CONTEXT')) exit;
$isLoggedIn = (bool) $username;
$page = $_GET['_page'];
$currentZone = $_GET['_zone'] ?? '';
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
        <a class="navbar-brand" href="?_page=dns">
            <i class="fa-solid fa-globe text-primary"></i> DNS Control
        </a>
        <?php if ($isLoggedIn) : ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <?php if ($zones) : ?>
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Zones
                            </a>
                            <ul class="dropdown-menu">
                                <?php foreach ($zones as $zone) : ?>
                                    <li>
                                        <a class="dropdown-item <?= $page === 'dns' && $currentZone === $zone ? 'active' : '' ?>" href="?_page=dns&_zone=<?= $zone ?>">
                                            <?= $zone ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    </ul>
                <?php endif; ?>

                <ul class="navbar-nav ms-auto mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= $username ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="?_page=logout">
                                    <i class="fa-solid fa-right-from-bracket"></i> Abmelden
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</nav>