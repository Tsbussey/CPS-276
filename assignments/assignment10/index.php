<?php
// ===== /index.php (updated) =====
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Enter Zip Code to Get City Weather</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous">
</head>
<body class="bg-white">
<?php
  $output = "";
  $acknowledgement = "";
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
      require __DIR__ . '/php/rest_client.php';
      [$acknowledgement, $output] = getWeather();
  }
?>
  <main class="container py-4">
    <h1 class="mb-4">Enter Zip Code to Get City Weather</h1>

    <?php echo $acknowledgement; ?>

    <form method="post" class="mb-4">
      <div class="mb-3">
        <label for="zip_code" class="form-label">Zip Code:</label>
        <div class="w-25"> <!-- Bootstrap utility width; keeps input short -->
          <input
            type="text"
            inputmode="numeric"
            pattern="[0-9]*"
            class="form-control"
            id="zip_code"
            name="zip_code"
            value="<?php echo isset($_POST['zip_code']) ? htmlspecialchars($_POST['zip_code']) : ''; ?>"
            required>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <?php echo $output; ?>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
          crossorigin="anonymous"></script>
</body>
</html>
