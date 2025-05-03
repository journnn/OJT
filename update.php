<?php
// Start the session
session_start();

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "maestro"); // Change database name as needed
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Initialize message variable
$message = ""; 
$messageClass = ""; 

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id']; // New field for ID
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the user ID exists in the database
    $checkSql = "SELECT * FROM login WHERE id = '$id'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        // User ID exists, proceed with the update
        $updateSql = "UPDATE login SET username='$username', password='$password' WHERE id='$id'";
        if ($conn->query($updateSql) === TRUE) {
            // Set session variables for success message
            $_SESSION['message'] = "User updated successfully!";
            $_SESSION['messageClass'] = "success"; 
            // Redirect to manage.php
            header("Location: manage.php");
            exit();
        } else {
            $message = "Error updating record: " . $conn->error;
            $messageClass = "error"; 
        }
    } else {
        // User ID does not exist, show invalid input alert
        $message = "User ID does not exist!";
        $messageClass = "error"; 
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Styling for the entire page */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
            flex-direction: column;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .alert {
            width: 100%;
            max-width: 500px;
            padding: 15px;
            color: white;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            font-size: 16px;
            font-weight: bold;
        }

        .alert.success {
            background-color: #4CAF50; /* Green background for success */
        }

        .alert.error {
            background-color: #f44336; /* Red background for error */
        }

        h2 {
            text-align: center;
            font-size: 36px;
            color: #800020;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
        }

        input[type="submit"], .back-button {
            width: 100%;
            padding: 10px;
            background-color: #800020;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            margin-top: 10px;
            text-align: center;
            display: block;
            text-decoration: none;
        }

        input[type="submit"]:hover, .back-button:hover {
            background-color: #a83232;
        }
    </style>
    <script>
        // Function to hide the alert after a few seconds
        function hideAlert() {
            const alertBox = document.querySelector('.alert');
            if (alertBox) {
                setTimeout(() => {
                    alertBox.style.display = 'none';
                }, 2000); // 5000ms = 5 seconds
            }
        }

        // Call the hideAlert function when the page loads
        window.onload = hideAlert;
    </script>
    <title>Update User Record</title>
</head>
<body>
    <!-- Display alert message if any -->
    <?php if ($message): ?>
        <div class="alert <?php echo $messageClass; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <h2>Update User Record</h2>
        <form method="POST" action="">
            <label for="id">ID:</label>
            <input type="text" id="id" name="id" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Update">
        </form>
        <!-- Back button to return to manage.php page -->
        <a href="manage.php" class="back-button">Back</a>
    </div>
</body>
</html>
