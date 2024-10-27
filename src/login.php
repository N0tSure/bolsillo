<?php
session_start();

// Whether user authenticated go to home
if (!empty($_SESSION['user_id'])):
    header('Location: index.php', true, 303);
    exit;
endif;

// Declare error: no error initially
$error = false;

// If this is login form submission then authenticate user
if (!empty($_POST['login']) && !empty($_POST['password'])):
    $login = trim($_POST['login']);
    $passwd = trim($_POST['password']);

    // Import User service
    require_once 'service.php';
    $service = new UserService();

    // Get existing User
    try {
        $user = $service->findUserByLoginAndPassword($login, $passwd);
        // If user found set session and go home.
        if ($user):
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_name'] = $user->getName();
            header('Location: index.php', true, 303);
            exit;
        // If user not found set error and proceed to login form
        else:
            $error = 'Username or/and password not correct';
        endif;
    } catch (Exception $exc) {
        // In case of error, log exception message, set error and proceed to login form
        $msg = $exc->getMessage();
        $error = "There is problem with authentication: $msg";
        error_log("Error when getting user: $msg");
        if ($exc->getPrevious()):
            error_log('Suspended error: '.$exc->getPrevious()->getMessage());
        endif;
    }
endif;

// Displaying login form
$title = 'Login';
include_once 'header.php';
?>

<h1 class="header">Login</h1>
<form class="login-form main-text-fields" action="login.php" method="POST">
<!--    Error message banner -->
    <div class="row <?= $error ? '' : 'absent' ?>">
        <div class="column">
            <div class="error-message">
                <span class="error-message-box"><?= $error ?></span>
            </div>
        </div>
    </div>
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
// Printing footer
require_once 'common-helpers.php';
print_footer();
?>
