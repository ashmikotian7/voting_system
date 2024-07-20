<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'voting_system');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for registration feedback
$message = '';
$error = '';

// Process candidate registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];

    // Sanitize inputs to prevent SQL injection
    $name = mysqli_real_escape_string($conn, $name);

    // Check if the candidate already exists
    $check_query = "SELECT * FROM candidates WHERE name = '$name'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        // Candidate already exists
        $error = "Candidate with this name already exists!";
    } else {
        // Insert new candidate into database
        $insert_query = "INSERT INTO candidates (name) VALUES ('$name')";
        if ($conn->query($insert_query) === TRUE) {
            // Registration successful
            $message = "Candidate registration successful!";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, red, orange, yellow, green, blue, indigo, violet);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        .registration-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 16px;
        }
        .form-group input[type="submit"] {
            background-color: purple;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .form-group input[type="submit"]:hover {
            background-color: darkpurple;
        }
        .message {
            color: #ff0000;
            margin-bottom: 10px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            color: #fff;
            background-color: purple;
            text-decoration: none;
            border-radius: 3px;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: darkpurple;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <h2>Candidate Registration</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Register">
            </div>
        </form>
        <?php if (!empty($message)) { ?>
            <div class="message"><?php echo $message; ?></div>
        <?php } ?>
        <?php if (!empty($error)) { ?>
            <div class="message"><?php echo $error; ?></div>
        <?php } ?>
        <a class="button" href="voting.php">Voting</a>
        <a class="button" href="vote_count.php">Votes</a>
        <a class="button" href
