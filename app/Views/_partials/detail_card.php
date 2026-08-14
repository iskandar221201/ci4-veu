<?php
/**
 * Partial: detail_card.php
 *
 * Detail card with label + value for show/detail page.
 *
 * Accepted variables:
 * @var string $title  (optional) Card title
 * @var array  $fields (required) [['label' => string, 'value' => mixed], ...]
 */

$fields = $fields ?? [];
?>
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

  <!-- Card title (optional) -->
  <?php if (! empty($title)): ?>
    <div class="px-6 py-4 border-b border-gray-100">
      <h2 class="text-base font-semibold text-gray-900">
        <?= esc($title) ?>
      </h2>
    </div>
  <?php endif ?>

  <!-- Label-value grid -->
  <dl class="divide-y divide-gray-100">
    <?php foreach ($fields as $field): ?>
      <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-4">

        <dt class="text-sm font-medium text-gray-500 flex items-start">
          <?= esc($field['label'] ?? '') ?>
        </dt>

        <dd class="text-sm text-gray-900 col-span-1 sm:col-span-2 break-words">
          <?php
          $value = $field['value'] ?? null;
          if ($value === null || $value === '') {
              echo '<span class="text-gray-400 italic">—</span>';
          } else {
              echo esc((string) $value);
          }
          ?>
        </dd>

      </div>
    <?php endforeach ?>

    <?php if (empty($fields)): ?>
      <div class="px-6 py-4 text-sm text-gray-400 italic">
        Tidak ada data.
      </div>
    <?php endif ?>
  </dl>

</div>
