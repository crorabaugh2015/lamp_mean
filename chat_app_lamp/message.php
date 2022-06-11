<?php

session_start();

if (!isset($_SESSION['username'])) {
    // error_log('Already have session, reloating..');
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    // error_log('Already have session, reloating..');
    echo '<h1>No message selected</h1>';
    exit;
}

require_once('dbconn.php');
?>
<html>
    <head>
        <title>Chat App</title>
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
        <h1>View Message</h1>
<?php
    if (!$selection = $db->prepare("SELECT id, username_from, datetime_sent, contents FROM chats WHERE id = ? LIMIT 1")) {
        error_log("PHP MySQL Prepare Statement failed: ".$db->error, 0);
        echo '<h2>Error reading messages!</h2>';
    }
    if (!$selection->bind_param("i", $_GET['id'])) {
        error_log("PHP MySQL Bind Param Statement failed: ".$db->error, 0);
        echo '<h2>Error reading messages!</h2>';
    }
    if (!$selection->execute()) {
        error_log("PHP MySQL Execute Statement failed: ".$db->error, 0);
        echo '<h2>Error reading messages!</h2>';
    }
    if (!$result = $selection->get_result()) {
        error_log("PHP MySQL Get Result Statement failed: ".$db->error, 0);
        echo '<h2>Error reading messages!</h2>';
    }
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $selection->close();

    if (!$stmt = $db->prepare("UPDATE chats SET seen = 1 WHERE id=?")) {
        error_log("PHP MySQL Prepare Statement failed: ".$db->error, 0);
        echo '<h2>Error updating messages!</h2>';
    }
    /* Bind our params */
    if (!$stmt->bind_param('i', $_GET['id'])) {
        error_log("PHP MySQL Bind Param Statement failed: ".$db->error, 0);
        echo '<h2>Error updating messages!</h2>';
    }
    if (!$stmt->execute()) {
        error_log("PHP MySQL Execute Statement failed: ".$db->error, 0);
        echo '<h2>Error updating messages!</h2>';
    }
    $stmt->close();

    if (count($data) == 0) {
        echo '<h2>No message found!</h2>';
    } else {
        if ($_SESSION['id'] == $data[0]['username_to']) {
            echo '<h2>From: '.$data[0]['username_from'].'</h2>';
            echo '<h3>Datetime Sent: '.$data[0]['datetime_sent'].'</h3>';
            echo '<h3>Message:</h3><p>'.$data[0]['contents'].'</p>';
        } else {
            echo '<h2>Unauthorized to view message!</h2>';
        }

    }
?>
        </div>
    </body>
</html>