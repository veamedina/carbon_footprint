<!DOCTYPE html>
<html>
<head>
    <title>History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

<h2 class="text-success">📊 Activity History</h2>

<table class="table table-striped table-bordered mt-3">

<tr>
    <th>Date</th>
    <th>Transport</th>
    <th>Electricity</th>
    <th>Fuel</th>
    <th>Total CO2</th>
</tr>

<?php
include "db.php";
session_start();

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM activities WHERE user_id='$user_id' ORDER BY date DESC";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['date']}</td>
        <td>{$row['transport_km']}</td>
        <td>{$row['electricity_kwh']}</td>
        <td>{$row['fuel_liters']}</td>
        <td>{$row['total_emission']}</td>
    </tr>";
}
?>

</table>

</body>
</html>