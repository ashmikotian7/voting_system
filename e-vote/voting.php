<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'voting_system');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for voting feedback
$message = '';
$error = '';

// Retrieve candidate list
$candidates = [];
$candidate_query = "SELECT * FROM candidates";
$candidate_result = $conn->query($candidate_query);
if ($candidate_result->num_rows > 0) {
    while ($row = $candidate_result->fetch_assoc()) {
        $candidates[] = $row;
    }
}

// Check if the user has already voted
$has_voted_query = "SELECT * FROM votes WHERE voter_id = '" . $_SESSION['user_id'] . "'";
$has_voted_result = $conn->query($has_voted_query);
$has_voted = $has_voted_result->num_rows > 0;

// Process voting form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && !$has_voted) {
    $candidate_id = $_POST['candidate'];

    // Insert vote into database
    $insert_query = "INSERT INTO votes (voter_id, candidate_id) VALUES ('" . $_SESSION['user_id'] . "', '$candidate_id')";
    if ($conn->query($insert_query) === TRUE) {
        // Increment candidate vote count
        $update_query = "UPDATE candidates SET votes = votes + 1 WHERE id = '$candidate_id'";
        if ($conn->query($update_query) === TRUE) {
            $message = "You voted successfully!";
        } else {
            $error = "Error updating vote count: " . $conn->error;
        }
    } else {
        $error = "Error: " . $conn->error;
    }
} elseif ($has_voted) {
    $message = "You have already voted!";
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting</title>
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
        .voting-container {
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
        .form-group select {
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
    <div class="voting-container">
        <h2>Vote for a Candidate</h2>
        <?php if (!$has_voted) { ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="candidate">Select Candidate:</label>
                <select id="candidate" name="candidate" required>
                    <?php foreach ($candidates as $candidate) { ?>
                        <option value="<?php echo $candidate['id']; ?>"><?php echo $candidate['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" value="Submit Vote">
            </div>
        </form>
        <?php } ?>
        <?php if (!empty($message)) { ?>
            <div class="message"><?php echo $message; ?></div>
        <?php } ?>
        <?php if (!empty($error)) { ?>
            <div class="message"><?php echo $error; ?></div>
        <?php } ?>
        <a class="button" href="vote_count.php">Votes</a>
        <a class="button" href="results.php">Results</a>
    </div>
</body>
</html>
