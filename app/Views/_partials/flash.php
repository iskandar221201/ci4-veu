<?php
/**
 * Partial: flash.php
 *
 * Displays server-side session flash messages from CI4.
 * Auto-dismiss after 4 seconds via Alpine.
 *
 * Flash sources:
 * - session()->getFlashdata('success') → green alert
 * - session()->getFlashdata('error')   → red alert
 *
 * Note: Alpine-triggered toast after API call is handled by errorHandler (error_toast.php).
 * This partial is only for flashes coming from server-side redirects.
 *
 * Variables: none — reads directly from session flash.
 */

$flashSuccess = session()->getFlashdata('success');
$flashError   = session()->getFlashdata('error');
?>

<?php if ($flashSuccess): ?>
  <div x-data="{ show: true }"
       x-show="show"
       x-init="setTimeout(() => show = false, 4000)"
       x-transition:enter="ease-out duration-300"
       x-transition:enter-start="opacity-0 -translate-y-2"
       x-transition:enter-end="opacity-100 translate-y-0"
       x-transition:leave="ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0"
       class="flex items-start gap-3 p-4 mb-4 rounded-lg border border-green-200 bg-green-50 text-green-800"
       role="alert">

    <!-- Success icon -->
    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>

    <div class="flex-1">
      <p class="text-sm font-medium">
        <?= esc($flashSuccess) ?>
      </p>
    </div>

    <!-- Close button -->
    <button type="button"
            @click="show = false"
            class="text-green-500 hover:text-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 rounded"
            aria-label="Tutup notifikasi">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>

  </div>
<?php endif ?>

<?php if ($flashError): ?>
  <div x-data="{ show: true }"
       x-show="show"
       x-init="setTimeout(() => show = false, 4000)"
       x-transition:enter="ease-out duration-300"
       x-transition:enter-start="opacity-0 -translate-y-2"
       x-transition:enter-end="opacity-100 translate-y-0"
       x-transition:leave="ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0"
       class="flex items-start gap-3 p-4 mb-4 rounded-lg border border-red-200 bg-red-50 text-red-800"
       role="alert">

    <!-- Error icon -->
    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
    </svg>

    <div class="flex-1">
      <p class="text-sm font-medium">
        <?= esc($flashError) ?>
      </p>
    </div>

    <!-- Close button -->
    <button type="button"
            @click="show = false"
            class="text-red-500 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 rounded"
            aria-label="Tutup notifikasi">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>

  </div>
<?php endif ?>
