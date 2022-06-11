<?php
session_start();
?>
<html>
    <head>
        <title>Create Chat</title>
        <link rel="stylesheet" href="bootstrap.css" />
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>

<?php
if (!isset($_SESSION['username'])) {
    // error_log('Already have session, reloating..');
    header('Location: login.php');
    exit;
}

if (isset($_POST['submitted'])) {

    require_once('dbconn.php');

    if (!$selection = $db->prepare("SELECT username FROM users  WHERE LOWER(username) = LOWER(?)")) {
        error_log("PHP MySQL Prepare Statement failed: ".$db->error, 0);
        echo '<h1>Error sending message!</h1>';
    }
    if (!$selection->bind_param("s", $_POST['username'])) {
        error_log("PHP MySQL Bind Param Statement failed: ".$db->error, 0);
        echo '<h1>Error sending message!</h1>';
    }
    if (!$selection->execute()) {
        error_log("PHP MySQL Execute Statement failed: ".$db->error, 0);
        echo '<h1>Error sending message!</h1>';
    }
    if (!$result = $selection->get_result()) {
        error_log("PHP MySQL Get Result Statement failed: ".$db->error, 0);
        echo '<h1>Error sending message!</h1>';
    }
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $selection->close();
    if (count($data) > 0) {
        if (!$stmt = $db->prepare("INSERT INTO chats (username_from, username_to, contents) VALUES (?, ?, ?)")) {
            error_log("PHP MySQL Prepare Statement failed: ".$db->error, 0);
            echo '<h1>Error sending message!</h1>';
        }
    
        if (!$stmt->bind_param("sss", $_SESSION['username'], $_POST['username'], $_POST['message'])) {
            error_log("PHP MySQL Bind Statement failed: ".$db->error, 0);
            echo '<h1>Error sending message!</h1>';
        }
    
        if ($stmt->execute()) {
            echo '<h1>Success!</h1>';
        } else {
            error_log("PHP MySQL Execute Statement failed: ".$db->error, 0);
            echo '<h1>Error sending message!</h1>';
            $stmt->close();
        }
    } else {
        echo '<h1>Error! User does not exist</h1>';
    }
}

?>
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
    <h1>Create Chat</h1>
        <form action="" method="post">
            <table cellpadding="2">
            <tr>
                <td><label for="login-name">Username To</label></td><td><input class="block" autofocus type="text" name="username" /></td>
            </tr>
            <tr>
                <td><label for="login-pass">Message</label></td><td><input class="block" type="text" name="message" /></td>
            </tr>
            <tr>
                <td><button type="submit">Submit</button></td>
            </tr>
            </table>
            <input type="hidden" value="submitted" name="submitted" />
        </form>
</div>
    </body>
</html>