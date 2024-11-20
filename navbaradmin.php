<?php
    include './dbconnect.php';
    include './scrolltotop.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN - PESU ALUMNIVERSE</title>
    <link rel="stylesheet" href="./assets/css/stylesheetalumni.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="topheader"></div>
    <div class="header">
        <div class="logo-admin">
            <a href="adminhome.php"><img src="./assets/images/sistlogo.jpg" alt="logo"  height="75" ></a>                 
            <div class="text-admin"><b> &nbsp&nbsp&nbsp PESU ALUMNIVERSE </b></div>           
        </div>
    </div>
    <div class="side-navbar">
        <br><br><br><br><br><br><br><br><br>
        <ul> <b>
            <li><a href="adminhome.php"><i class="fa fa-home"></i>&nbsp
                HOME &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</a></li>
            <li><a href="adminprofile.php"><i class="fa fa-user">&nbspALUMNIPROFILE&nbsp</i></a></li>
            <li><a href="alumnisearch.php"><i class="fa fa-search">&nbspSEARCH ALUMNIs</i></a></li>
            <li><a href="queries.php"><i class="fa fa-question-circle">&nbspQUERIES &nbsp</i></a></li><br>
            <li><a href="achievements.php"><i class="fa fa-trophy">&nbspACHIEVEMENTS&nbsp</i></a></li>
            <li><a href="postedjobs.php"><i class="fa fa-briefcase">&nbspPOSTED JOBS&nbsp</i></a></li>
            <li><a href="mentorrequests.php"><i class="fa fa-handshake-o">&nbspMENTOR REQUESTS&nbsp</i></a></li>
            <li><a href="updateevents.php"><i class="fa fa-calendar">&nbspUPDATE EVENTS&nbsp</i></a></li>
            <li><a href="announcements.php"><i class="fa fa-bullhorn">&nbspANNOUNCEMENTS&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</i></a></li>
            <li><a href="logout.php"><i class="fa fa-sign-out">&nbspLOGOUT &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</i></a></li>
        </ul></b>
    </div>
</body>
</html>