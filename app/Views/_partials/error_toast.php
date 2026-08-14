<?php
/**
 * Partial: error_toast.php
 *
 * Global error/warning/info toast driven by errorHandler from error.js.
 * Included ONCE in layout — active globally for all pages.
 *
 * Uses x-data="errorHandler" — Alpine uses the plain errorHandler object
 * from error.js as a reactive data source.
 *
 * Variables: none — fully driven by the errorHandler JS object.
 */
?>
<div x-data="errorHandler"
     x-show="visible"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0 translate-y-2"
     :class="{
       'bg-red-600':    type === 'error',
       'bg-yellow-500': type === 'warning',
       'bg-gray-800':   type === 'info'
     }"
     class="fixed bottom-4 right-4 left-4 sm:left-auto z-50 flex items-center gap-3 text-white px-4 py-3 rounded-lg shadow-lg max-w-sm w-full"
     role="alert"
     aria-live="assertive"
     aria-atomic="true">

  <!-- Icon according to type -->
  <div class="flex-shrink-0">

    <!-- Error icon -->
    <svg x-show="type === 'error'"
         class="w-5 h-5"
         fill="none"
         stroke="currentColor"
         stroke-width="1.5"
         viewBox="0 0 24 24"
         aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
    </svg>

    <!-- Warning icon -->
    <svg x-show="type === 'warning'"
         class="w-5 h-5"
         fill="none"
         stroke="currentColor"
         stroke-width="1.5"
         viewBox="0 0 24 24"
         aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
    </svg>

    <!-- Info icon -->
    <svg x-show="type === 'info'"
         class="w-5 h-5"
         fill="none"
         stroke="currentColor"
         stroke-width="1.5"
         viewBox="0 0 24 24"
         aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
    </svg>

  </div>

  <!-- Message -->
  <span x-text="message" class="flex-1 text-sm font-medium"></span>

  <!-- Close button -->
  <button type="button"
          @click="visible = false"
          class="flex-shrink-0 opacity-75 hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-white rounded"
          aria-label="Tutup notifikasi">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
    </svg>
  </button>

</div>
