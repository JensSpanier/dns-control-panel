<?php if (!defined('APPLICATION_CONTEXT')) exit; ?>

<div class="alert alert-<?= $class ?> alert-dismissible fade show" role="alert">
    <?= $html ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>