<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($title ?? env('APP_NAME', 'CI4 Kit')) ?></title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Flowbite CSS CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css">
</head>
<body class="bg-gray-50 antialiased">

  <main>
    <!-- Server-side flash messages -->
    <?= $this->include('_partials/flash') ?>

    <!-- Page content slot -->
    <?= $this->renderSection('content') ?>
  </main>

  <!-- Global overlays -->
  <?= $this->include('_partials/error_toast') ?>
  <?= $this->include('_partials/loading_overlay') ?>

  <!-- CI4 Kit JS — must load before Alpine initializes -->
  <script src="/assets/js/auth.js"></script>
  <script src="/assets/js/error.js"></script>
  <script src="/assets/js/api.js"></script>
  <script src="/assets/js/components.js"></script>

  <!-- Alpine.js CDN (defer) -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <!-- Flowbite JS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>

  <!-- Per-page scripts slot -->
  <?= $this->renderSection('scripts') ?>

</body>
</html>
