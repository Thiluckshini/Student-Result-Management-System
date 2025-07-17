<?php


session_start();
include 'functions.php';




if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    echo "Debug Info:<br>";
    echo "isLoggedIn(): " . (isLoggedIn() ? "true" : "false") . "<br>";
    echo "Session role: " . (isset($_SESSION['role']) ? $_SESSION['role'] : "Not set") . "<br>";
    echo "Session user_id: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "Not set") . "<br>";
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        .container h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 28px;
        }

        .container button {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
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

        .container button:last-child {
            background-color: #ff4d4d;
        }

        .container button:last-child:hover {
            background-color: #ff1a1a;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <button onclick="location.href='add_student.php'">Add Student</button>
        <button onclick="location.href='admin_result.php'">View Results</button>
        <button onclick="location.href='login.php'">Logout</button>
    </div>
</body>
</html>
