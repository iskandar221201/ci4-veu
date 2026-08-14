<?php
/**
 * Partial: datepicker.php
 *
 * Custom date picker with calendar overlay, month navigation, and click-outside dismiss.
 * Uses Alpine datepicker() component (defined in components.js).
 *
 * Accepted variables:
 * @var string $name        (required) Input name attribute
 * @var string $label       (required) Field label
 * @var string $value       (optional) Default date in YYYY-MM-DD format
 * @var string $placeholder (optional, default 'Pilih tanggal')
 *
 * Usage inside a formHandler form:
 *   <?= $this->include('_partials/datepicker', [
 *       'name'  => 'birth_date',
 *       'label' => 'Tanggal Lahir',
 *       'value' => '1990-01-15',
 *   ]) ?>
 *
 * Usage standalone (or merged with other x-data):
 *   <div x-data="{ ...datepicker('birth_date', '1990-01-15') }">
 *     <?= $this->include('_partials/datepicker', [
 *         'name'  => 'birth_date',
 *         'label' => 'Tanggal Lahir',
 *     ]) ?>
 *   </div>
 */

$name        = $name        ?? '';
$label       = $label       ?? '';
$value       = $value       ?? '';
$placeholder = $placeholder ?? 'Pilih tanggal';
$fieldId     = preg_replace('/[\[\]\.-]/', '_', $name);
?>
<div x-data="datepicker('<?= esc($name) ?>', '<?= esc($value) ?>')"
     @click.outside="closeCalendar()"
     class="relative">

  <?php if ($label): ?>
    <label for="<?= $fieldId ?>_trigger" class="block mb-1.5 text-sm font-medium text-gray-700">
      <?= esc($label) ?>
    </label>
  <?php endif ?>

  <!-- Hidden input — carries the selected value during form submission -->
  <input type="hidden" :name="name" x-model="selectedValue" id="<?= $fieldId ?>">

  <!-- Trigger field + calendar icon -->
  <div class="relative">
    <input type="text" readonly
      id="<?= $fieldId ?>_trigger"
      x-model="displayText"
      @click.stop="open = !open"
      @keydown.enter.prevent="open = !open"
      @keydown.space.prevent="open = !open"
      placeholder="<?= esc($placeholder) ?>"
      class="w-full px-3.5 py-2.5 pr-10 text-sm text-gray-900 bg-white border rounded-lg outline-none focus:ring-1 transition placeholder-gray-400 cursor-pointer"
      :class="(typeof errors !== 'undefined' && errors['<?= $name ?>']) ? 'border-red-400 focus:ring-red-400' : 'border-gray-300 focus:ring-gray-400'"
      autocomplete="off">

    <!-- Calendar icon -->
    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none" aria-hidden="true">
      <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
      </svg>
    </div>

    <!-- Calendar overlay -->
    <div x-show="open"
         x-transition:enter="ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @keydown.escape.window="open = false"
         class="absolute top-full left-0 mt-1.5 bg-white border border-gray-200 rounded-xl shadow-xl z-50 p-4 w-72 origin-top-left">

      <!-- Month / Year header with prev / next -->
      <div class="flex items-center justify-between mb-3">
        <button type="button"
                @click="prevMonth()"
                class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-600 transition-colors focus:outline-none focus:ring-1 focus:ring-gray-400"
                aria-label="Bulan sebelumnya">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
        </button>
        <span class="text-sm font-semibold text-gray-900 select-none" x-text="months[currentMonth] + ' ' + currentYear"></span>
        <button type="button"
                @click="nextMonth()"
                class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-600 transition-colors focus:outline-none focus:ring-1 focus:ring-gray-400"
                aria-label="Bulan berikutnya">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        </button>
      </div>

      <!-- Day-of-week header row -->
      <div class="grid grid-cols-7 gap-0.5 mb-1">
        <template x-for="day in daysOfWeek" :key="day">
          <div class="text-center text-xs font-medium text-gray-500 py-1.5 w-9 mx-auto" x-text="day"></div>
        </template>
      </div>

      <!-- Calendar day grid -->
      <div class="grid grid-cols-7 gap-0.5">
        <template x-for="(day, index) in calendarDays()" :key="index">
          <template x-if="day !== null">
            <button type="button"
                    @click="selectDate(day)"
                    :class="{
                      'bg-gray-900 text-white hover:bg-gray-800': isSelected(day),
                      'bg-gray-100': isToday(day) && !isSelected(day),
                      'hover:bg-gray-100 text-gray-900': !isSelected(day)
                    }"
                    class="w-9 h-9 rounded-lg text-sm flex items-center justify-center transition-colors focus:outline-none focus:ring-1 focus:ring-gray-400"
                    x-text="day">
            </button>
          </template>
          <template x-if="day === null">
            <div class="w-9 h-9"></div>
          </template>
        </template>
      </div>
    </div>
  </div>

  <!-- Validation error — inherits from parent formHandler scope if available -->
  <span x-show="typeof errors !== 'undefined' && errors['<?= $name ?>']"
        x-text="errors['<?= $name ?>']"
        class="mt-1 text-xs text-red-600 block"
        role="alert">
  </span>

</div>
