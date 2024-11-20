<?php
    include './navigationbar.php';
    session_start();
    if(!(isset($_SESSION['logged_in']))){
        header("Location: alumnilogin.php");  
    }
    $_SESSION["logged_in"] = [
        "alumni_id" => $row["alumni_id"],
        "email" => $row["email"],
        "password" => $row["password"],
      ];
    //var_dump($_SESSION['alumni_id']);
    //var_dump($_SESSION['email']);
    $alumniEmail = $_SESSION['email'];
    $name=$email=$query=$msg="";
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $name=test_input($_POST["name"]);
        #$email=test_input($_POST["email"]);
        $query=test_input($_POST["query"]);
        $result = mysqli_query($con, "SELECT MAX(id) AS max_id FROM queries");
        $row = mysqli_fetch_assoc($result);
        $next_id = $row['max_id'] + 1;
        if(isset($_POST["submit"])){
            $sql="INSERT INTO queries (id,name,email,query) values ('$next_id','$name','$alumniEmail','$query')";
            if(mysqli_query($con,$sql)){
                $msg="Thank you for the response, We will get back to you.";
                // header("Location:successfullmsg.php");
            }
            else{
                echo "Error";
            }
        }
    }
    function test_input($data){
        $data=trim($data);
        $data=stripslashes($data);
        $data=htmlspecialchars($data);
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
</head>
<body>
    <div class="alumnimain">
        <div class="head"><b>ALUMNI ASSIST - SEND QUERY</b></div>
        <div class="content">
            For getting academic transcripts, campus visit request and any other queries contact us by filling the form.
        </div>
        <div class="message">
            <?php
                if($msg!=""){
                    echo $msg;
                }
            ?>
        </div>
        <div class="formcontainer">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" name="mentorform" method="post">
                <div class="input-box">
                    <span class="details">Name</span>
                    <input type="text" placeholder="Enter name" name="name" autocomplete="off" required>
                </div>
                <div class="input-box">
                    <span class="details">Email</span>
                    <input type="email" placeholder="<?php echo htmlspecialchars($alumniEmail); ?>" name="email" autocomplete="off">
                </div>
                <div class="input-box">
                    <span class="details">Type Query</span>
                <textarea name="query" id="query" cols="30" rows="10" placeholder="Type your Query here" autocomplete="off" required></textarea>
        </div>
        <button type="submit" value="submit" name="submit" class="btn">Submit</button>
    </form>
</div>

    </div>
</body>
</html>
<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>