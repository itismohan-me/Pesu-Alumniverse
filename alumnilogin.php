<?php
// Start the session at the beginning of the script
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Include the necessary files
include './navbarindex.php'; // Include the navigation bar
include './dbconnect.php'; // Include the database connection

$message = ""; // Initialize message variable
$email_id = ""; // Initialize email_id variable

if (isset($_POST['login'])) {
    // Retrieve email and password from POST data
    $email_id = test_input($_POST["email"]); // Store the email in $email_id
    $pwd = test_input($_POST["password"]); // Get the password
    $pwd = md5($pwd); // Hash the password

    // Prepare and execute the SQL query
    $result = mysqli_query($con, "SELECT * FROM alumnilogin WHERE email='$email_id'");
    if (!$result) {
        echo mysqli_error($con);
        exit;
    }

    $row = mysqli_fetch_array($result);

    // Check if the retrieved row exists and the password matches
    if (is_array($row) && ($pwd === $row["password"])) {
        $_SESSION["alumni_id"] = $row["alumni_id"]; // Assuming alumni_id is the ID
        $_SESSION["email"] = $row["email"];
        $_SESSION["logged_in"] = true; // Track logged-in status

        // Redirect to alumni home
        header("Location:alumnihome.php");
        exit; // Exit after redirecting to avoid further script execution
    } else {
        $message = "Invalid Email or Password";
    }
}

// Function to sanitize input
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
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="./assets/css/stylehome.css" />
    <title>ALUMNI LOGIN</title>
</head>
<body>
    <div class="main">
        <div class="bg-img">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" name="alumnilogin" method="post" class="container">
                <center><h2><u>ALUMNI LOGIN</u></h2><br><br></center>
                <div class="message">
                    <?php 
                    if ($message != "") {
                        echo $message;
                    }
                    ?>
                </div>
                <label for="email">Email</label><br>
                <input type="email" name="email" placeholder="Enter your email" autocomplete="off" required><br><br>
                <label>Password</label><br>
                <input type="password" name="password" placeholder="Enter your password" autocomplete="off" required><br><br>
                <button type="submit" name="login" class="btn">Login</button><br><br>
                <p>Don't have an account?&nbsp;&nbsp;&nbsp;
                    <b><a href="signup.php" class="link">Sign Up Now</a></b>
                </p>
            </form>
        </div>
    </div>
</body>
</html>
