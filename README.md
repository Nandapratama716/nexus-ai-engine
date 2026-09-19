Nexus AI Engine — Inference Gateway & Management Console

A lightweight, enterprise-ready model governance console and inference dispatcher built with semantic HTML5, Neo-Brutalist CSS3, and a dynamic PHP server-side runtime.

📌 Overview

Nexus AI Engine serves as an operational dashboard to monitor inference latency benchmarks across Large Language Models (LLMs) and execute payload requests. Designed with high modularity and zero bloated third-party dependencies, this gateway demonstrates end-to-end integration between modern UI components and server-side request pipelines.

🚀 Key Features

Dynamic Model Registry: Server-side rendered model metrics catalog (p95 latency, precision formats, memory footprints) generated via structured associative arrays.

Inference Studio (Request Dispatcher): Interactive parameter controller supporting configurable sampling temperature, quantization modes (FP16, INT8, INT4), and streaming flags.

Sanitized Payload Processing: Server-side ingestion pipeline powered by PHP $_POST with XSS sanitation (htmlspecialchars) and heuristic token estimation.

Neo-Brutalist Design System: High-contrast, accessible UI featuring solid 3px borders, tactile mechanical click states, and hard offset drop shadows.

Modular Architecture: Componentized layout splitting navigation and persistent footers into reusable includes.

🛠 Tech Stack & Specifications

Layer

Technology

Details

Frontend Structure

Semantic HTML5

Form controls, tabular data, SVG architecture diagrams

Styling & Layout

Modern CSS3

Neo-Brutalism, Box Model, Sticky Positioning, CSS Grid

Backend Runtime

PHP 8.x

Built-in CLI Server, superglobal $_POST, modular templating

Version Control

Git & GitHub

Modular directory structure

📂 Project Structure

nexus-ai-engine/
│
├── includes/
│   ├── navbar.php          # Reusable sticky navigation component
│   └── footer.php          # Dynamic server timestamp & footer component
│
├── index.php               # Core dashboard & dynamically rendered model catalog
├── process.php             # Server-side payload processor & latency analyzer
├── style.css               # Neo-Brutalist stylesheet
└── README.md               # Engineering documentation


⚡ Quick Start

1. Clone the Repository

git clone https://github.com/<your-username>/nexus-ai-engine.git
cd nexus-ai-engine


2. Verify PHP Installation

Ensure PHP 8.x is installed and accessible in your environment PATH:

php -v


3. Start the Local Server

Run the built-in development server from the project root directory:

php -S localhost:8080


4. Access the Gateway

Open your browser and navigate to:

http://localhost:8080/index.php


🔒 Security & Performance Considerations

Input Sanitization: User prompts and metadata are escaped using htmlspecialchars to mitigate Cross-Site Scripting (XSS).

Zero Overhead: No heavy front-end libraries (pure vanilla HTML/CSS) ensuring sub-10ms initial client render times.

Production Gateway Ready: Form actions are decoupled to easily map to Python/FastAPI microservices in upcoming modules.

📄 License

This project is licensed under the MIT License - see the LICENSE file for details.