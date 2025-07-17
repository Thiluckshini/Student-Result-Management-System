<?php
// Include database connection
include 'db.php';
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Get the subject ID from the GET parameter
if (!isset($_GET['subject_id'])) {
    header("Location: admin_results.php");
    exit();
}

$subject_id = intval($_GET['subject_id']);

// Fetch the subject details
$sql = "SELECT * FROM subjects WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $subject_id);
$stmt->execute();
$result = $stmt->get_result();
$subject = $result->fetch_assoc();

if (!$subject) {
    echo "Subject not found.";
    exit();
}

// Update the subject result
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_subject_name = $_POST['subject_name'];
    $new_result = $_POST['result'];

    $update_sql = "UPDATE subjects SET subject_name = ?, result = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssi", $new_subject_name, $new_result, $subject_id);

    if ($update_stmt->execute()) {
        header("Location:admin_result.php"); // If it's in the 'result' folder

        exit();
    } else {
        echo "Failed to update result: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        .container h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .container label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
            color: #333;
        }

        .container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .container button {
            width: 100%;
            padding: 12px;
            background-color: #2575fc;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .container button:hover {
            background-color: #6a11cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Result</h1>

        <form method="post" action="">
            <!-- Hidden input to pass the subject ID -->
            <input type="hidden" name="subject_id" value="<?= htmlspecialchars($subject_id) ?>">

            <label for="subject_name">Subject Name:</label>
            <input type="text" name="subject_name" id="subject_name" 
                   value="<?= htmlspecialchars($subject['subject_name']) ?>" required>

            <label for="result">Result:</label>
            <input type="text" name="result" id="result" 
                   value="<?= htmlspecialchars($subject['result']) ?>" required>

            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>
