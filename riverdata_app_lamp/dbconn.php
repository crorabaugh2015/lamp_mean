<?php

// Create connection

$db = new mysqli('localhost', 'admin', 'admin', 'dataapp');

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
//echo "Connected successfully";

?>