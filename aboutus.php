<?php
    include './navigationbar.php'; 
    session_start();
    if(!(isset($_SESSION['logged_in']))){
        header("Location: alumnilogin.php");  
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
        <div class="head">
           <b> ABOUT US </b>
        </div>
        <div class="subheading">
            <b>VISION</b>
        </div>
        <div class="content">
            Develop a fully connected, strong PESU alumni community.<br>
        </div>
        <div class="subheading">
            <b>MISSION</b>
        </div>
        <div class="content">
            Promote alumni relationships, develop student engagement, and allow student-alumni interactions to benefit PES UNIVERSITY.
            <b>LETTER FROM ALUMNI COUNCIL</b>
            <b>Alumni Chapters</b>
        </div>
        <div class="content">
            <p>
            Dear Alumni,<br><br>
            Greetings from your Alma mater!
            <br><br>
                    We will be opening chapters in different geographies to provide an accessible, fun, and consistent
                point of contact for alumni. These will bring alumni together to provide quality local programs
                and events which appeals to a wide variety of alumni. These chapters will also act as a source of
                information about the University, they provide a local contact for alumni, parents, and current and
                future students.
            <br><br>
            Director <br>
            Alumni Council <br>
            PES UNIVERSITY <br>
            BENGALURU
            </p>
        </div>
</div>
</body>
</html>