<?php
if (!defined('APPLICATION_CONTEXT')) exit;
$this->helperService->printStart('Login');
$this->helperService->printNavbar();
$this->helperService->printContentStart();
?>

<h1>Login</h1>

<form method="post">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
    <button type="submit" class="btn btn-primary">
        <i class="fa-solid fa-right-to-bracket"></i> Log in
    </button>
</form>

<?php
$this->helperService->printContentEnd();
$this->helperService->printEnd();
?>