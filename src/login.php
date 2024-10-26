<?php
session_start();

$title = 'Login';
include_once 'header.php';
?>

<h1 class="header">Login</h1>
<form class="login-form main-text-fields" action="login.php" method="POST">
    <div class="row">
        <div class="column-1-4">
            <label for="login">Username</label>
        </div>
        <div class="column-3-4">
            <input type="text" name="login" id="login" required/>
        </div>
    </div>
    <div class="row">
        <div class="column-1-4">
            <label for="password">Password</label>
        </div>
        <div class="column-3-4">
            <input type="password" name="password" id="password" required />
        </div>
    </div>
    <div class="row">
        <div class="column">
            <span class="main-buttons">
                <input type="submit" value="Login"/>
            </span>
        </div>
    </div>
</form>

<?php
require_once 'common-helpers.php';
print_footer();
?>
