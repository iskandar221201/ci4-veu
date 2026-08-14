<?php
/**
 * Partial: search_bar.php
 *
 * Note: this partial MUST be placed inside an Alpine x-data="dataTable(...)" scope
 * so that x-model="search" and fetch() are accessible.
 *
 * Accepted variables:
 * @var string $placeholder (optional, default 'Cari...')
 */
?>
<div class="relative w-full max-w-sm">

  <!-- Search icon -->
  <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none" aria-hidden="true">
    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
    </svg>
  </div>

  <label for="searchInput" class="sr-only">
    <?= esc($placeholder ?? 'Cari...') ?>
  </label>

  <input
    id="searchInput"
    type="text"
    x-model="search"
    @input.debounce.400ms="fetch()"
    placeholder="<?= esc($placeholder ?? 'Cari...') ?>"
    class="block w-full pl-9 pr-4 py-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400"
  >

</div>
