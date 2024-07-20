<?php
session_start();

// Check if user is already logged in, redirect to dashboard if true
if (isset($_SESSION['user_id'])) {
    header("Location: voter_dashboard.php");
    exit;
}

// Initialize variables for registration feedback
$message = '';
$error = '';

// Process registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'voting_system');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve user input
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize inputs to prevent SQL injection
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Check if the email is already registered
    $check_query = "SELECT * FROM voters WHERE email = '$email'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        $error = "Email already registered. Please use a different email.";
    } else {
        // Insert new voter into database
        $insert_query = "INSERT INTO voters (name, email, password) VALUES ('$name', '$email', '$password')";
        if ($conn->query($insert_query) === TRUE) {
            // Registration successful
            $message = "Registration successful!";
            $_SESSION['registered'] = true;
            $_SESSION['user_id'] = $conn->insert_id;
        } else {
            $error = "Error: " . $conn->error;
        }
    }

    // Close database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Registration</title>
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
        .button-container {
            display: none;
            margin-top: 20px;
        }
        .button {
            background-color: purple;
            color: #fff;
            padding: 10px 20px;
            margin: 10px 0;
            text-decoration: none;
            border-radius: 3px;
            display: block;
        }
        .button:hover {
            background-color: darkpurple;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <h2>Voter Registration</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
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
        <div class="button-container" id="button-container">
            <a class="button" href="candidate_registration.php">Candidate Form</a>
            <a class="button" href="voting.php">Voting</a>
            <a class="button" href="vote_count.php">Votes</a>
            <a class="button" href="results.php">Results</a>
        </div>
    </div>
    <script>
        // Show buttons if registration is successful
        <?php if (!empty($message)) { ?>
            document.getElementById('button-container').style.display = 'block';
        <?php } ?>
    </script>
</body>
</html>
