<?php
    #include './navigationhomenew.php';
    include './dbconnect.php';
    include './navbarindex.php';
    
    // Query for upcoming events
    $sql_events = "SELECT * FROM events WHERE fromdate > CURDATE() ORDER BY fromdate ASC";
    $result_events = mysqli_query($con, $sql_events);
    $count_events = $result_events->num_rows;
    
    // Query for latest announcements
    $sql_announcements = "SELECT * FROM announcements ORDER BY id";
    $result_announcements = mysqli_query($con, $sql_announcements);
    $count_announcements = $result_announcements->num_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./assets/css/stylesheetalumni.css" />
    <title>Alumni Events and Announcements</title>
</head>
<body>
    <div class="alumnimain">
        
        <!-- Events Section -->
        <div class="head"><b>EVENTS - UPCOMING EVENTS</b></div><br>
        <div class="content">
            View the upcoming alumni events and conferences!
            <br>
        </div>
        <?php
            while($count_events > 0) {
                $row_event = mysqli_fetch_array($result_events);
        ?>
            <div class="eventcontainer">
                <table width="100%" height="250px">
                    <tr>
                        <th>
                            <div class="eventname">
                                <?php echo strtoupper($row_event["eventname"]); ?>
                            </div>
                            <div class="eventdate">
                                DATE: <?php 
                                if($row_event["fromdate"] == $row_event["todate"]) {
                                    echo $row_event["fromdate"];
                                } else {
                                    echo $row_event["fromdate"] . " - " . $row_event["todate"];
                                }
                                ?>
                            </div>
                            <div class="eventdesc">
                                <?php echo $row_event["description"]; ?>
                            </div>
                        </th>
                        <th>
                            <a href="events/<?php echo $row_event["file_name"]; ?>" target="_blank">
                                <img id="myImg" src="events/<?php echo $row_event["file_name"]; ?>" alt="Event Image">
                            </a>                    
                        </th>
                    </tr>
                </table>        
            </div>
        <?php
                $count_events--;
            }
        ?>
        <br>                 
    </div>
    <hr style="border: 1px solid #ccc; margin: 20px 0;">   
    <div class="alumnimain">
        <!-- Announcements Section -->
        <div class="head"><b>ANNOUNCEMENTS</b></div><br>
        <div class="content announcement-content">
            Latest announcements and important updates!
        <br>
        </div>
        <?php if ($count_announcements > 0): ?>
            <table class="tablecontainer" id="table" border=black width=90% height=60%>
            <thead>
            <tr>
                <th>ID</th>
                <th>TITLE</th>
                <th>DATE</th>
                <th>DESCRIPTION</th>
            </tr>
            </thead>
            <tbody>
            <?php
                while ($count_announcements > 0) {
                    $row = mysqli_fetch_array($result_announcements);
                    echo "<tr><td>".$row["id"]."</td><td>".$row["announcement_title"]."</td><td>".$row["announcement_date"]."</td><td>".$row["announcement_description"]."</td></tr>";
                    $count_announcements--;
                }
            ?>
             </tbody>
            </table>
        <?php else: ?>
                <div class="no-announcements announcement-content" style="color: #101012;">No announcements available at this time.</div>
        <?php endif; ?>

        </div>
        <br>
        <br>
        <br>
        <hr style="border: 1px solid #ccc; margin: 20px>

</body>
</html>
