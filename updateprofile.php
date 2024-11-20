<?php
session_start();
include './navigationbar.php';
require './dbconnect.php';

#ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$email = $_SESSION['email'];

// Fetch the alumni_id from alumni_login table using email
$alumniIdQuery = "SELECT alumni_id FROM alumnilogin WHERE email = ?";
$alumniIdStmt = $con->prepare($alumniIdQuery);
$alumniIdStmt->bind_param('s', $email);
$alumniIdStmt->execute();
$alumniIdResult = $alumniIdStmt->get_result()->fetch_assoc();
$alumniId = $alumniIdResult['alumni_id'];
$alumniIdStmt->close();

// Fetch existing profile data
$basicInfoQuery = "SELECT * FROM alumni_basic_info WHERE alumni_id = ?";
$basicStmt = $con->prepare($basicInfoQuery);
$basicStmt->bind_param('i', $alumniId);
$basicStmt->execute();
$basicResult = $basicStmt->get_result()->fetch_assoc();
$basicStmt->close();

$employmentQuery = "SELECT * FROM alumni_employment WHERE alumni_id = ?";
$employmentStmt = $con->prepare($employmentQuery);
$employmentStmt->bind_param('i', $alumniId);
$employmentStmt->execute();
$employmentResult = $employmentStmt->get_result()->fetch_assoc();
$employmentStmt->close();

$educationQuery = "SELECT * FROM alumni_education WHERE alumni_id = ?";
$educationStmt = $con->prepare($educationQuery);
$educationStmt->bind_param('i', $alumniId);
$educationStmt->execute();
$educationResult = $educationStmt->get_result()->fetch_assoc();
$educationStmt->close();

$socialProfilesQuery = "SELECT * FROM alumni_social_profiles WHERE alumni_id = ?";
$socialProfilesStmt = $con->prepare($socialProfilesQuery);
$socialProfilesStmt->bind_param('i', $alumniId);
$socialProfilesStmt->execute();
$socialProfilesResult = $socialProfilesStmt->get_result()->fetch_assoc();
$socialProfilesStmt->close();

$errorFlag = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gather POST data
    $full_name = $_POST['full_name'];
    $dob = $_POST['dob'];
    $phone_num = $_POST['phone_num'];
    $degree = $_POST['degree'];
    $specialization = $_POST['specialization'];
    $branch = $_POST['branch'];
    $graduation_year = $_POST['graduation_year'];
    $is_working = isset($_POST['is_working']) ? 1 : 0;
    $company_name = $_POST['company_name'];
    $job_title = $_POST['job_title'];
    $is_studying = isset($_POST['is_studying']) ? 1 : 0;
    $college_name = $_POST['college_name'];
    $course_name = $_POST['course_name'];
    $linkedin_url = $_POST['linkedin_url'];
    $github_url = $_POST['github_url'];

    // Insert or Update alumni_basic_info
    if ($basicResult) {
        $updateBasicQuery = "UPDATE alumni_basic_info SET full_name = ?, dob = ?, phone_num = ?, degree = ?, specialization = ?, branch = ?, graduation_year = ? WHERE alumni_id = ?";
        $updateBasicStmt = $con->prepare($updateBasicQuery);
        $updateBasicStmt->bind_param('sssssssi', $full_name, $dob, $phone_num, $degree, $specialization, $branch, $graduation_year, $alumniId);
        if (!$updateBasicStmt->execute()) {
            $errorFlag = true;
            echo "Error updating basic info: " . $updateBasicStmt->error;
        }
        $updateBasicStmt->close();
    } else {
        $insertBasicQuery = "INSERT INTO alumni_basic_info (alumni_id, email, full_name, dob, phone_num, degree, specialization, branch, graduation_year) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insertBasicStmt = $con->prepare($insertBasicQuery);
        $insertBasicStmt->bind_param('isssssssi', $alumniId, $email, $full_name, $dob, $phone_num, $degree, $specialization, $branch, $graduation_year);
        if (!$insertBasicStmt->execute()) {
            $errorFlag = true;
            echo "Error inserting basic info: " . $insertBasicStmt->error;
        }
        $insertBasicStmt->close();
    }

    // Update or Insert employment info
    if ($employmentResult) {
        $updateEmploymentQuery = "UPDATE alumni_employment SET is_working = ?, company_name = ?, job_title = ? WHERE alumni_id = ?";
        $updateEmploymentStmt = $con->prepare($updateEmploymentQuery);
        $updateEmploymentStmt->bind_param('issi', $is_working, $company_name, $job_title, $alumniId);
        if (!$updateEmploymentStmt->execute()) {
            $errorFlag = true;
            echo "Error updating employment info: " . $updateEmploymentStmt->error;
        }
        $updateEmploymentStmt->close();
    } else {
        $insertEmploymentQuery = "INSERT INTO alumni_employment (alumni_id, email, is_working, company_name, job_title) VALUES (?, ?, ?, ?, ?)";
        $insertEmploymentStmt = $con->prepare($insertEmploymentQuery);
        $insertEmploymentStmt->bind_param('issss', $alumniId, $email, $is_working, $company_name, $job_title);
        if (!$insertEmploymentStmt->execute()) {
            $errorFlag = true;
            echo "Error inserting employment info: " . $insertEmploymentStmt->error;
        }
        $insertEmploymentStmt->close();
    }

    // Update or Insert education info
    if ($educationResult) {
        $updateEducationQuery = "UPDATE alumni_education SET is_studying = ?, college_name = ?, course_name = ? WHERE alumni_id = ?";
        $updateEducationStmt = $con->prepare($updateEducationQuery);
        $updateEducationStmt->bind_param('issi', $is_studying, $college_name, $course_name, $alumniId);
        if (!$updateEducationStmt->execute()) {
            $errorFlag = true;
            echo "Error updating education info: " . $updateEducationStmt->error;
        }
        $updateEducationStmt->close();
    } else {
        $insertEducationQuery = "INSERT INTO alumni_education (alumni_id, email, is_studying, college_name, course_name) VALUES (?, ?, ?, ?, ?)";
        $insertEducationStmt = $con->prepare($insertEducationQuery);
        $insertEducationStmt->bind_param('issss', $alumniId, $email, $is_studying, $college_name, $course_name);
        if (!$insertEducationStmt->execute()) {
            $errorFlag = true;
            echo "Error inserting education info: " . $insertEducationStmt->error;
        }
        $insertEducationStmt->close();
    }

    // Update or Insert social profiles info
    if ($socialProfilesResult) {
        $updateSocialProfilesQuery = "UPDATE alumni_social_profiles SET linkedin_url = ?, github_url = ? WHERE alumni_id = ?";
        $updateSocialProfilesStmt = $con->prepare($updateSocialProfilesQuery);
        $updateSocialProfilesStmt->bind_param('ssi', $linkedin_url, $github_url, $alumniId);
        if (!$updateSocialProfilesStmt->execute()) {
            $errorFlag = true;
            echo "Error updating social profiles: " . $updateSocialProfilesStmt->error;
        }
        $updateSocialProfilesStmt->close();
    } else {
        $insertSocialProfilesQuery = "INSERT INTO alumni_social_profiles (alumni_id, email, linkedin_url, github_url) VALUES (?, ?, ?, ?)";
        $insertSocialProfilesStmt = $con->prepare($insertSocialProfilesQuery);
        $insertSocialProfilesStmt->bind_param('isss', $alumniId, $email, $linkedin_url, $github_url);
        if (!$insertSocialProfilesStmt->execute()) {
            $errorFlag = true;
            echo "Error inserting social profiles info: " . $insertSocialProfilesStmt->error;
        }
        $insertSocialProfilesStmt->close();
    }

    // Redirect to profile page with success or error status
    if ($errorFlag) {
        header('Location: alumniprofile.php?status=error');
    } else {
        header('Location: alumniprofile.php?status=success');
    }
    exit();
}

$con->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="./assets/css/stylesheetalumni.css"> <!-- Include your CSS file -->
</head>
<body>
    
    
    <div class="alumnimain">

        <div class="head"><h1><b>UPDATE PROFILE</b></h1></div>
        <div class="message">
            <?php if ($msg != "") { echo $msg; } ?>
        </div>
    
    
    <form method="POST" action="">
        <h2>Basic Information</h2>
        <table class="tablecontainer" id="table" border="black" width="90%" height="60%">
            <tr>
                <td><label for="full_name">Full Name:</label></td>
                <td><input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($basicResult['full_name']); ?>" required></td>
            </tr>
            <tr>
                <td><label for="dob">Date of Birth:</label></td>
                <td><input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($basicResult['dob']); ?>"></td>
            </tr>
            <tr>
                <td><label for="phone_num">Phone Number:</label></td>
                <td><input type="text" id="phone_num" name="phone_num" value="<?php echo htmlspecialchars($basicResult['phone_num']); ?>"></td>
            </tr>
            <tr>
                <td><label for="degree">Degree:</label></td>
                <td><input type="text" id="degree" name="degree" value="<?php echo htmlspecialchars($basicResult['degree']); ?>"></td>
            </tr>
            <tr>
                <td><label for="specialization">Specialization:</label></td>
                <td><input type="text" id="specialization" name="specialization" value="<?php echo htmlspecialchars($basicResult['specialization']); ?>"></td>
            </tr>
            <tr>
            <td><label for="branch">Branch:</label></td>
            <td>
                <select id="branch" name="branch" required>
                <option value="" disabled selected>Select your Branch</option>
                <option value="CSE" <?php echo ($basicResult['branch'] == 'CSE') ? 'selected' : ''; ?>>CSE</option>
                <option value="AIML" <?php echo ($basicResult['branch'] == 'AIML') ? 'selected' : ''; ?>>AIML</option>
                <option value="ECE" <?php echo ($basicResult['branch'] == 'ECE') ? 'selected' : ''; ?>>ECE</option>
                <option value="EEE" <?php echo ($basicResult['branch'] == 'EEE') ? 'selected' : ''; ?>>EEE</option>
                <option value="ME" <?php echo ($basicResult['branch'] == 'ME') ? 'selected' : ''; ?>>ME</option>
                <option value="BT" <?php echo ($basicResult['branch'] == 'BT') ? 'selected' : ''; ?>>BT</option>
                </select>
            </td>
            </tr>
            <tr>
                <td><label for="graduation_year">Graduation Year:</label></td>
                <td>
                    <select id="graduation_year" name="graduation_year" required>
                        <option value="" disabled selected>Select your Graduation Year</option>
                        <?php
                                $currentYear = date("Y"); // Get the current year
                                    for ($year = 1974; $year <= $currentYear; $year++) { // Adding a few years to the future
                                    echo "<option value=\"$year\" " . ($basicResult['graduation_year'] == $year ? 'selected' : '') . ">$year</option>";
                                 }
                        ?>
                    </select>
                </td>
            </tr>

            
        </table>

        <h2>Employment Information</h2>
        <table class="tablecontainer" id="table" border="black" width="90%" height="60%">
            <tr>
                <td><label for="is_working">Currently Working:</label></td>
                <td><input type="checkbox" id="is_working" name="is_working" <?php echo ($employmentResult['is_working'] == 1) ? 'checked' : ''; ?>></td>
            </tr>
            <tr>
                <td><label for="company_name">Company Name:</label></td>
                <td><input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($employmentResult['company_name']); ?>"></td>
            </tr>
            <tr>
                <td><label for="job_title">Job Title:</label></td>
                <td><input type="text" id="job_title" name="job_title" value="<?php echo htmlspecialchars($employmentResult['job_title']); ?>"></td>
            </tr>
        </table>

        <h2>Education Information</h2>
        <table class="tablecontainer" id="table" border="black" width="90%" height="60%">
            <tr>
                <td><label for="is_studying">Currently Studying:</label></td>
                <td><input type="checkbox" id="is_studying" name="is_studying" <?php echo ($educationResult['is_studying'] == 1) ? 'checked' : ''; ?>></td>
            </tr>
            <tr>
                <td><label for="college_name">College Name:</label></td>
                <td><input type="text" id="college_name" name="college_name" value="<?php echo htmlspecialchars($educationResult['college_name']); ?>"></td>
            </tr>
            <tr>
                <td><label for="course_name">Course Name:</label></td>
                <td><input type="text" id="course_name" name="course_name" value="<?php echo htmlspecialchars($educationResult['course_name']); ?>"></td>
            </tr>
        </table>

        <h2>Social Profiles</h2>
        <table class="tablecontainer" id="table" border="black" width="90%" height="60%">
            <tr>
                <td><label for="linkedin_url">LinkedIn URL:</label></td>
                <td><input type="url" id="linkedin_url" name="linkedin_url" value="<?php echo htmlspecialchars($socialProfilesResult['linkedin_url']); ?>"></td>
            </tr>
            <tr>
                <td><label for="github_url">GitHub URL:</label></td>
                <td><input type="url" id="github_url" name="github_url" value="<?php echo htmlspecialchars($socialProfilesResult['github_url']); ?>"></td>
            </tr>
        </table>

        <button type="submit" class="btn">Update Profile</button>
    </form>
    
    </div>
</body>
</html>
