<?php
/**
 * Partial: badge.php
 *
 * Status badge with color + text label.
 * Color is NEVER the sole indicator — a text label is always present.
 *
 * Tailwind classes are written literally (not string interpolated) so they
 * are not purged by the Tailwind CDN.
 *
 * Accepted variables:
 * @var string $label (required)
 * @var string $color (required) 'green' | 'red' | 'yellow' | 'blue' | 'gray'
 */

$color ??= 'gray';
?>
<?php
$colorStyles = [
    'green'  => 'background-color:#dcfce7;color:#166534',
    'red'    => 'background-color:#fee2e2;color:#991b1b',
    'yellow' => 'background-color:#fef9c3;color:#92400e',
    'blue'   => 'background-color:#dbeafe;color:#1e40af',
    'gray'   => 'background-color:#f3f4f6;color:#1f2937',
];
$style = $colorStyles[$color] ?? $colorStyles['gray'];
?>
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="<?= $style ?>">
  <?= esc($label ?? '') ?>
</span>
