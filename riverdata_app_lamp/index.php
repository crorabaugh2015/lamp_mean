<?php
require_once('dbconn.php');
?>
<html>
    <head>
        <title>Chat App</title>
        <link rel="stylesheet" href="bootstrap.css" />
        <style>
            table.striped, table.striped th , table.striped td {
                border: 1px solid grey;
                border-collapse: collapse;
                padding: 5px;
            }
            
            table.striped tr:nth-child(odd) {
                background-color: #f1f1f1;
            }
            
            table.striped tr:nth-child(even) {
                background-color: #ffffff;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">DataApp</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
            </div>
        </nav>
        <div style="padding:20px 50px">
            <h4>Get Water Levels</h4>
        <form action="" method="get">
            <table cellpadding="2">
            <tr>
                <td><label>Start Date</label></td><td><input class="block" autofocus type="text" name="date1" /></td>
            </tr>
            <tr>
                <td><label>End Date</label></td><td><input class="block" type="text" name="date2" /></td>
            </tr>
            <tr>
                <td><button type="submit">Submit</button></td>
            </tr>
            </table>
        </form>

<?php
if (isset($_GET['date1']) && isset($_GET['date2'])) {
    if (!$selection = $db->prepare("SELECT id, DataSource, StationID, StationName, Latitude_DD, Longitude_DD, SampleDate, ResultValue FROM riverdata WHERE SampleDate >= ? AND SampleDate <= ? ORDER BY SampleDate ASC")) {
        error_log("PHP MySQL Prepare Statement failed: ".$db->error, 0);
        echo '<h2>Error reading messages!1</h2>';
    }
    if (!$selection->bind_param("ss", $_GET['date1'], $_GET['date2'])) {
        error_log("PHP MySQL Bind Param Statement failed: ".$db->error, 0);
        echo '<h2>Error reading messages!2</h2>';
    }
    if (!$selection->execute()) {
        error_log("PHP MySQL Execute Statement failed: ".$db->error, 0);
        echo '<h2>Error reading messages!3</h2>';
    }
    if (!$result = $selection->get_result()) {
        error_log("PHP MySQL Get Result Statement failed: ".$db->error, 0);
        echo '<h2>Error reading messages!4</h2>';
    }
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $selection->close();
    if (count($data) == 0) {
        echo '<h2>No new messages!</h2>';
    } else {
        ?>
        <table class="striped">
            <tr>
                <th>Data Source</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Station Id</th>
                <th>Station Name</th>
                <th>Sample Date</th>
                <th>Height (ft)</th>
            </tr>
        
        <?php
        for ($i = 0; $i < count($data); $i++) {
            echo '<tr><td>'.$data[$i]['DataSource'].'</td><td>'.$data[$i]['Latitude_DD'].'</td><td>'.$data[$i]['Longitude_DD'].'</td><td>'.$data[$i]['StationID'].'</td><td>'.$data[$i]['StationName'].'</td><td>'.$data[$i]['SampleDate'].'</td><td>'.$data[$i]['ResultValue'].'</td></tr>';
        }
        echo '</table>';
    }
}
?>
    </div>
    </body>
</html>
