<?php
// Include the navbar and database connection
include './navbaradmin.php';
include './dbconnect.php';
session_start();
ini_set('display_errors', 1);
// Check if admin is logged in
if (!(isset($_SESSION['logged_in']))) {
    header("Location: adminlogin.php");
    exit();
}

// Initialize variables for search
$searchType = "";
$searchValue = "";
$results = [];
$allAlumniResults = [];

// Handle form submission for search
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['viewAllAlumni'])) {
        // Query to fetch all alumni ordered by alumni_id
        $query = "SELECT 
                    abi.*, ae.*, aemp.*, asp.* 
                  FROM 
                    alumni_basic_info abi
                  LEFT JOIN 
                    alumni_education ae ON abi.alumni_id = ae.alumni_id
                  LEFT JOIN 
                    alumni_employment aemp ON abi.alumni_id = aemp.alumni_id
                  LEFT JOIN 
                    alumni_social_profiles asp ON abi.alumni_id = asp.alumni_id
                  ORDER BY abi.alumni_id";  // Added ORDER BY alumni_id
        
        // Prepare and execute the query for all alumni
        $stmt = $con->prepare($query);
        $stmt->execute();
        $allAlumniResults = $stmt->get_result();
    } else if (isset($_POST['searchValue'])) {
        // Build SQL query based on search type
        $searchType = $_POST['searchType'];
        $searchValue = $_POST['searchValue'];

        $query = "SELECT 
                    abi.*, ae.*, aemp.*, asp.* 
                  FROM 
                    alumni_basic_info abi
                  LEFT JOIN 
                    alumni_education ae ON abi.alumni_id = ae.alumni_id
                  LEFT JOIN 
                    alumni_employment aemp ON abi.alumni_id = aemp.alumni_id
                  LEFT JOIN 
                    alumni_social_profiles asp ON abi.alumni_id = asp.alumni_id";
        
        // Append WHERE clause based on search criteria
        switch ($searchType) {
            case "alumni_id":
                $query .= " WHERE abi.alumni_id = ?";
                break;
            case "branch":
                $query .= " WHERE abi.branch LIKE ?";
                $searchValue = "%" . $searchValue . "%"; // For partial match
                break;
            case "graduation_year":
                $query .= " WHERE abi.graduation_year = ?";
                break;
            default:
                echo "Invalid search type selected.";
                exit;
        }

        // Add ORDER BY to the search query
        $query .= " ORDER BY abi.alumni_id"; // Added ORDER BY alumni_id
        
        // Prepare and execute the query for search
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $searchValue);
        $stmt->execute();
        $results = $stmt->get_result();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Alumni</title>
    <link rel="stylesheet" type="text/css" href="./assets/css/stylesheetalumni.css" />
</head>
<body>
    <div class="adminmain">
        <div class="head"><h1>Search Alumni</h1></div>
        <div class="form-container">
            <form method="POST">
                <label for="searchType">Search by:</label>
                <select name="searchType" id="searchType" required>
                    <option value="alumni_id">Alumni ID</option>
                    <option value="branch">Branch</option>
                    <option value="graduation_year">Graduation Year</option>
                </select>
                <input type="text" name="searchValue" placeholder="Enter search value" required>
                <button type="submit" class="btn">VIEW</button>
            </form>

        </div>

        <?php if ($results && $results->num_rows > 0): ?>
            <div class="results-container">
                <h2>Search Results</h2>
                <table border="1">
                    <tr>
                        <th>Alumni ID</th>
                        <th>Email</th>
                        <th>Full Name</th>
                        <th>Date of Birth</th>
                        <th>Phone Number</th>
                        <th>Degree</th>
                        <th>Specialization</th>
                        <th>Branch</th>
                        <th>Graduation Year</th>
                        <th>College Name</th>
                        <th>Course Name</th>
                        <th>Company Name</th>
                        <th>Job Title</th>
                        <th>Working Status</th>
                        <th>Studying Status</th>
                    </tr>
                    <?php while ($row = $results->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['alumni_id']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['full_name']; ?></td>
                            <td><?php echo $row['dob']; ?></td>
                            <td><?php echo $row['phone_num']; ?></td>
                            <td><?php echo $row['degree']; ?></td>
                            <td><?php echo $row['specialization']; ?></td>
                            <td><?php echo $row['branch']; ?></td>
                            <td><?php echo $row['graduation_year']; ?></td>

                            <?php if ($row['is_working'] == 0): ?>
                                <td><?php echo $row['college_name']; ?></td>
                                <td><?php echo $row['course_name']; ?></td>
                            <?php else: ?>
                                <td colspan="2">Not Displayed (Working)</td>
                            <?php endif; ?>

                            <?php if ($row['is_studying'] == 0): ?>
                                <td><?php echo $row['company_name']; ?></td>
                                <td><?php echo $row['job_title']; ?></td>
                            <?php else: ?>
                                <td colspan="2">Not Displayed (Studying)</td>
                            <?php endif; ?>

                            <td><?php echo $row['is_working'] == 1 ? "Working" : "Not Working"; ?></td>
                            <td><?php echo $row['is_studying'] == 1 ? "Yes" : "No"; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['viewAllAlumni']) && $results->num_rows == 0): ?>
            <p>No results found.</p>
        <?php endif; ?>
          <!-- Button to display all alumni -->
        <div class="form-container">
            <form method="POST">
                <button type="submit" name="viewAllAlumni" class="btn">View All Alumni</button>
            </form>
        </div>
        <?php if ($allAlumniResults && $allAlumniResults->num_rows > 0): ?>
            <div class="results-container">
                <h2>All Registered Alumni</h2>
                <table border="1">
                    <tr>
                        <th>Alumni ID</th>
                        <th>Email</th>
                        <th>Full Name</th>
                        <th>Date of Birth</th>
                        <th>Phone Number</th>
                        <th>Degree</th>
                        <th>Specialization</th>
                        <th>Branch</th>
                        <th>Graduation Year</th>
                        <th>College Name</th>
                        <th>Course Name</th>
                        <th>Company Name</th>
                        <th>Job Title</th>
                        <th>Working Status</th>
                        <th>Studying Status</th>
                    </tr>
                    <?php while ($row = $allAlumniResults->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['alumni_id']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['full_name']; ?></td>
                            <td><?php echo $row['dob']; ?></td>
                            <td><?php echo $row['phone_num']; ?></td>
                            <td><?php echo $row['degree']; ?></td>
                            <td><?php echo $row['specialization']; ?></td>
                            <td><?php echo $row['branch']; ?></td>
                            <td><?php echo $row['graduation_year']; ?></td>

                            <?php if ($row['is_working'] == 0): ?>
                                <td><?php echo $row['college_name']; ?></td>
                                <td><?php echo $row['course_name']; ?></td>
                            <?php else: ?>
                                <td colspan="2"><b>Working</b></td>
                            <?php endif; ?>

                            <?php if ($row['is_studying'] == 0): ?>
                                <td><?php echo $row['company_name']; ?></td>
                                <td><?php echo $row['job_title']; ?></td>
                            <?php else: ?>
                                <td colspan="2"><b>Studying</b></td>
                            <?php endif; ?>

                            <td><?php echo $row['is_working'] == 1 ? "Working" : "Not Working"; ?></td>
                            <td><?php echo $row['is_studying'] == 1 ? "Yes" : "No"; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
