<?php
session_start();
include "db.php";

// Initialize variables
$result = null;
$suggestions = [];
$total_emission = 0;

// Handle form submission
if (isset($_POST['compute'])) {

    $category = $_POST['category'];
    $item = $_POST['item'];
    $value = $_POST['value']; // distance / kWh / waste weight

    // Get emission factor from DB
    $stmt = $conn->prepare("SELECT factor FROM emission_factors WHERE category=? AND item=?");
    $stmt->bind_param("ss", $category, $item);
    $stmt->execute();
    $stmt->bind_result($factor);
    $stmt->fetch();
    $stmt->close();

    if ($factor) {
        $result = $value * $factor;
        $total_emission = $result;

        // --------------------------
        // RECOMMENDATION LOGIC
        // --------------------------

        if ($category == "transport") {
            if ($result > 10) {
                $suggestions[] = "High transport emissions detected. Consider public transportation or carpooling.";
            } else {
                $suggestions[] = "Good job! Your transportation emissions are low.";
            }
        }

        if ($category == "electricity") {
            if ($result > 50) {
                $suggestions[] = "High electricity usage. Switch to LED lighting and energy-efficient appliances.";
                $suggestions[] = "Consider renewable energy sources like solar panels.";
            } else {
                $suggestions[] = "Your electricity usage is efficient. Keep it up!";
            }
        }

        if ($category == "waste") {
            if ($result > 20) {
                $suggestions[] = "Reduce waste by recycling and composting organic materials.";
            } else {
                $suggestions[] = "Good waste management habits detected.";
            }
        }

    } else {
        $result = "No emission factor found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Carbon Footprint Tracker</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        .container { background: white; padding: 20px; max-width: 600px; margin: auto; border-radius: 10px; }
        select, input { width: 100%; padding: 10px; margin: 10px 0; }
        button { padding: 10px; background: green; color: white; border: none; width: 100%; }
        .result { margin-top: 20px; padding: 10px; background: #e9ffe9; }
        .suggestions { margin-top: 20px; background: #fff3cd; padding: 10px; }
    </style>
</head>
<body>

<div class="container">

    <h2>🌍 Carbon Footprint Tracker</h2>

    <!-- FORM -->
    <form method="POST">

        <!-- CATEGORY -->
        <label>Category</label>
        <select name="category" required>
            <option value="">-- Select Category --</option>
            <option value="transport">Transportation</option>
            <option value="electricity">Electricity</option>
            <option value="waste">Waste</option>
        </select>

        <!-- ITEM -->
        <label>Type</label>
        <select name="item" required>
            <option value="passenger_car">Passenger Car</option>
            <option value="motorcycle">Motorcycle</option>
            <option value="electricity_grid">Electricity Grid</option>
            <option value="food_waste">Food Waste</option>
        </select>

        <!-- VALUE -->
        <label>Value (km / kWh / tons)</label>
        <input type="number" step="0.01" name="value" required>

        <button type="submit" name="compute">Compute CO₂e</button>
    </form>

    <!-- RESULT -->
    <?php if ($result !== null): ?>
        <div class="result">
            <h3>Result</h3>
            <p><strong>CO₂e Emissions:</strong> <?= $result ?></p>
        </div>
    <?php endif; ?>

    <!-- SUGGESTIONS -->
    <?php if (!empty($suggestions)): ?>
        <div class="suggestions">
            <h3>🌱 Recommendations</h3>
            <?php foreach ($suggestions as $s): ?>
                <p>✔ <?= $s ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

</body>
</html>