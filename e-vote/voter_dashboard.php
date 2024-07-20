<?php
session_start();

if (!isset($_SESSION['registered'])) {
    header("Location: voter_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Dashboard</title>
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
        .button-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
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
    <div class="button-container">
        <h2>Registration Successful!</h2>
        <a class="button" href="candidate_registration.php">Candidate Form</a>
        <a class="button" href="voting.php">Voting</a>
        <a class="button" href="vote_count.php">Votes</a>
        <a class="button" href="results.php">Results</a>
    </div>
</body>
</html>
