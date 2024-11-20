<?php
  session_start();
  include './navbarindex.php'; 
  $message = $password = "";
  ini_set('display_errors', 1);

  // Process the login when the form is submitted
  if(count($_POST) > 0){
    // Escape inputs to prevent SQL injection
    $result= mysqli_query($con, "SELECT * FROM adminlogin WHERE username='".$_POST["username"]."' and password='".$_POST["password"]."'");
    if(!$result){
      echo mysqli_error($con);
      exit;
    };
    $row= mysqli_fetch_array($result);
    // Check if a row is found for the username
    if(is_array($row)){
      $_SESSION["id"]=$row["id"];
      $_SESSION["username"]= $row["username"];
      $_SESSION["password"]=$row["password"];
    }
    else{
      $message= "Invalid Username or Password";
    }
  }
  if(isset($_SESSION["id"])){
    header("Location:adminhome.php");
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./assets/css/stylehome.css" />
  <title>ADMIN LOGIN | PESU ALUMNIVERSE</title>
</head>
<body>
  <div class="main">
    <div class="bg-img">
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" name="adminlogin" method="post" class="container">
        <center><h2><u>ADMIN LOGIN</u></h2><br><br></center>
        <div class="message">
          <?php 
            if($message != ""){
              echo "<p style='color:red;'>$message</p>";
            }
          ?>
        </div>
        <label for="username">User Name</label><br>
        <input type="text" name="username" placeholder="Enter your username" autocomplete="off" required><br><br>
        <label for="pwd">Password</label><br>
        <input type="password" name="password" placeholder="Enter your password" autocomplete="off" required><br><br>
        <button type="submit" class="btn">Login</button>
      </form>
    </div>
  </div>
</body>
</html>
