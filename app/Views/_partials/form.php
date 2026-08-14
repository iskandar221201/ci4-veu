<?php
/**
 * Partial: form.php
 *
 * Form wrapper using the Alpine formHandler component. Field content is injected
 * by the page via $this->section('form_fields') / $this->endSection(), or
 * included inline before the closing form tag.
 *
 * Accepted variables:
 * @var string      $endpoint    (required) API endpoint, e.g. '/api/users'
 * @var string      $method      (optional, default 'POST') HTTP method
 * @var string|null $redirectUrl (optional) URL to redirect to after a successful submission
 * @var string      $submitLabel (optional, default 'Simpan') Submit button label
 */

$endpoint    = $endpoint    ?? '';
$method      = $method      ?? 'POST';
$redirectUrl = $redirectUrl ?? null;
$submitLabel = $submitLabel ?? 'Simpan';
$redirectJs  = $redirectUrl !== null ? "'" . esc($redirectUrl, 'js') . "'" : 'null';
?>
<form x-data="formHandler('<?= esc($endpoint) ?>', '<?= esc($method) ?>', <?= $redirectJs ?>)"
      @submit.prevent="submit(Object.fromEntries(new FormData($el)))"
      novalidate>

  <!-- Field content slot — injected by the page that includes this partial -->
  <?= $this->renderSection('form_fields') ?>

  <!-- Global form error (for non-field errors) -->
  <template x-if="errors._form">
    <p class="mt-2 text-sm text-red-600" x-text="errors._form" role="alert"></p>
  </template>

  <!-- Submit button -->
  <div class="mt-6 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
    <button type="submit"
            :disabled="isSubmitting"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-gray-900 text-white text-sm font-medium hover:bg-gray-700 focus:outline-none disabled:opacity-60 disabled:cursor-not-allowed transition-colors">

      <!-- Spinner while submitting -->
      <svg x-show="isSubmitting"
           class="w-4 h-4 animate-spin"
           fill="none"
           viewBox="0 0 24 24"
           aria-hidden="true">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
      </svg>

      <span x-show="isSubmitting">Menyimpan...</span>
      <span x-show="!isSubmitting"><?= esc($submitLabel) ?></span>

    </button>
  </div>

</form>
