<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'voting_system');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the winner
$winner_query = "SELECT name, votes FROM candidates ORDER BY votes DESC LIMIT 1";
$winner_result = $conn->query($winner_query);
$winner = $winner_result->fetch_assoc();

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting Results</title>
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
        }
        .results-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        .result {
            margin-bottom: 10px;
        }
        .button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="results-container">
        <h2>Voting Results</h2>
        <?php if ($winner) { ?>
            <div class="result">
                <strong>Winner:</strong> <?php echo htmlspecialchars($winner['name']); ?>
            </div>
            <div class="result">
                <strong>Votes:</strong> <?php echo htmlspecialchars($winner['votes']); ?>
            </div>
        <?php } else { ?>
            <p>No results yet.</p>
        <?php } ?>
        <br>
        <a href="voter_registration.php" class="button">Go Back</a>
    </div>
</body>
</html>
