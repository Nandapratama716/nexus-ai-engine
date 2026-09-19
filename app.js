document.addEventListener("DOMContentLoaded", () => {
  const inferenceForm = document.getElementById("inference-form");
  const submitBtn = document.getElementById("submit-btn");
  const resultCard = document.getElementById("result-card");
  const liveOutput = document.getElementById("live-output");
  const tableBody = document.getElementById("history-table-body");

  // 1. Muat riwayat data dari database saat halaman dibuka (GET Request)
  loadInferenceHistory();

  // 2. Event Listener Form Submission 
  inferenceForm.addEventListener("submit", async (e) => {
    e.preventDefault(); // Mencegah reload halaman

    submitBtn.disabled = true;
    submitBtn.textContent = "Dispatched & Computing...";

    // Ambil data form
    const formData = new FormData(inferenceForm);
    const payload = {
      model_name: formData.get("model_name"),
      temperature: parseFloat(formData.get("temperature")),
      prompt: formData.get("prompt"),
      precision: formData.get("precision")
    };

    try {
      // Mengirim payload JSON ke endpoint backend 
      const response = await fetch("api_inference.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
      });

      const resJson = await response.json();

      if (resJson.status === "success") {
        const item = resJson.data;

        // Tampilkan output card hasil eksekusi (DOM Manipulation)
        resultCard.style.display = "block";
        liveOutput.innerHTML = `
          <strong>Execution Trace #${item.id}</strong><br>
          Model: <code>${item.model_name}</code> | Precision: <code>${item.precision}</code> | Latency: <strong>${item.latency_ms} ms</strong><br>
          Tokens Output: ~${item.tokens} tokens<br>
          <div style="margin-top: 10px; padding: 10px; background: #fff; border: 2px solid #121212; border-radius: 6px;">
            ${item.response_sample}
          </div>
        `;

        // Refresh tabel audit log
        loadInferenceHistory();
      } else {
        alert("Gagal memproses inferensi: " + resJson.message);
      }
    } catch (err) {
      console.error("Network / API Error:", err);
      alert("Terjadi kesalahan koneksi ke server backend.");
    } finally {
      submitBtn.disabled = false;
      submitBtn.textContent = "Dispatch Inference";
    }
  });

  // Fungsi Fetch Riwayat Database (SELECT Query via GET)
  async function loadInferenceHistory() {
    try {
      const res = await fetch("api_inference.php");
      const json = await res.json();

      if (json.status === "success" && tableBody) {
        tableBody.innerHTML = ""; // Bersihkan baris tabel lama

        if (json.data.length === 0) {
          tableBody.innerHTML = `<tr><td colspan="6" style="text-align: center;">Belum ada log inferensi tersimpan.</td></tr>`;
          return;
        }

        json.data.forEach((row) => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>#${row.id}</td>
            <td><strong>${row.model_name}</strong></td>
            <td>${row.latency_ms} ms</td>
            <td>~${row.generated_tokens} tok</td>
            <td><span class="tag tag-ready">${row.status}</span></td>
            <td><button class="btn-delete" onclick="deleteLog('${row.id}')">Delete</button></td>
          `;
          tableBody.appendChild(tr);
        });
      }
    } catch (e) {
      console.warn("Belum dapat memuat log tabel:", e);
    }
  }

  // Fungsi Helper Modal Konfirmasi Neo-Brutalist
  function showConfirmModal(message, title = "Hapus Log Inferensi") {
    return new Promise((resolve) => {
      const modal = document.getElementById("confirm-modal");
      const titleEl = document.getElementById("modal-title");
      const msgEl = document.getElementById("modal-message");
      const confirmBtn = document.getElementById("modal-btn-confirm");
      const cancelBtn = document.getElementById("modal-btn-cancel");
      const closeBtn = document.getElementById("modal-cancel-x");

      if (!modal) {
        resolve(window.confirm(message));
        return;
      }

      if (titleEl) titleEl.textContent = title;
      if (msgEl) msgEl.innerHTML = message;

      modal.style.display = "flex";
      modal.setAttribute("aria-hidden", "false");

      function cleanup(result) {
        modal.style.display = "none";
        modal.setAttribute("aria-hidden", "true");
        confirmBtn?.removeEventListener("click", onConfirm);
        cancelBtn?.removeEventListener("click", onCancel);
        closeBtn?.removeEventListener("click", onCancel);
        document.removeEventListener("keydown", onKeydown);
        modal.removeEventListener("click", onBackdropClick);
        resolve(result);
      }

      function onConfirm() { cleanup(true); }
      function onCancel() { cleanup(false); }
      function onKeydown(e) {
        if (e.key === "Escape") cleanup(false);
      }
      function onBackdropClick(e) {
        if (e.target === modal) cleanup(false);
      }

      confirmBtn?.addEventListener("click", onConfirm);
      cancelBtn?.addEventListener("click", onCancel);
      closeBtn?.addEventListener("click", onCancel);
      document.addEventListener("keydown", onKeydown);
      modal.addEventListener("click", onBackdropClick);
    });
  }

  // Fungsi global untuk menghapus log dari MongoDB
  window.deleteLog = async function(id) {
    const isConfirmed = await showConfirmModal(
      `Hapus log inferensi <code>#${id}</code> dari MongoDB? Data yang dihapus tidak dapat dipulihkan.`
    );
    if (!isConfirmed) return;

    try {
      const res = await fetch(`api_inference.php?id=${id}`, {
        method: "DELETE"
      });
      const json = await res.json();

      if (json.status === "success") {
        loadInferenceHistory();
      } else {
        alert("Gagal menghapus: " + json.message);
      }
    } catch (err) {
      console.error("Delete Error:", err);
      alert("Terjadi kesalahan jaringan saat menghapus dokumen.");
    }
  };
});