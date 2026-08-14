<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc(($title ?? '') ? $title . ' — ' . env('APP_NAME', 'CI4 Kit') : env('APP_NAME', 'CI4 Kit')) ?></title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Flowbite CSS CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css">
</head>
<body class="bg-gray-50 antialiased">

  <div class="flex min-h-screen"
       x-data="sidebarStore()"
       @keydown.window.escape="close()"
       @toggle-sidebar.window="toggle()">

    <!-- Backdrop overlay for mobile sidebar -->
    <div x-show="sidebarOpen"
         @click="close()"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-30 bg-black/50 lg:hidden"
         aria-hidden="true">
    </div>

    <!-- Sidebar -->
    <?= $this->include('_partials/sidebar') ?>

    <!-- Main content area -->
    <div class="flex flex-col flex-1 min-w-0">

      <!-- Navbar -->
      <?= $this->include('_partials/navbar') ?>

      <!-- Page content -->
      <main class="flex-1 p-6">

        <!-- Server-side flash messages -->
        <?= $this->include('_partials/flash') ?>

        <!-- Page content slot -->
        <?= $this->renderSection('content') ?>

      </main>

    </div>

  </div>

  <!-- Global overlays (outside flex layout to cover entire viewport) -->
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
