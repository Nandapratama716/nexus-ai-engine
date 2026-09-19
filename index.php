<?php

require_once __DIR__ . '/db.php';

// Query 10 riwayat inferensi terakhir langsung dari MongoDB
try {
    $initialLogs = $auditCollection->find([], [
        'sort' => ['_id' => -1],
        'limit' => 10
    ]);
} catch (Exception $e) {
    $initialLogs = [];
}

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

  <?php include 'includes/navbar.php'; ?>

  <main class="main-wrapper">
    <header class="card hero-card">
      <span class="badge-status">FULLSTACK AI ENGINE</span>
      <h1>Neural Engine Management Gateway</h1>
      <p>
        Pusat kendali dan observabilitas inferensi model bahasa skala besar (LLM).
        Mendukung transmisi asinkron via <strong>JavaScript Fetch API</strong>, payload standar <strong>JSON</strong>,
        serta audit log persisten berbasis dokumen data.
      </p>
    </header>

    <section id="pipeline" class="card">
      <h2>Architecture &amp; Processing Pipeline</h2>
      <p>Tahapan pemrosesan inferensi dari penerimaan prompt hingga output terstruktur:</p>
      
      <div class="diagram-box">
        <div class="step-node">
          <div class="node-title">1. Tokenizer</div>
          <div class="node-desc">BPE Encoding &amp; Embedding</div>
        </div>
        <div class="step-arrow">&rarr;</div>
        <div class="step-node node-active">
          <div class="node-title">2. Transformer Layers</div>
          <div class="node-desc">Multi-Head Self-Attention</div>
        </div>
        <div class="step-arrow">&rarr;</div>
        <div class="step-node">
          <div class="node-title">3. Detokenizer</div>
          <div class="node-desc">Softmax &amp; Sampling Gen</div>
        </div>
      </div>

      <ul class="tech-list" style="margin-top: 18px;">
        <li><strong>Low-Latency KV Cache:</strong> Mempercepat komputasi sekuensial dengan menyimpan state attention sebelumnya.</li>
        <li><strong>Dynamic Quantization:</strong> Dukungan presisi FP16, INT8, dan INT4 untuk efisiensi VRAM.</li>
        <li><strong>Continuous Batching:</strong> Mengelompokkan antrean request secara dinamis pada GPU cluster.</li>
      </ul>
    </section>

    <section id="benchmark" class="card">
      <h2>Model Registry &amp; Benchmark Performa</h2>
      <p>Daftar engine inferensi aktif yang dikelola oleh gateway server:</p>
      
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

    <section id="playground" class="card">
      <h2>Inference Studio (Live Dispatch)</h2>
      <p>Form ini dieksekusi secara asinkron (SPA) tanpa perlu memuat ulang halaman:</p>

      <form id="inference-form" class="ai-form">
        <div class="form-row">
          <div class="form-group flex-1">
            <label for="model-select">Target Model Endpoint:</label>
            <select id="model-select" name="model_name">
              <?php foreach ($model_registry as $m): ?>
                <option value="<?php echo $m['name']; ?>">
                  <?php echo $m['name']; ?> (<?php echo $m['latency']; ?>ms)
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
          <textarea id="user-prompt" name="prompt" rows="3" placeholder="Ketik prompt inferensi untuk model..." required></textarea>
        </div>

        <div class="form-group">
          <label class="group-label">Quantization / Execution Precision:</label>
          <div class="radio-group">
            <label class="choice-item">
              <input type="radio" name="precision" value="FP16" checked> FP16
            </label>
            <label class="choice-item">
              <input type="radio" name="precision" value="INT8"> INT8
            </label>
            <label class="choice-item">
              <input type="radio" name="precision" value="INT4"> INT4
            </label>
          </div>
        </div>

        <div class="button-row">
          <button type="submit" id="submit-btn" class="btn btn-primary">Dispatch Inference</button>
          <button type="reset" class="btn btn-secondary">Clear</button>
        </div>
      </form>

      <div id="result-card" style="display: none; margin-top: 20px; padding: 18px; background-color: #fef08a; border: var(--border-medium); border-radius: 8px; box-shadow: var(--shadow-hard-sm);">
        <h3 style="margin-bottom: 8px;">Execution Output:</h3>
        <div id="live-output" style="font-size: 0.9rem;"></div>
      </div>
    </section>

    <section id="audit" class="card">
      <h2>Inference Audit Log (Live Database Records)</h2>
      <p>Data berikut diambil secara real-time dari dokumen database backend:</p>
      
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Trace ID</th>
              <th>Model Active</th>
              <th>Compute Latency</th>
              <th>Tokens</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="history-table-body">
            <?php if (!empty($initialLogs)): ?>
              <?php foreach ($initialLogs as $log): ?>
                <tr>
                  <td>#<?= htmlspecialchars((string)$log['_id']) ?></td>
                  <td><strong><?= htmlspecialchars($log['model_name'] ?? 'Nexus-Model') ?></strong></td>
                  <td><?= htmlspecialchars($log['latency_ms'] ?? 0) ?> ms</td>
                  <td>~<?= htmlspecialchars($log['generated_tokens'] ?? 0) ?> tok</td>
                  <td><span class="tag tag-ready"><?= htmlspecialchars($log['status'] ?? 'SUCCESS') ?></span></td>
                  <td>
                    <button 
                      class="btn-delete" 
                      onclick="deleteLog('<?= (string)$log['_id'] ?>')"
                    >
                      Delete
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
            <tr>
              <td colspan="6" style="text-align: center;">Belum ada log inferensi tersimpan.</td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <section class="card">
      <h2>Cluster Specifications &amp; Runtime</h2>
      <ol class="steps-list">
        <li><strong>Host Node:</strong> Ubuntu Server x86_64 dengan akselerasi NVIDIA TensorRT-LLM.</li>
        <li><strong>Orchestrator:</strong> Docker containerized runtime dengan auto-scaling listener.</li>
        <li><strong>Security Standard:</strong> Sanitasi payload anti-prompt-injection dan enkripsi TLS 1.3 transit.</li>
      </ol>
      <div class="ext-note">
        Pelajari dokumentasi lengkap engine di: 
        <a href="https://huggingface.co/models" target="_blank" rel="noopener">Hugging Face Hub Registry &rarr;</a>
      </div>
    </section>

    <?php include 'includes/footer.php'; ?>

  </main>

  <!-- Neo-Brutalist Confirmation Modal -->
  <div id="confirm-modal" class="modal-overlay" aria-hidden="true" style="display: none;">
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="modal-title">
      <div class="modal-header">
        <span class="modal-badge">&#x26A0;&#xFE0F; SYSTEM ACTION</span>
        <button type="button" class="modal-close-btn" id="modal-cancel-x" aria-label="Tutup">&times;</button>
      </div>
      <h3 class="modal-title" id="modal-title">Hapus Log Inferensi</h3>
      <p class="modal-text" id="modal-message">Apakah Anda yakin ingin menghapus log inferensi ini dari database MongoDB?</p>
      <div class="modal-actions">
        <button type="button" class="btn btn-secondary" id="modal-btn-cancel">Batal</button>
        <button type="button" class="btn btn-danger" id="modal-btn-confirm">Hapus Log</button>
      </div>
    </div>
  </div>

  <script src="app.js"></script>
</body>
</html>