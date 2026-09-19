<?php
// Pastikan hanya melayani request bertipe POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

// Tangkap dan Amankan Data Input
$model_name  = htmlspecialchars($_POST["model_name"] ?? "Unknown Model");
$temperature = floatval($_POST["temperature"] ?? 0.7);
$prompt      = htmlspecialchars($_POST["prompt"] ?? "");
$precision   = htmlspecialchars($_POST["precision"] ?? "FP16");

// Logika Pengkondisian Server
if ($temperature >= 0.8) {
    $mode_analysis = "High Creativity / Stochastic Output";
} elseif ($temperature >= 0.4) {
    $mode_analysis = "Balanced Enterprise Mode";
} else {
    $mode_analysis = "Deterministic / Strict JSON Output";
}

// Menghitung perkiraan token & simulasi response time
$word_count = str_word_count($prompt);
$est_tokens = intval($word_count * 1.35) + 12;
$execution_time = rand(65, 130); // simulasi latensi server (ms)
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Execution Results &bull; Nexus AI Engine</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <?php include 'includes/navbar.php'; ?>

  <main class="main-wrapper">
    <header class="card hero-card" style="background-color: var(--accent-green);">
      <span class="badge-status" style="background: #fff;">STATUS: 200 OK &bull; PAYLOAD EXECUTED</span>
      <h1>Inference Execution Summary</h1>
      <p>Data form berhasil ditangkap oleh server PHP melalui superglobal <code>$_POST</code>.</p>
    </header>

    <section class="card">
      <h2>Ringkasan Parameter &amp; Analisis Server</h2>
      
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Server Attribute</th>
              <th>Parsed Value</th>
              <th>System Impact</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>Target Model</strong></td>
              <td><?php echo $model_name; ?></td>
              <td><span class="tag tag-ready">Active Instance</span></td>
            </tr>
            <tr>
              <td><strong>Precision Level</strong></td>
              <td><?php echo $precision; ?></td>
              <td>Memory footprint optimized</td>
            </tr>
            <tr>
              <td><strong>Temperature</strong></td>
              <td><?php echo $temperature; ?></td>
              <td><?php echo $mode_analysis; ?></td>
            </tr>
            <tr>
              <td><strong>Estimated Input Tokens</strong></td>
              <td>~<?php echo $est_tokens; ?> Tokens</td>
              <td>Calculated from <?php echo $word_count; ?> words</td>
            </tr>
            <tr>
              <td><strong>Server Dispatch Latency</strong></td>
              <td><?php echo $execution_time; ?> ms</td>
              <td>Network &bull; Runtime handler</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div style="margin-top: 24px;">
        <label class="group-label">Received Prompt Content (Sanitized):</label>
        <div style="background: #f1f5f9; padding: 14px; border: var(--border-medium); border-radius: 8px; margin-top: 8px; font-family: var(--font-code); font-size: 0.9rem;">
          <?php echo nl2br($prompt); ?>
        </div>
      </div>

      <div class="button-row" style="margin-top: 24px;">
        <a href="index.php" class="btn btn-primary" style="text-decoration: none; display: inline-block;">&larr; Kembali ke Dashboard</a>
      </div>
    </section>

    <?php include 'includes/footer.php'; ?>
  </main>
</body>
</html>