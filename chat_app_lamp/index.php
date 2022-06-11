<?php

session_start();

if (!isset($_SESSION['username'])) {
    // error_log('Already have session, reloating..');
    header('Location: login.php');
    exit;
}

require_once('dbconn.php');
?>
<html>
    <head>
        <title>Chat App</title>
        <link rel="stylesheet" href="bootstrap.css" />
        <link rel="stylesheet" href="style.css" />
        <style>
            table, th , td {
                border: 1px solid grey;
                border-collapse: collapse;
                padding: 5px;
            }
            
            table tr:nth-child(odd) {
                background-color: #f1f1f1;
            }
            
            table tr:nth-child(even) {
                background-color: #ffffff;
            }
        </style>
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
        <h1>My Unread Messages</h1>
<?php
    if (!$selection = $db->prepare("SELECT id, username_from, datetime_sent FROM chats WHERE username_to = ? AND seen = 0")) {
        error_log("PHP MySQL Prepare Statement failed: ".$db->error, 0);
        echo '<h2>Error reading messages!</h2>';
    }
    if (!$selection->bind_param("s", $_SESSION['username'])) {
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
    if (count($data) == 0) {
        echo '<h2>No new messages!</h2>';
    } else {
        ?>
        <table>
            <tr>
                <th>From</th>
                <th>Date Sent</th>
                <th>View Message</th>
            </tr>
        
        <?php
        for ($i = 0; $i < count($data); $i++) {
            echo '<tr><td>'.$data[$i]['username_from'].'</td><td>'.$data[$i]['datetime_sent'].'</td><td><a href="/message.php?id='.$data[$i]['id'].'">View Message</a></td></tr>';
        }
        echo '</table>';
    }
?>
        <h1>My Older Messages</h1>
        <?php
    if (!$selection = $db->prepare("SELECT id, username_from, datetime_sent FROM chats WHERE username_to = ? AND seen = 1")) {
        error_log("PHP MySQL Prepare Statement failed: ".$db->error, 0);
        echo '<h2>Error reading messages!</h2>';
    }
    if (!$selection->bind_param("s", $_SESSION['username'])) {
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
    if (count($data) == 0) {
        echo '<h2>No old messages!</h2>';
    } else {
        ?>
        <table>
            <tr>
                <th>From</th>
                <th>Date Sent</th>
                <th>View Message</th>
            </tr>
        
        <?php
        for ($i = 0; $i < count($data); $i++) {
            echo '<tr><td>'.$data[$i]['username_from'].'</td><td>'.$data[$i]['datetime_sent'].'</td><td><a href="/message.php?id='.$data[$i]['id'].'">View Message</a></td></tr>';
        }
        echo '</table>';
    }
?>
    </div>
    </body>
</html>