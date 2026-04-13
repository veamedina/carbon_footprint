<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $check = "SELECT * FROM users WHERE email='$email'";
    $check_result = mysqli_query($conn, $check);

    if (mysqli_num_rows($check_result) > 0) {
        $error = "❌ Email already registered!";
    } else {

        $sql = "INSERT INTO users (name, email, password)
                VALUES ('$name', '$email', '$password')";

        if (mysqli_query($conn, $sql)) {
            $success = "✅ Registration successful!";
        } else {
            $error = "❌ Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- YOUR CSS FILE (KEEP THIS!) -->
    <link rel="stylesheet" href="UI/css/style.css">
</head>

<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">

    <div class="card shadow p-4" style="width: 360px;">

        <h3 class="text-center eco-title">Create Account 🌱</h3>

        <?php
        if (isset($error)) echo "<p class='text-danger'>$error</p>";
        if (isset($success)) echo "<p class='text-success'>$success</p>";
        ?>

        <form method="POST">

            <input type="text" name="name" class="form-control mb-2" placeholder="Name" required>

            <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>

            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

            <button class="btn btn-success w-100" name="register">Register</button>

        </form>

        <p class="text-center mt-2">
            <a href="login.php">Already have an account?</a>
        </p>

    </div>

</div>

</body>
</html>