<?php
// ===== /index.php =====
/**
 * Assignment 10 REST — Bootstrap version
 * Renders one form, calls php/rest_client.php via getWeather(), and displays results.
 * Follows Canvas spec for $acknowledgement (above form) and $output (below form).
 */
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Assignment 10 REST (Bootstrap)</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous">
</head>
<body class="bg-light">
<?php
  // Spec-defined variables (:contentReference[oaicite:10]{index=10})
  $output = "";
  $acknowledgement = "";

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
      require __DIR__ . '/php/rest_client.php';
      $result = getWeather();
      $acknowledgement = $result[0];
      $output = $result[1];
  }
?>
  <main class="container py-4">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-8">

        <h1 class="mb-3 text-center">Weather by Zip</h1>

        <!-- Acknowledgement above the form (:contentReference[oaicite:11]{index=11}) -->
        <?php echo $acknowledgement; ?>

        <form method="post" class="card shadow-sm mb-4">
          <div class="card-body">
            <div class="mb-3">
              <label for="zip_code" class="form-label">Zip Code</label>
              <input
                type="text"
                inputmode="numeric"
                pattern="[0-9]*"
                class="form-control"
                id="zip_code"
                name="zip_code"
                placeholder="e.g., 12345"
                value="<?php echo isset($_POST['zip_code']) ? htmlspecialchars($_POST['zip_code']) : ''; ?>"
                required>
              <div class="form-text">Use a fictitious zip from the assignment list.</div>
            </div>
            <button type="submit" class="btn btn-primary">Get Weather</button>
          </div>
        </form>

        <!-- Output below the form (:contentReference[oaicite:12]{index=12}) -->
        <?php echo $output; ?>

      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
          crossorigin="anonymous"></script>
</body>
</html>
