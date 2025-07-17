<?php
session_start();
include 'functions.php';

if (!isLoggedIn() || !in_array($_SESSION['role'], ['student', 'admin'])) {
    header("Location: login.php");
    exit();
}

// Get the logged-in student's ID
$user_id = $_SESSION['user_id'];

// Fetch student details
$stmt = $conn->prepare("SELECT name, index_number, exam_name, year_stream FROM students WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($student = $result->fetch_assoc()) {
    $name = $student['name'];
    $index_number = $student['index_number'];
    $exam_name = $student['exam_name'];
    $year_stream = $student['year_stream'];
} else {
    echo "Student details not found!";
    exit();
}

// Fetch student results
$stmt = $conn->prepare("SELECT subject_name, result FROM subjects WHERE student_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$results = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 600px;
            text-align: center;
        }

        h1, h2 {
            color: #333;
        }

        p {
            font-size: 16px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #2575fc;
            color: white;
            text-align: center;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        footer {
            margin-top: 20px;
        }

        footer a {
            text-decoration: none;
            color: white;
            background: #2575fc;
            padding: 10px 20px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        footer a:hover {
            background: #6a11cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Welcome, <?php echo htmlspecialchars($name); ?></h1>
        </header>
        <section>
            <h2>Student Details</h2>
            <p><strong>Index Number:</strong> <?php echo htmlspecialchars($index_number); ?></p>
            <p><strong>Exam Name:</strong> <?php echo htmlspecialchars($exam_name); ?></p>
            <p><strong>Year/Stream:</strong> <?php echo htmlspecialchars($year_stream); ?></p>
        </section>
        <section>
            <h2>Results</h2>
            <table>
                <tr>
                    <th>Subject</th>
                    <th>Result</th>
                </tr>
                <?php while ($row = $results->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['result']); ?></td>
                    </tr>
                <?php } ?>
            </table>
        </section>
        <footer>
            <a href="logout.php">Logout</a>
        </footer>
    </div>
</body>
</html>
