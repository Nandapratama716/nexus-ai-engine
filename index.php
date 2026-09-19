<?php
// Data Model Registry
$model_registry = [
    [
        "id" => "llama3-8b",
        "name" => "Llama-3-8B-Instruct",
        "capability" => "Multi-turn Reasoning & Synthesis",
        "latency" => 142,
        "ram" => "16.0 GB",
        "status" => "ready"
    ],
    [
        "id" => "mistral-7b",
        "name" => "Mistral-7B-v0.2",
        "capability" => "Structured Output & Code Gen",
        "latency" => 118,
        "ram" => "14.5 GB",
        "status" => "ready"
    ],
    [
        "id" => "bge-large",
        "name" => "BGE-Large-EN-v1.5",
        "capability" => "Semantic Search Embeddings",
        "latency" => 24,
        "ram" => "1.3 GB",
        "status" => "ready"
    ],
    [
        "id" => "custom-cnn",
        "name" => "Custom-CNN-Classifier",
        "capability" => "Visual Artifact Detection",
        "latency" => 45,
        "ram" => "512 MB",
        "status" => "eval"
    ]
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nexus AI Engine &bull; Management Console</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- Modular Include: Navbar (Part 5) -->
  <?php include 'includes/navbar.php'; ?>

  <main class="main-wrapper">
    
    <header class="card hero-card">
      <span class="badge-status">BACKEND ACTIVE &bull; PHP <?php echo phpversion(); ?></span>
      <h1>Neural Engine Management Gateway</h1>
      <p>
        Pusat kendali dan observabilitas inferensi model bahasa skala besar (LLM).
        Data tabel dan komponen sekarang di-generate secara dinamis dari sisi server.
      </p>
    </header>

    <!-- Benchmark Model Registry Table (Rendered via foreach loop) -->
    <section id="benchmark" class="card">
      <h2>Model Registry &amp; Benchmark Performa</h2>
      <p>Data berikut digenerate langsung dari Array PHP di server:</p>
      
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Model Identifier</th>
              <th>Primary Capability</th>
              <th>Latency (p95)</th>
              <th>Memory Footprint</th>
              <th>Lifecycle Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($model_registry as $model): ?>
              <tr>
                <td><strong><?php echo $model["name"]; ?></strong></td>
                <td><?php echo $model["capability"]; ?></td>
                <td><?php echo $model["latency"]; ?> ms</td>
                <td><?php echo $model["ram"]; ?></td>
                <td>
                  <?php if ($model["status"] === "ready"): ?>
                    <span class="tag tag-ready">Production Ready</span>
                  <?php else: ?>
                    <span class="tag tag-eval">Evaluating</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Inference Studio Form (Menghubungkan Form ke PHP Backend) -->
    <section id="playground" class="card">
      <h2>Inference Studio &amp; Request Dispatcher</h2>
      <p>Kirim parameter inferensi untuk diproses oleh skrip server-side (<code>process.php</code>):</p>

      <form action="process.php" method="POST" class="ai-form">
        <div class="form-row">
          <div class="form-group flex-1">
            <label for="model-select">Target Model Endpoint:</label>
            <select id="model-select" name="model_name">
              <?php foreach ($model_registry as $model): ?>
                <option value="<?php echo $model["name"]; ?>">
                  <?php echo $model["name"]; ?> (<?php echo $model["latency"]; ?>ms)
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group flex-1">
            <label for="temp-input">Sampling Temperature (0.0 - 1.0):</label>
            <input type="number" id="temp-input" name="temperature" min="0" max="1" step="0.1" value="0.7" required>
          </div>
        </div>

        <div class="form-group">
          <label for="user-prompt">System Context &amp; Payload Instruction:</label>
          <textarea id="user-prompt" name="prompt" rows="4" placeholder="Tulis instruksi prompt evaluasi sistem..." required></textarea>
        </div>

        <div class="form-group">
          <label class="group-label">Quantization / Execution Precision:</label>
          <div class="radio-group">
            <label class="choice-item">
              <input type="radio" name="precision" value="FP16" checked> FP16 (High Fidelity)
            </label>
            <label class="choice-item">
              <input type="radio" name="precision" value="INT8"> INT8 (Balanced)
            </label>
            <label class="choice-item">
              <input type="radio" name="precision" value="INT4"> INT4 (Low-Memory)
            </label>
          </div>
        </div>

        <div class="button-row">
          <button type="submit" class="btn btn-primary">Dispatch to Server</button>
          <button type="reset" class="btn btn-secondary">Clear Parameters</button>
        </div>
      </form>
    </section>

    <!-- Modular Include: Footer (Part 5) -->
    <?php include 'includes/footer.php'; ?>

  </main>
</body>
</html>