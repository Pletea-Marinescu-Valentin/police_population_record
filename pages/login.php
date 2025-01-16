<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../config/database.php';
include '../includes/header.php';
include '../includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM Users WHERE Username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['Password'])) {
        $_SESSION['user_id'] = $user['User_ID'];
        $_SESSION['role'] = $user['Role']; // Save the user role
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Incorrect username or password!";
    }
}
?>

<!-- HTML -->
<form method="POST" action="">
    <h2>Login</h2>
    <label>Username:</label>
    <input type="text" name="username" required>
    <label>Password:</label>
    <input type="password" name="password" required>
    <button type="submit">Login</button>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
</form>

<?php
include '../includes/footer.php';
?>
