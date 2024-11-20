<?php
include './navbaradmin.php';
include './dbconnect.php';
session_start();
ini_set('display_errors', 1);

if (!(isset($_SESSION['logged_in']))) {
    header("Location: adminlogin.php");  
}

// Initialize variables
$eventname = $organizer = $description = $fromdate = $todate = $file = $file_loc = $final_file = $msg = "";
$sql = "SELECT * FROM events ORDER BY id";
$result = mysqli_query($con, $sql);
$count = $result->num_rows;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add event logic
    if (isset($_POST['submit'])) {
        $eventname = test_input($_POST["eventname"]);
        $organizer = test_input($_POST["organizer"]);
        $description = test_input($_POST["description"]);
        $fromdate = test_input($_POST["fromdate"]);
        $todate = test_input($_POST["todate"]);
        
        // Get the next available unique id
        $result = mysqli_query($con, "SELECT MAX(id) AS max_id FROM events");
        $row = mysqli_fetch_assoc($result);
        $next_id = $row['max_id'] + 1;
        
        // File upload handling
        if (isset($_FILES['file'])) {
            $file = $_FILES['file']['name'];
            $file_loc = $_FILES['file']['tmp_name'];
            $folder = "events/";

            // Make file name lowercase and replace spaces with hyphens
            $new_file_name = strtolower($file);
            $final_file = str_replace(' ', '-', $new_file_name);

            // Move uploaded file to destination folder
            if (move_uploaded_file($file_loc, $folder . $final_file)) {
                $query = "INSERT INTO events(id, eventname, organizer, description, fromdate, todate, file_name) 
                          VALUES ('$next_id', '$eventname', '$organizer', '$description', '$fromdate', '$todate', '$final_file')";
                if (mysqli_query($con, $query)) {
                    $msg = "Event Added Successfully!";
                } else {
                    $msg = "Error adding event: " . mysqli_error($con);
                }
            } else {
                $msg = "Error uploading file.";
            }
        }
    }

    // Delete event logic
    if (isset($_POST['delete'])) {
        $eventid = test_input($_POST["eventid"]);

        // Check if event exists before deleting
        $sqlCheck = "SELECT * FROM events WHERE id='$eventid'";
        $resultCheck = mysqli_query($con, $sqlCheck);

        if (mysqli_num_rows($resultCheck) > 0) {
            $sqlDelete = "DELETE FROM events WHERE id='$eventid'";
            if (mysqli_query($con, $sqlDelete)) {
                // After deletion, call the function to reassign IDs
                $sqlReassign = "SELECT reassign_event_ids() AS msg";
                $resultReassign = mysqli_query($con, $sqlReassign);

                if ($resultReassign) {
                    $row = mysqli_fetch_assoc($resultReassign);
                    $msg = $row['msg'];  // This will contain the success message from the function
                } else {
                    $msg = "Error reassigning event IDs: " . mysqli_error($con);
                }
            } else {
                $msg = "Error deleting event: " . mysqli_error($con);
            }
        } else {
            $msg = "Event ID not found!";
        }
    }
}

// Function to sanitize user input
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link rel="stylesheet" type="text/css" href="./assets/css/stylesheetalumni.css" />
    <title>Event Management</title>
</head>
<body>
    <!-- Modal for Deleting Events -->
    <div class="bg-modal">
        <div class="modal-content">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                <span class="close">&times;</span>
                <center><h1>Delete Events</h1></center><br><br>
                Event ID <input type="text" placeholder="Enter event ID" name="eventid" autocomplete="off" required>
                <center><button type="submit" class="delbtn" name="delete" value="delete">DELETE</button></center>
            </form>
        </div>
    </div> 

    <!-- Modal for Adding Events -->
    <div class="bg-modal1">
        <div class="modal-content1">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" enctype="multipart/form-data" method="post">
                <span class="close1" onclick="close1()">&times;</span>
                <center><h2>Add Events</h2></center>
                Event Name<br>
                <input type="text" placeholder="Enter event name" name="eventname" autocomplete="off" required><br>
                Organizer<br>
                <input type="text" placeholder="Enter organizer name" name="organizer" autocomplete="off" required><br>
                Description<br>
                <textarea name="description" id="description" cols="30" rows="2" placeholder="Event description" autocomplete="off" required></textarea><br>
                From <input type="date" name="fromdate" min="<?=date('Y-m-d');?>" required>
                To <input type="date" name="todate" min="<?=date('Y-m-d');?>" required><br>
                Upload Poster <input type="file" name="file" required><br>
                <button type="submit" class="btn" name="submit" value="submit">ADD</button>
            </form>
        </div>
    </div>

    <!-- Main Admin Panel -->
    <div class="adminmain">
        <div class="head">ADD / DELETE EVENTS</div>
        <button class="open-button" id="addbtn" onclick="add()">Add Events</button>
        <button class="open-button" id="delbtn" onclick="del()">Delete Events</button>
        <br><br>
        <div class="head">UPCOMING EVENTS</div>
        <div class="message">
            <?php if ($msg != "") { echo $msg; } ?>
        </div>
        <table class="tablecontainer" id="table" border=black width=90% height=60%>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>EVENT NAME</th>
                    <th>ORGANIZER</th>
                    <th>EVENT DESCRIPTION</th>
                    <th>FROM DATE</th>
                    <th>TO DATE</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while ($count > 0) {
                        $row = mysqli_fetch_array($result);
                        echo "<tr><td>".$row["id"]."</td><td>".$row["eventname"]."</td><td>".$row["organizer"]."</td><td>".$row["description"]."</td><td>".$row["fromdate"]."</td><td>".$row["todate"]."</td></tr>";
                        $count = $count - 1;
                    }
                ?>
            </tbody>
        </table>
        <br><br>      
    </div> 

    <!-- JavaScript for Modal Controls -->
    <script>
        function add() {
            document.querySelector('.bg-modal1').style.display = 'flex';
        }

        function del() {
            document.querySelector('.bg-modal').style.display = 'flex';
        }

        function close1() {
            document.querySelector('.bg-modal1').style.display = "none";
        }

        function close() {
            document.querySelector('.bg-modal').style.display = "none";
        }
        
        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>
