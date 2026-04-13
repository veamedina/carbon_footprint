<?php
session_start();
include "db.php";

$user_id = $_SESSION['user_id'];

$message = "";
$total = null;

if (isset($_POST['submit'])) {

    $km = $_POST['km'];
    $kwh = $_POST['kwh'];
    $fuel = $_POST['fuel'];

    // calculation
    $transport = $km * 0.21;
    $electricity = $kwh * 0.5;
    $fuel_emission = $fuel * 2.31;

    $total = $transport + $electricity + $fuel_emission;

    // save to DB
    $sql = "INSERT INTO activities 
    (user_id, date, transport_km, electricity_kwh, fuel_liters, total_emission)
    VALUES 
    ('$user_id', NOW(), '$km', '$kwh', '$fuel', '$total')";

    mysqli_query($conn, $sql);

    $message = "Total CO2: $total kg";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <!-- Bootstrap (IMPORTANT UPGRADE) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Your CSS -->
    <link rel="stylesheet" href="UI/css/style.css">
</head>

<body class="bg-light">

<div class="container mt-4">

    <h2 class="text-success">🌿 Carbon Footprint Tracker</h2>

    <!-- INPUT CARD -->
    <div class="card p-3 shadow mt-3">

        <form method="POST">

            <div class="row">

                <div class="col-md-4">
                    <label>Transport (km)</label>
                    <input type="number" name="km" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label>Electricity (kWh)</label>
                    <input type="number" name="kwh" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label>Fuel (liters)</label>
                    <input type="number" name="fuel" class="form-control" required>
                </div>

            </div>

            <button type="submit" name="submit" class="btn btn-success mt-3">
                Calculate
            </button>

        </form>

    </div>

    <!-- RESULT -->
    <?php if ($message != "") { ?>
        <div class="alert alert-info mt-3">
            <h4><?= $message ?></h4>
        </div>
    <?php } ?>

    <!-- RECOMMENDATIONS -->
    <div class="card p-3 mt-3">

        <h5>🌱 Eco Recommendations</h5>

        <?php
        if ($total !== null) {

            if ($km > 20) echo "🚍 Use public transport<br>";
            if ($kwh > 10) echo "💡 Reduce electricity usage<br>";
            if ($fuel > 5) echo "🚗 Reduce fuel consumption<br>";

            if ($total > 50) {
                echo "<p class='text-danger'>⚠ High carbon footprint</p>";
            } else {
                echo "<p class='text-success'>🌍 Good job!</p>";
            }
        }
        ?>

    </div>

    <!-- RECENT ACTIVITY -->
    <div class="card p-3 mt-3">

        <h5>📊 Recent Activity</h5>

        <?php
        $sql = "SELECT * FROM activities 
                WHERE user_id='$user_id' 
                ORDER BY date DESC 
                LIMIT 3";

        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='border p-2 mb-2'>";
            echo "Date: {$row['date']}<br>";
            echo "CO2: {$row['total_emission']} kg";
            echo "</div>";
        }
        ?>

    </div>

    <!-- HISTORY BUTTON -->
    <a href="history.php" class="btn btn-outline-success mt-3">
        View Full History
    </a>

</div>

<!-- JS -->
<script src="UI/js/script.js"></script>

</body>
</html>