<?php
/**
 * Partial: currency_input.php
 *
 * Currency / price input with auto-formatting (Indonesian thousand separator — dots).
 * Uses Alpine currencyInput() component (defined in components.js).
 *
 * Accepted variables:
 * @var string $name        (required) Input name attribute
 * @var string $label       (required) Field label
 * @var string $value       (optional) Default numeric value, e.g. '1500000'
 * @var string $placeholder (optional, default '0')
 * @var string $prefix      (optional, default 'Rp')
 *
 * Usage inside a formHandler form:
 *   <?= $this->include('_partials/currency_input', [
 *       'name'  => 'price',
 *       'label' => 'Harga',
 *       'value' => '1500000',
 *   ]) ?>
 *
 * Usage with custom prefix:
 *   <?= $this->include('_partials/currency_input', [
 *       'name'   => 'budget',
 *       'label'  => 'Anggaran',
 *       'prefix' => 'Rp',
 *       'placeholder' => '0',
 *   ]) ?>
 */

$name        = $name        ?? '';
$label       = $label       ?? '';
$value       = $value       ?? '';
$placeholder = $placeholder ?? '0';
$prefix      = $prefix      ?? 'Rp';
$fieldId     = preg_replace('/[\[\]\.-]/', '_', $name);
?>
<div x-data="currencyInput('<?= esc($name) ?>', '<?= esc($value) ?>')">

  <?php if ($label): ?>
    <label for="<?= $fieldId ?>_input" class="block mb-1.5 text-sm font-medium text-gray-700">
      <?= esc($label) ?>
    </label>
  <?php endif ?>

  <!-- Hidden input — carries the raw numeric value (no formatting) for form submission -->
  <input type="hidden" :name="name" x-model="rawValue" id="<?= $fieldId ?>">

  <!-- Input group with prefix addon -->
  <div class="flex">
    <span class="inline-flex items-center px-3.5 py-2.5 text-sm font-medium border border-r-0 border-gray-300 rounded-l-lg bg-gray-50 text-gray-600 select-none"
          :class="(typeof errors !== 'undefined' && errors['<?= $name ?>']) ? 'border-red-400 bg-red-50 text-red-600' : 'border-gray-300 bg-gray-50 text-gray-600'">
      <?= esc($prefix) ?>
    </span>
    <input type="text" inputmode="numeric"
      id="<?= $fieldId ?>_input"
      :value="displayText"
      @input="onInput($event)"
      placeholder="<?= esc($placeholder) ?>"
      class="flex-1 min-w-0 px-3.5 py-2.5 text-sm text-gray-900 bg-white border rounded-r-lg outline-none focus:ring-1 transition placeholder-gray-400 border-l-0"
      :class="(typeof errors !== 'undefined' && errors['<?= $name ?>']) ? 'border-red-400 focus:ring-red-400' : 'border-gray-300 focus:ring-gray-400'"
      autocomplete="off">
  </div>

  <!-- Validation error — inherits from parent formHandler scope if available -->
  <span x-show="typeof errors !== 'undefined' && errors['<?= $name ?>']"
        x-text="errors['<?= $name ?>']"
        class="mt-1 text-xs text-red-600 block"
        role="alert">
  </span>

</div>
