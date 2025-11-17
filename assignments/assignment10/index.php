<?php
// ====================== /index.php ======================
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Enter Zip Code to Get City Weather</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="bg-white">
<?php
  // Acknowledgement above the form; output below.
  $output = "";
  $acknowledgement = "";

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
      require_once __DIR__ . '/php/rest_client.php'; // include once
      [$acknowledgement, $output] = getWeather();
  }
?>
  <main class="container py-2">
    <h1 class="mb-2">Enter Zip Code to Get City Weather</h1>

    <!-- Errors/messages shown here (black text) -->
    <?php echo $acknowledgement; ?>

    <!-- Normal-size controls; field clears after submit -->
    <form method="post" class="mb-3" novalidate>
      <div class="mb-1">
        <label for="zip_code" class="form-label mb-0">Zip Code:</label>
        <div class="w-25">
          <input
            type="text"
            inputmode="numeric"
            pattern="[0-9]*"
            class="form-control"
            id="zip_code"
            name="zip_code">
        </div>
      </div>
      <button type="submit" class="btn btn-primary mt-1">Submit</button>
    </form>

    <!-- Results -->
    <?php echo $output; ?>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
          crossorigin="anonymous"></script>
</body>
</html>
