<?php
// Set a variable to track if the form was submitted successfully
$submit = false;

// Check if the form was submitted using the 'name' field
if (isset($_POST['name'])) {

    // Database connection details
    $server = "localhost";
    $username = "root";
    $password = "";
    $dbname = "trip"; 

    // Establish a connection to the database
    $con = mysqli_connect($server, $username, $password, $dbname);

    // Check for connection errors and stop if there's an issue
    if (!$con) {
        die("Connection to this database failed due to " . mysqli_connect_error());
    }

    // Get the form data. Variable names are lowercase to match the form.
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $other = $_POST['desc'];

    // This is the correct, secure SQL query using a prepared statement.
    // The '?' are placeholders for the data.
    $sql = "INSERT INTO `trip` (`Name`, `Age`, `Gender`, `Email`, `Phone`, `Other`, `DT`) VALUES (?, ?, ?, ?, ?, ?, current_timestamp());";

    // Prepare the SQL statement for execution
    if ($stmt = $con->prepare($sql)) {
        // Bind the variables to the placeholders in the correct order and type
        // 's' = string, 'i' = integer. This prevents injection.
        $stmt->bind_param("sissss", $name, $age, $gender, $email, $phone, $other);

        // Execute the prepared statement
        if ($stmt->execute()) {
            $submit = true;
        } else {
            echo "ERROR: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "ERROR: Could not prepare query: " . $con->error;
    }

    // Close the database connection
    $con->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Travel Form</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Domine:wght@400..700&family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="roll.css">
</head>
<body>
    <img class="clg" src="clg.jpg" alt="SBS College" width="500" height="auto">
    <div class="container">
        <h1>Welcome to SBS USA Trip Form</h1>
        <p>Enter your details and submit this form to confirm your participation in the trip.</p>
        
        <?php
        // Display the success message only if the form was submitted successfully
        if ($submit) {
            echo "<p class='submitMsg'>Thanks for submitting our form. We are happy to see you joining us for the USA trip!</p>";
        }
        ?>

        <form action="roll.php" method="post">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" placeholder="Enter your full name" required>

            <label for="age">Age:</label>
            <input type="number" name="age" id="age" placeholder="Enter your age" required>
            
            <label for="gender">Gender:</label>
            <input type="text" name="gender" id="gender" placeholder="Enter your gender">
            
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>
            
            <label for="phone">Phone:</label>
            <input type="tel" name="phone" id="phone" placeholder="Enter your phone number" required>
            
            <label for="desc">Additional Information:</label>
            <textarea name="desc" id="desc" cols="30" rows="10" placeholder="Enter any additional information here"></textarea>

            <button type="submit" class="btn">Submit</button>
            <button type="reset" class="btn">Reset</button>
        </form>
    </div>
    <script src="roll.js"></script>
</body>
</html>