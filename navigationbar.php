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
    <title>PESU ALUMNIVERSE</title>
    <link rel="stylesheet" type="text/css" href="./assets/css/stylesheetalumni.css" />
</head>
<body>
    <div class="topheader"></div>
    <div class="header">
        <div class="logo">
            <a href="alumnihome.php"><img src="./assets/images/sistlogo.jpg" alt="logo"  height="75" ></a>                 
            <div class="text"><b> &nbsp&nbsp&nbsp PESU ALUMNIVERSE</b></div>          
        </div>
    </div>
    <div class="nav-bar">
        <div class="list-items">
            <ul><b>
                <li><a href="alumnihome.php"><i class="fa fa-home"></i>&nbspHOME</a></li>
                <li><a href="aboutus.php"><i class="fa fa-info-circle"></i>&nbspABOUT US</a></li>
                <li><a href="gallery.php"><i class="fa fa-picture-o"></i>&nbspGALLERY</a></li>
                <li><a href="events.php"><i class="fa fa-calendar"></i>&nbspEVENTS</a></li>
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropbtn"><i class="fa fa-handshake-o"></i>&nbspALUMNI ASSIST&nbsp&nbsp&nbsp&nbsp&nbsp</a></b>
                    <div class="dropdown-content">
                        <a href="sendquery.php"><i class="fa fa-question-circle"></i>&nbspSend Query</a>
                        <a href="postajob.php"><i class="fa fa-briefcase"></i>&nbspPost a Job</a>
                        <a href="shareachievements.php"><i class="fa fa-trophy"></i>&nbspShare Achievements</a>
                        <a href="beamentor.php"><i class="fa fa-handshake-o"></i>&nbspBe a Mentor</a>
                    </div>
                </li>
                <li><a href="alumniprofile.php"><b><i class="fa fa-user"></i>&nbspMY PROFILE<b></a></li>
                <b>

                <li><a href="logoutalumni.php"><i class="fa fa-sign-out"></i>&nbspLOG OUT</a></li></b>
            </ul>
        </div>
    </div>
</body>
</html>