<?php

include 'db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$sql = "
SELECT 
    s.user_id AS student_id,
    s.name AS student_name, 
    s.index_number, 
    s.exam_name, 
    s.year_stream, 
    sub.subject_name, 
    sub.result,
    sub.id AS subject_id
FROM students AS s
LEFT JOIN subjects AS sub ON s.user_id = sub.student_id
ORDER BY s.user_id;
";

$results = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Student Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            background: white;
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 1200px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .student-info {
            margin-bottom: 20px;
            padding: 15px;
            background: #f4f4f9;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .student-info h3 {
            margin: 0;
            color: #2575fc;
        }

        .student-info p {
            margin: 5px 0;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .edit-btn {
            color: #2575fc;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .edit-btn:hover {
            color: #6a11cb;
        }

        .no-results {
            text-align: center;
            font-size: 18px;
            color: #333;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Panel - Manage Student Results</h1>

        <?php if ($results->num_rows > 0): ?>
            <?php 
            $currentStudentId = null; 
            while ($row = $results->fetch_assoc()): 
            ?>
                <?php if ($currentStudentId !== $row['student_id']): ?>
                    <?php 
                    
                    if ($currentStudentId !== null) echo "</table>"; 
                    $currentStudentId = $row['student_id'];
                    ?>

                    <div class="student-info">
                        <h3>Student Name: <?= htmlspecialchars($row['student_name']) ?></h3>
                        <p>Index Number: <?= htmlspecialchars($row['index_number']) ?></p>
                        <p>Exam Name: <?= htmlspecialchars($row['exam_name']) ?></p>
                        <p>Year/Stream: <?= htmlspecialchars($row['year_stream']) ?></p>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Subject Name</th>
                                <th>Result</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                <?php endif; ?>

                <tr>
                    <td><?= htmlspecialchars($row['subject_name'] ?? "No subject data") ?></td>
                    <td><?= htmlspecialchars($row['result'] ?? "No result data") ?></td>
                    <td>
                        <?php if (!empty($row['subject_id'])): ?>
                            <a href="edit_result.php?subject_id=<?= $row['subject_id'] ?>" class="edit-btn">Edit</a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                </tr>

            <?php endwhile; ?>
            </tbody>
            </table> <!-- Close the last student's table -->
        <?php else: ?>
            <p class="no-results">No results found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
