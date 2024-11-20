<?php
include './navbaradmin.php';
include './dbconnect.php';
session_start();
//ini_set('display_errors', 1);
// Check if admin is logged in
// Check if admin is logged in
if (!(isset($_SESSION['logged_in']))) {
    header("Location: adminlogin.php");
}

// Initialize variables
$announcement_title = $announcement_date = $announcement_description = $msg = "";
$sql = "SELECT * FROM announcements ORDER BY id";
$result = mysqli_query($con, $sql);
$count = $result->num_rows;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add announcement logic
    if (isset($_POST['submit'])) {
        $announcement_title = test_input($_POST["announcement_title"]);
        $announcement_date = test_input($_POST["announcement_date"]);
        $announcement_description = test_input($_POST["announcement_description"]);

        // Get the next available unique id
        $result = mysqli_query($con, "SELECT MAX(id) AS max_id FROM announcements");
        $row = mysqli_fetch_assoc($result);
        $Next_id = $row['max_id'] + 1;
        // Insert announcement (without specifying 'id', as it's auto-generated)
        $query = "INSERT INTO announcements(id,announcement_title, announcement_date, announcement_description) 
                  VALUES ('$Next_id','$announcement_title', '$announcement_date', '$announcement_description')";
        if (mysqli_query($con, $query)) {
            $msg = "Announcement Added Successfully!";
        } else {
            $msg = "Error adding announcement: " . mysqli_error($con);
        }
    }

    // Delete announcement logic
    if (isset($_POST['delete'])) {
        $announcement_id = test_input($_POST["announcement_id"]);

        // Check if announcement exists before deleting
        $sqlCheck = "SELECT * FROM announcements WHERE id='$announcement_id'";
        $resultCheck = mysqli_query($con, $sqlCheck);

        if (mysqli_num_rows($resultCheck) > 0) {
            $sqlDelete = "DELETE FROM announcements WHERE id='$announcement_id'";
            if (mysqli_query($con, $sqlDelete)) {
                // Call the SQL function to reassign IDs if necessary (for deletions)
                $sqlReassign = "SELECT reassign_announcement_ids() AS msg";
                $resultReassign = mysqli_query($con, $sqlReassign);
                if ($resultReassign) {
                    $row = mysqli_fetch_assoc($resultReassign);
                    $msg = $row['msg'];  // This will contain the success message from the function
                } else {
                    $msg = "Error reassigning announcement IDs: " . mysqli_error($con);
                }
            } else {
                $msg = "Error deleting announcement: " . mysqli_error($con);
            }
        } else {
            $msg = "Announcement ID not found!";
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
    <title>Announcement Management</title>
</head>
<body>
    <!-- Modal for Deleting Announcements -->
    <div class="bg-modal">
        <div class="modal-content">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                <span class="close">&times;</span>
                <center><h1>Delete Announcement</h1></center><br><br>
                Announcement ID <input type="text" placeholder="Enter announcement ID" name="announcement_id" autocomplete="off" required>
                <center><button type="submit" class="delbtn" name="delete" value="delete">DELETE</button></center>
            </form>
        </div>
    </div>

    <!-- Modal for Adding Announcements -->
    <div class="bg-modal1">
        <div class="modal-content1">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                <span class="close1" onclick="close1()">&times;</span>
                <center><h2>Add Announcement</h2></center>
                Title<br>
                <input type="text" placeholder="Enter title" name="announcement_title" autocomplete="off" required><br>
                Date<br>
                <input type="date" name="announcement_date" min="<?=date('Y-m-d');?>" required><br>
                Description<br>
                <textarea name="announcement_description" id="announcement_description" cols="30" rows="2" placeholder="Description" autocomplete="off" required></textarea><br>
                <button type="submit" class="btn" name="submit" value="submit">ADD</button>
            </form>
        </div>
    </div>

    <!-- Main Admin Panel -->
    <div class="adminmain">
        <div class="head">ADD / DELETE ANNOUNCEMENTS</div>
        <button class="open-button" id="addbtn" onclick="add()">Add Announcement</button>
        <button class="open-button" id="delbtn" onclick="del()">Delete Announcement</button>
        <br><br>
        <div class="head">ANNOUNCEMENTS</div>
        <div class="message">
            <?php if ($msg != "") { echo $msg; } ?>
        </div>
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
                    while ($count > 0) {
                        $row = mysqli_fetch_array($result);
                        echo "<tr><td>".$row["id"]."</td><td>".$row["announcement_title"]."</td><td>".$row["announcement_date"]."</td><td>".$row["announcement_description"]."</td></tr>";
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
