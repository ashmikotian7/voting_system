<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'voting_system');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve vote counts
$vote_counts = [];
$vote_query = "SELECT name, votes FROM candidates ORDER BY votes DESC";
$vote_result = $conn->query($vote_query);
if ($vote_result->num_rows > 0) {
    while ($row = $vote_result->fetch_assoc()) {
        $vote_counts[] = $row;
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
    <title>Vote Count</title>
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
        .vote-count-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .vote-count {
            margin-bottom: 20px;
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
    <div class="vote-count-container">
        <h2>Vote Count</h2>
        <?php foreach ($vote_counts as $vote_count) { ?>
            <div class="vote-count">
                <?php echo $vote_count['name'] . ": " . $vote_count['votes'] . " votes"; ?>
            </div>
        <?php } ?>
        <a class="button" href="results.php">Results</a>
        <a class="button" href="voter_registration.php">Go Back to Entry Page</a>
    </div>
</body>
</html>
