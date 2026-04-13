<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $row = mysqli_fetch_assoc($result);

        // STORE USER SESSION
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['name'] = $row['name'];

        header("Location: dashboard.php");
        exit();

    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">

    <div class="card shadow p-4" style="width: 350px;">

        <h3 class="text-center text-success">🌿 Carbon Tracker</h3>
        <h5 class="text-center mb-3">Login</h5>

        <?php
        if (isset($error)) {
            echo "<p class='text-danger text-center'>$error</p>";
        }
        ?>

        <form method="POST">

            <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>

            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

            <button name="login" class="btn btn-success w-100">Login</button>

        </form>

        <p class="text-center mt-2">
            No account? <a href="register.php">Register</a>
        </p>

    </div>

</div>

</body>
</html>