<?php
session_start();
?>

<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="bootstrap.css" />
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">ChatApp</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="/" routerLinkActive = "active">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/login.php" routerLinkActive = "active">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register.php" routerLinkActive = "active" >Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/create.php" routerLinkActive = "active" >Create Chat</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div style="padding:20px 50px">
<?php
if (isset($_SESSION['username'])) { 
    echo '<h1>You already are logged in! <a href="/">Go to site</a><br /><a href="/logout.php">Log out</a></h1></body></html>';
    exit;
}

if (isset($_POST['submitted'])) {

    require_once('dbconn.php');

    if (!$selection = $db->prepare("SELECT username FROM users WHERE LOWER(username) = LOWER(?) AND password = ?")) {
        error_log("PHP MySQL Prepare Statement failed: ".$db->error, 0);
        echo '<h1>Error logging in!</h1>';
    }
    if (!$selection->bind_param("ss", $_POST['username'], sha1($_POST['password']))) {
        error_log("PHP MySQL Bind Param Statement failed: ".$db->error, 0);
        echo '<h1>Error logging in!</h1>';
    }
    if (!$selection->execute()) {
        error_log("PHP MySQL Execute Statement failed: ".$db->error, 0);
        echo '<h1>Error logging in!</h1>';
    }
    if (!$result = $selection->get_result()) {
        error_log("PHP MySQL Get Result Statement failed: ".$db->error, 0);
        echo '<h1>Error logging in!</h1>';
    }
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $selection->close();
    if (count($data) == 1) {
        echo '<h1>Successfully logged in as '.$data[0]['username'].'. <a href="/">Go to site</a></h1></h1>';
        $_SESSION['username'] = $data[0]['username'];
    } else {
        echo '<h1>Username or password is incorrect!</h1>';
    }
}

?>

        <h1>Login</h1>
        <form action="" method="post">
            <table cellpadding="2">
            <tr>
                <td><label for="login-name">Username</label></td><td><input class="block" autofocus type="text" name="username" /></td>
            </tr>
            <tr>
                <td><label for="login-pass">Password</label></td><td><input class="block" type="password" name="password" /></td>
            </tr>
            <tr>
                <td><button type="submit">Log In</button></td>
            </tr>
            </table>
            <input type="hidden" value="submitted" name="submitted" />
        </form>
        <br />
        Need an account? <a href="register.php">Register Here</a>
</div>
    </body>
</html>