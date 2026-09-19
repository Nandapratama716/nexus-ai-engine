# ⚡ Nexus AI Engine — Neural Management Gateway (v1.4.0)

Console orkestrasi dan observabilitas inferensi Model Bahasa Skala Besar (LLM). Dibangun dengan antarmuka **Neo-Brutalism**, arsitektur modular **PHP 8.x**, pemrosesan asinkron **JavaScript Fetch API**, serta persistensi NoSQL enterprise menggunakan **MongoDB Server & PHP MongoDB Driver**.

---

## 🚀 Versi 1.4.0 Release Notes: Migrasi MongoDB
Pada versi **v1.4.0**, Nexus AI Engine secara resmi telah bermigrasi penuh dari penyimpanan file JSON lokal (`database.json`) menuju **MongoDB NoSQL Database**:
- **Enterprise Persistence:** Menggunakan MongoDB Server (`mongodb://127.0.0.1:27017`) dengan database `nexus_ai_db`.
- **PHP MongoDB Library:** Integrasi official library `mongodb/mongodb` (^2.4) via Composer dan MongoDB PHP Extension.
- **RESTful CRUD & BSON Handling:** Pengelolaan data audit log menggunakan BSON ObjectId (`MongoDB\BSON\ObjectId`) dan UTC DateTime (`MongoDB\BSON\UTCDateTime`).
- **Aggregation Pipeline:** Endpoint kalkulasi analitik metrik performa (`total_runs`, `avg_latency`, `total_tokens`) langsung via MongoDB Aggregation Framework.
- **Neo-Brutalist Confirmation Modal:** Konfirmasi penghapusan data dengan modal kustom asinkron bertema Neo-Brutalism menggantikan dialog bawaan browser.

---

## 🌟 Fitur Utama & Modul Sistem

1. **Tactile Neo-Brutalism Design System:**
   - Desain kontras tinggi dengan border tebal (*ink-black*), bayangan mekanis (*hard drop shadows*), dan palet warna berani (*vivid yellow, emerald, cyan, coral red*).
2. **Processing Pipeline Architecture:**
   - Visualisasi alur inferensi multi-tahap (*Tokenizer $\rightarrow$ Transformer Layers $\rightarrow$ Detokenizer*) dengan indikator akselerasi (KV Cache, Dynamic Quantization, Batching).
3. **Dynamic Model Registry & Benchmark:**
   - Tabel spesifikasi model inferensi aktif (Llama-3-8B, Mistral-7B, BGE-Large, CNN Classifier) dengan rincian parameter, konteks window, dan latency tier.
4. **Asynchronous Inference Studio (SPA):**
   - Pengiriman prompt dan dispatch inferensi tanpa reload halaman menggunakan Fetch API asinkron.
5. **Real-Time Execution Output Card:**
   - Visualisasi status trace ID eksekusi, komputasi latensi, kalkulasi token, dan sampel respons model secara instan.
6. **MongoDB Audit Logging & Actions:**
   - Riwayat inferensi tersimpan ke collection `audit_logs` dan dimuat real-time ke tabel audit log.
   - Fitur penghapusan dokumen via `DELETE` request dengan verifikasi Neo-Brutalist confirmation modal.
7. **Real-Time Metrics Aggregation:**
   - Endpoint statistik ringkasan performa yang menghitung rata-rata latensi dan total token tergenerasi menggunakan aggregation pipeline MongoDB.

---

## 🛠️ Tech Stack

| Layer | Teknologi | Keterangan |
| :--- | :--- | :--- |
| **Frontend** | HTML5 Semantic, CSS3 (Neo-Brutalism System), Vanilla JavaScript (ES6+) | Single-Page Application feel, Fetch API, Promise-based Modal |
| **Backend Engine** | PHP 8.2+ / 8.3+ | Modular Architecture, REST API Handler, BSON Serialization |
| **Database** | MongoDB Server 6.0+ / 7.0+ | NoSQL Document Store (`audit_logs`, `model_registry`) |
| **Driver & SDK** | `mongodb/mongodb` (^2.4) via Composer | Official MongoDB PHP Library & `ext-mongodb` |
| **Protocols & Format** | JSON, RESTful HTTP (GET, POST, DELETE), BSON | Asynchronous Data Interchange |

---

## 📡 REST API Endpoints (`api_inference.php`)

| Method | Query / Payload | Deskripsi | Respons |
| :--- | :--- | :--- | :--- |
| `GET` | *(none)* | Mengambil 10 dokumen log inferensi terbaru | `{"status": "success", "data": [...]}` |
| `GET` | `?action=stats` | Menghitung agregasi statistik (total run, rerata latency, total token) | `{"status": "success", "data": {"total_runs": N, "avg_latency": N, "total_tokens": N}}` |
| `POST` | `{"model_name", "temperature", "prompt", "precision"}` | Menyimpan rekaman inferensi baru ke MongoDB | `{"status": "success", "message": "...", "data": {...}}` |
| `DELETE` | `?id={ObjectId}` | Menghapus dokumen log berdasarkan BSON ObjectId | `{"status": "success", "message": "Log berhasil dihapus"}` |

---

## 📂 Struktur Direktori

```text
nexus-ai-engine/
│
├── composer.json           # Dependensi project (mongodb/mongodb ^2.4)
├── composer.lock           # Lock file dependensi Composer
├── vendor/                 # Autoloader & dependensi vendor Composer
│
├── db.php                  # Koneksi terpusat MongoDB Client & inisialisasi collection
├── api_inference.php       # RESTful API handler (GET, POST, DELETE, Stats Aggregation)
├── app.js                  # Frontend runtime (Fetch API, DOM rendering, modal handler, deleteLog)
├── index.php               # Console dashboard utama (Pipeline, Registry, Studio, Audit Table, Modal)
├── process.php             # Handler fallback form POST konvensional
├── style.css               # Desain sistem & styling Neo-Brutalism (Komponen, Form, Modal)
│
├── includes/
│   ├── navbar.php          # Komponen navigasi modular
│   └── footer.php          # Komponen footer dinamis
│
└── README.md               # Dokumentasi sistem Nexus AI Engine
```

---

## ⚙️ Panduan Menjalankan Sistem

### 1. Prasyarat Sistem
- PHP versi 8.2 atau lebih baru dengan ekstensi `mongodb` (`php_mongodb.dll` di Windows atau `mongodb.so` di Linux).
- MongoDB Server aktif dan berjalan di `localhost:27017`.
- Composer terpasang di sistem.

### 2. Instalasi Dependensi
Jalankan Composer install di root direktori project:
```bash
composer install
```

### 3. Jalankan Development Server
Jalankan PHP built-in web server:
```bash
php -S localhost:8080
```
Buka browser dan akses console di `http://localhost:8080`.