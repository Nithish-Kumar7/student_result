<?php
session_start(); // Start the session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "students_result"; // your database name
$port = "3307"; //default 3306

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$rollNumber = $dob = "";
$results = null;
$error = "";
$captchaNumber = "";

// Generate CAPTCHA only when the form is not submitted
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $captchaNumber = rand(1000, 9999);
    $_SESSION['captcha'] = $captchaNumber;
}

// Handle form submission for login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $rollNumber = $_POST["roll_number"];
    $dob = $_POST["dob"];
    $captcha = $_POST["captcha"];

    // Simple CAPTCHA validation
    if ($captcha != $_SESSION['captcha']) {
        $error = "Invalid CAPTCHA. Please try again.";
        // Generate a new CAPTCHA if the user fails
        $captchaNumber = rand(1000, 9999);
        $_SESSION['captcha'] = $captchaNumber;
    } else {
        // Check if roll number and date of birth are valid
        $sql = "SELECT * FROM students WHERE roll_number = ? AND date_of_birth = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $rollNumber, $dob);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Redirect to the same page to display results
            header("Location: " . $_SERVER['PHP_SELF'] . "?roll_number=$rollNumber&dob=$dob");
            exit();
        } else {
            $error = "Invalid roll number or date of birth.";
        }

        $stmt->close();
    }
}

// Handle result fetching
if (isset($_GET['roll_number']) && isset($_GET['dob'])) {
    $rollNumber = $_GET['roll_number'];

    // Fetch semester results for the entered roll number
    $sql = "SELECT semester, subject, marks FROM results WHERE roll_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $rollNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    // Store results in array if found
    if ($result->num_rows > 0) {
        $results = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $error = "No results found for the entered roll number.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students Results</title>
    <style>
        body {    
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f0f2f5;
        padding: 40px 20px;
    }

    h1 {
        text-align: center;
        color: #343a40;
        margin-bottom: 20px;
    }

    form {
        margin: 0 auto;
        max-width: 400px;
        padding: 30px;
        border: 1px solid #dee2e6;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #495057;
    }

    input[type="text"],
    input[type="date"],
    input[type="number"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 6px;
        border: 1px solid #ced4da;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    input[type="text"]:focus,
    input[type="date"]:focus,
    input[type="number"]:focus {
        border-color: #28a745; 
        outline: none;
    }

    button {
        width: 100%;
        padding: 12px;
        background-color: #28a745;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #218838; 
    }

    .error {
        color: #dc3545;
        text-align: center;
        margin-top: 20px;
    }

    .captcha {
        font-size: 18px;
        font-weight: bold;
        text-align: center;
        margin: 10px 0;
        padding: 10px;
        border: 1px dashed #6c757d;
        border-radius: 6px;
        background-color: #e9ecef;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    table, th, td {
        border: 1px solid #dee2e6;
    }

    th, td {
        padding: 12px;
        text-align: left;
        font-size: 14px;
    }

    th {
        background-color: #343a40;
        color: #ffffff;
    }

    td {
        background-color: #ffffff;
    }

    tr:nth-child(even) td {
        background-color: #f8f9fa;
    }

    @media (max-width: 600px) {
        form {
            padding: 20px;
            width: 90%;
        }

        h1 {
            font-size: 24px;
        }
    }

    </style>
</head>
<body>

<h1>Student Login</h1>


<form action="" method="post">
    <label for="roll_number">Enter Roll Number:</label>
    <input type="text" id="roll_number" name="roll_number" required>
    
    <label for="dob">Enter Date of Birth:</label>
    <input type="date" id="dob" name="dob" required>
    
    <div class="captcha">CAPTCHA: <?php echo $captchaNumber; ?></div>
    <label for="captcha">Enter CAPTCHA:</label>
    <input type="number" id="captcha" name="captcha" required>
    
    <button type="submit" name="login">Login</button>
</form>

<?php

if (!empty($error)) {
    echo "<div class='error'>$error</div>";
}


if ($results && count($results) > 0) {
    echo "<h2>Semester Results for Roll Number " . htmlspecialchars($rollNumber) . "</h2>";
    echo "<h4>Welcome $rollNumber...!</h4>";
    echo "<table>
            <tr>
                <th>Semester</th>
                <th>Subject</th>
                <th>Marks</th>
            </tr>";

    foreach ($results as $row) {
        echo "<tr>
                <td>{$row['semester']}</td>
                <td>{$row['subject']}</td>
                <td>{$row['marks']}</td>
              </tr>";
    }

    echo "</table>";
}
?>

</body>
</html>