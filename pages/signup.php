<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../config/database.php';
include '../includes/header.php';
include '../includes/functions.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user into the database
        $query = "INSERT INTO Users (Username, Password, Role) VALUES (:username, :password, 'User')";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);

        if ($stmt->execute()) {
            echo "Account created successfully! You can now log in.";
            header("Location: login.php"); // Redirect the user to the login page
            exit;
        } else {
            echo "An error occurred while creating the account.";
        }
    }
}
?>

<!-- Signup form -->
<form method="POST" action="">
    <h2>Create an Account</h2>
    <label>Username:</label>
    <input type="text" name="username" required><br>
    <label>Password:</label>
    <input type="password" name="password" required><br>
    <label>Confirm Password:</label>
    <input type="password" name="confirm_password" required><br>
    <button type="submit">Create Account</button>
</form>

<?php
include '../includes/footer.php';
?>
