<?php
include './navigationbar.php';
include './dbconnect.php';
session_start();
//ini_set('display_errors', 1);

// Check if alumni is logged in
if (!isset($_SESSION['alumni_id'])) {
    header("Location: alumnilogin.php"); // Redirect to alumni login page
    exit; // Ensure to exit after redirect
}
$_SESSION["logged_in"] = [
    "alumni_id" => $row["alumni_id"],
    "email" => $row["email"],
    "password" => $row["password"],
  ];
//var_dump($_SESSION['alumni_id']);
//var_dump($_SESSION['email']);
  if (!isset($_SESSION['email'])) {
    header("Location: alumnilogin.php"); // Redirect to alumni login page
    exit; // Ensure to exit after redirect
}
//var_dump($_SESSION[logged_in]['alumni_id']);
//var_dump($_SESSION['email']);
// Initialize variables
$alumniEmail = $_SESSION['email']; // Get the email from the session
$msg = "";
$alumniDetails = [];
//var_dump($alumniEmail);
// Call the stored procedure to get alumni profile using the logged-in email
$sql = "CALL DisplayAlumniProfile(?)";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "s", $alumniEmail);

if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $alumniDetails = $row;
    } else {
        $msg = "No alumni found with that email address.";
    }
} else {
    $msg = "Error executing procedure: " . mysqli_error($con);
}

mysqli_stmt_close($stmt);

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
    <title>View Alumni Details</title>
</head>
<body>
    <div class="adminmain">
        <div class="head">VIEW ALUMNI DETAILS</div>
        
        <div class="message">
            <?php if ($msg != "") { echo $msg; } ?>
        </div>
        
        <?php if (!empty($alumniDetails)): ?>
    <h3>Alumni Profile</h3>
        <table class="tablecontainer" id="table" border="black" width="90%" height="60%">
            <tr><th>Field</th><th>Details</th></tr>
            <tr><td>Full Name</td><td><?php echo !empty($alumniDetails['Full_Name']) ? $alumniDetails['Full_Name'] : 'NIL'; ?></td></tr>
            <tr><td>Date of Birth</td><td><?php echo !empty($alumniDetails['Date_of_Birth']) ? $alumniDetails['Date_of_Birth'] : 'NIL'; ?></td></tr>
            <tr><td>Phone Number</td><td><?php echo !empty($alumniDetails['Phone_Number']) ? $alumniDetails['Phone_Number'] : 'NIL'; ?></td></tr>
            <tr><td>Degree</td><td><?php echo !empty($alumniDetails['Degree']) ? $alumniDetails['Degree'] : 'NIL'; ?></td></tr>
            <tr><td>Specialization</td><td><?php echo !empty($alumniDetails['Specialization']) ? $alumniDetails['Specialization'] : 'NIL'; ?></td></tr>
            <tr><td>Branch</td><td><?php echo !empty($alumniDetails['Branch']) ? $alumniDetails['Branch'] : 'NIL'; ?></td></tr>
            <tr><td>Graduation Year</td><td><?php echo !empty($alumniDetails['Graduation_Year']) ? $alumniDetails['Graduation_Year'] : 'NIL'; ?></td></tr>
            <tr><td>Company</td><td><?php echo !empty($alumniDetails['Company']) ? $alumniDetails['Company'] : 'NIL'; ?></td></tr>
            <tr><td>Job Title</td><td><?php echo !empty($alumniDetails['Job_Title']) ? $alumniDetails['Job_Title'] : 'NIL'; ?></td></tr>
            <tr><td>Is Working</td><td><?php echo isset($alumniDetails['Is_Working']) ? ($alumniDetails['Is_Working'] ? 'Yes' : 'No') : 'NIL'; ?></td></tr>
            <tr><td>LinkedIn</td><td><?php echo !empty($alumniDetails['LinkedIn']) ? $alumniDetails['LinkedIn'] : 'NIL'; ?></td></tr>
            <tr><td>GitHub</td><td><?php echo !empty($alumniDetails['GitHub']) ? $alumniDetails['GitHub'] : 'NIL'; ?></td></tr>
            <tr><td>College Name</td><td><?php echo !empty($alumniDetails['College_Name']) ? $alumniDetails['College_Name'] : 'NIL'; ?></td></tr>
            <tr><td>Course Name</td><td><?php echo !empty($alumniDetails['Course_Name']) ? $alumniDetails['Course_Name'] : 'NIL'; ?></td></tr>
            <tr><td>Is Studying</td><td><?php echo isset($alumniDetails['Is_Studying']) ? ($alumniDetails['Is_Studying'] ? 'Yes' : 'No') : 'NIL'; ?></td></tr>
        </table>


    
    <!-- Add Update Profile Button -->
    <form action="updateprofile.php" method="get">
        <input type="hidden" name="alumni_email" value="<?php echo htmlspecialchars($alumniEmail); ?>">
        <button type="submit" class="btn">Update Profile</button>
    </form>
<?php endif; ?>

    </div>
</body>
</html>
