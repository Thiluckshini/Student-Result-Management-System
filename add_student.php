<?php

session_start();
include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'db.php'; 

    $name = $_POST['name'];
    $index_number = $_POST['index_number'];
    $exam_name = $_POST['exam_name'];
    $year_stream = $_POST['year_stream'];
    $subjects = $_POST['subjects'] ?? []; 

  
    if (!is_array($subjects)) {
        echo "Subjects data is invalid!";
        exit;
    }

    
    $username = strtolower(str_replace(' ', '_', $name));
    $password = "password123";

    $conn->begin_transaction(); 

    try {
        
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'student')");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $user_id = $stmt->insert_id;
        $stmt->close();

        
        $stmt = $conn->prepare("INSERT INTO students (user_id, name, index_number, exam_name, year_stream) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $name, $index_number, $exam_name, $year_stream);
        $stmt->execute();
        $stmt->close();

        
        $stmt = $conn->prepare("INSERT INTO subjects (student_id, subject_name, result) VALUES (?, ?, ?)");
        foreach ($subjects as $subject) {
            if (!isset($subject['subject_name']) || !isset($subject['result'])) {
                throw new Exception("Invalid subject data format.");
            }
            $subject_name = $subject['subject_name'];
            $result = $subject['result'];
            $stmt->bind_param("iss", $user_id, $subject_name, $result);
            $stmt->execute();
        }
        $stmt->close();

        $conn->commit(); 
        echo "<script> alert('student added successfully')</script>";
    } catch (Exception $e) {
        $conn->rollback(); 
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 800px;
            box-sizing: border-box;
        }

        header h1 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="text"]:focus {
            border-color: #2575fc;
            outline: none;
        }

        .subject-inputs {
            grid-column: span 2;
        }

        .subject-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        button {
            padding: 12px;
            background-color: #2575fc;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #6a11cb;
        }

        button{
            background-color: #ccc;
            width:200px;
        }
        .b1{
            background-color: #2575fc;

        }
        

        button[type="button"]:hover {
            background-color: #aaa;
        }

        .actions {
            grid-column: span 2;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        a {
            color: #2575fc;
            text-decoration: none;
            font-size: 14px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Add Student</h1>
        </header>
        <form method="POST" action="">
            <div>
                <label>Index Number:</label>
                <input type="text" name="index_number" required>
            </div>

            <div>
                <label>Exam Name:</label>
                <input type="text" name="exam_name" required>
            </div>

            <div>
                <label>Year/Stream:</label>
                <input type="text" name="year_stream" required>
            </div>

            <div>
                <label>Student Name:</label>
                <input type="text" name="name" required>
            </div>

            <div class="subject-inputs">
                <label>Subjects and Results:</label>
                <div class="subject-grid">
                    <input type="text" name="subjects[0][subject_name]" placeholder="Subject 1 Name" required>
                    <input type="text" name="subjects[0][result]" placeholder="Result" required>

                    <input type="text" name="subjects[1][subject_name]" placeholder="Subject 2 Name" required>
                    <input type="text" name="subjects[1][result]" placeholder="Result" required>

                    <input type="text" name="subjects[2][subject_name]" placeholder="Subject 3 Name" required>
                    <input type="text" name="subjects[2][result]" placeholder="Result" required>

                    <input type="text" name="subjects[3][subject_name]" placeholder="Subject 4 Name" required>
                    <input type="text" name="subjects[3][result]" placeholder="Result" required>

                    <input type="text" name="subjects[4][subject_name]" placeholder="Subject 5 Name" required>
                    <input type="text" name="subjects[4][result]" placeholder="Result" required>
                </div>
            </div>

            <div class="actions">
                <button type="submit" class="b1">Add Student</button>
                <button type="button" onclick="location.href='admin_dashboard.php'" >Back</button>
            </div>
        </form>
    </div>
  
</body>
</html>
