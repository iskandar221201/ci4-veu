<?php
/**
 * Partial: submit_group.php
 *
 * Submit + Cancel button group untuk form actions.
 * Cancel mendukung dua mode: link (href) atau callback (click).
 *
 * Accepted variables:
 * @var string $submitLabel        (optional, default 'Simpan')
 * @var string $submitLoadingLabel (optional, default 'Menyimpan...')
 * @var string $cancelLabel        (optional, default 'Batal')
 * @var string|null $cancelUrl     (optional) Cancel sebagai <a href="...">
 * @var string|null $cancelClick   (optional) Cancel sebagai <button @click="...">
 */
$submitLabel        = $submitLabel        ?? 'Simpan';
$submitLoadingLabel = $submitLoadingLabel ?? 'Menyimpan...';
$cancelLabel        = $cancelLabel        ?? 'Batal';
$cancelUrl          = $cancelUrl          ?? null;
$cancelClick        = $cancelClick        ?? null;
?>
<div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-1">
    <button type="submit" :disabled="isSubmitting"
        class="px-5 py-2.5 text-sm font-medium text-white bg-gray-900 hover:bg-gray-700 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
        <span x-show="isSubmitting"><?= esc($submitLoadingLabel) ?></span>
        <span x-show="!isSubmitting"><?= esc($submitLabel) ?></span>
    </button>

    <?php if ($cancelUrl): ?>
        <a href="<?= esc($cancelUrl) ?>"
           class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
            <?= esc($cancelLabel) ?>
        </a>
    <?php elseif ($cancelClick): ?>
        <button type="button"
            @click="<?= esc($cancelClick, 'raw') ?>"
            class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
            <?= esc($cancelLabel) ?>
        </button>
    <?php endif ?>
</div>
