<?php
/**
 * Partial: page_header.php
 *
 * Accepted variables:
 * @var string $title        (required) Page title
 * @var array  $breadcrumbs  (optional) [['label' => string, 'url' => string|null], ...]
 *                           Last item without 'url' = current page (rendered as plain text)
 * @var array  $action       (optional) ['label' => string, 'url' => string]
 */
?>
<div class="mb-6">

  <div class="flex flex-col lg:flex-row items-start justify-between gap-4">

    <!-- Page title -->
    <div>
      <h1 class="text-2xl font-bold text-gray-900">
        <?= esc($title ?? '') ?>
      </h1>

      <!-- Breadcrumb -->
      <?php if (! empty($breadcrumbs)): ?>
        <nav aria-label="Breadcrumb" class="mt-1">
          <ol class="flex items-center gap-1.5 text-sm text-gray-500">
            <?php foreach ($breadcrumbs as $i => $crumb): ?>

              <?php if ($i > 0): ?>
                <li aria-hidden="true">
                  <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                  </svg>
                </li>
              <?php endif ?>

              <li>
                <?php if (! empty($crumb['url'])): ?>
                  <a href="<?= esc($crumb['url']) ?>"
                     class="hover:text-gray-900 hover:underline underline-offset-2 transition-colors">
                    <?= esc($crumb['label'] ?? '') ?>
                  </a>
                <?php else: ?>
                  <span class="text-gray-700 font-medium" aria-current="page">
                    <?= esc($crumb['label'] ?? '') ?>
                  </span>
                <?php endif ?>
              </li>

            <?php endforeach ?>
          </ol>
        </nav>
      <?php endif ?>
    </div>

    <!-- Optional action button -->
    <?php if (! empty($action)): ?>
      <a href="<?= esc($action['url'] ?? '#') ?>"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-medium hover:bg-gray-700 focus:outline-none transition-colors flex-shrink-0">
        <!-- Plus icon -->
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        <?= esc($action['label'] ?? 'Tambah') ?>
      </a>
    <?php endif ?>

  </div>

</div>
