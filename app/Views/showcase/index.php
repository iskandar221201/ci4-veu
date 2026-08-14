<?= $this->extend('_layouts/main') ?>

<?= $this->section('content') ?>

<?= $this->include('_partials/page_header') ?>

<?php
/**
 * Helper: renders a dark code block with escaped PHP.
 * @param string $raw  Raw PHP source (literal, unescaped)
 */
$codeBlock = function (string $raw): string {
    return '<div class="bg-gray-900 rounded-lg overflow-hidden border border-gray-800">
        <div class="flex items-center justify-between px-4 py-2 bg-gray-800 border-b border-gray-700">
            <span class="text-xs font-medium text-gray-400">PHP</span>
        </div>
        <pre class="p-4 overflow-x-auto text-sm text-gray-300 font-mono leading-relaxed"><code>' . esc($raw) . '</code></pre>
    </div>';
};
?>

<div class="space-y-6">

  <!-- ============================================================ -->
  <!-- INTRODUCTION                                                  -->
  <!-- ============================================================ -->
  <div class="bg-white rounded-lg border border-gray-200 p-6">
    <p class="text-sm text-gray-600">
      Halaman ini menampilkan seluruh komponen UI yang tersedia di <span class="font-semibold text-gray-900">CI4 Kit</span>.
      Setiap bagian menyertakan pratinjau langsung (<em>live preview</em>) dan cuplikan kode (<em>code snippet</em>)
      yang dapat disalin langsung ke halaman view Anda.
    </p>
  </div>

  <!-- ============================================================ -->
  <!-- 1. FLASH / ALERT                                              -->
  <!-- ============================================================ -->
  <hr class="border-gray-200">

  <div>
    <h2 class="text-lg font-semibold text-gray-900 mb-1">flash / alert</h2>
    <p class="text-sm text-gray-500 mb-4">
      Menampilkan pesan <em>flash</em> dari server (session) setelah redirect.
      Sukses hijau — error merah. Auto-dismiss 4 detik.
    </p>

    <div class="bg-white rounded-lg border border-gray-200 p-6 space-y-3">
      <div x-data="{ show: true }"
           x-show="show"
           x-init="setTimeout(() => show = false, 999999)"
           class="flex items-start gap-3 p-4 rounded-lg border border-green-200 bg-green-50 text-green-800"
           role="alert">
        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="flex-1">
          <p class="text-sm font-medium">Data berhasil disimpan.</p>
        </div>
        <button type="button" @click="show = false" class="text-green-500 hover:text-green-700 focus:outline-none" aria-label="Tutup">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div x-data="{ show: true }"
           x-show="show"
           x-init="setTimeout(() => show = false, 999999)"
           class="flex items-start gap-3 p-4 rounded-lg border border-red-200 bg-red-50 text-red-800"
           role="alert">
        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
        </svg>
        <div class="flex-1">
          <p class="text-sm font-medium">Gagal menyimpan data. Silakan coba lagi.</p>
        </div>
        <button type="button" @click="show = false" class="text-red-500 hover:text-red-700 focus:outline-none" aria-label="Tutup">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    <?php $flashCode = <<<'CODE'
<?= $this->include('_partials/flash') ?>
CODE; ?>
    <?= $codeBlock($flashCode) ?>
  </div>

  <!-- ============================================================ -->
  <!-- 2. BADGE                                                      -->
  <!-- ============================================================ -->
  <hr class="border-gray-200">

  <div>
    <h2 class="text-lg font-semibold text-gray-900 mb-1">badge</h2>
    <p class="text-sm text-gray-500 mb-4">
      Status <em>pill</em> dengan kode warna. Parameter: <code class="text-gray-800 bg-gray-100 px-1 rounded">$label</code> (required),
      <code class="text-gray-800 bg-gray-100 px-1 rounded">$color</code> (green / red / yellow / blue / gray).
    </p>

    <div class="bg-white rounded-lg border border-gray-200 p-6 flex flex-wrap items-start gap-x-10 gap-y-4">
      <?php
      $badges = [
        ['color' => 'green',  'label' => 'Active'],
        ['color' => 'red',    'label' => 'Inactive'],
        ['color' => 'yellow', 'label' => 'Pending'],
        ['color' => 'blue',   'label' => 'Verified'],
        ['color' => 'gray',   'label' => 'Draft'],
      ];

      $colorMap = [
        'green'  => ['bg' => '#dcfce7', 'text' => '#166534'],
        'red'    => ['bg' => '#fee2e2', 'text' => '#991b1b'],
        'yellow' => ['bg' => '#fef9c3', 'text' => '#92400e'],
        'blue'   => ['bg' => '#dbeafe', 'text' => '#1e40af'],
        'gray'   => ['bg' => '#f3f4f6', 'text' => '#1f2937'],
      ];
      ?>
      <?php foreach ($badges as $b):
        $c = $colorMap[$b['color']] ?? $colorMap['gray'];
      ?>
      <div class="flex flex-col items-center gap-1.5">
        <span style="display:inline-flex;align-items:center;padding:2px 10px;border-radius:9999px;font-size:12px;font-weight:500;background-color:<?= $c['bg'] ?>;color:<?= $c['text'] ?>">
          <?= esc($b['label']) ?>
        </span>
        <span class="text-xs text-gray-400 font-mono"><?= esc($b['color']) ?></span>
      </div>
      <?php endforeach ?>
    </div>

    <?php $badgeCode = <<<'CODE'
<?= $this->include('_partials/badge', ['label' => 'Active', 'color' => 'green']) ?>
CODE; ?>
    <?= $codeBlock($badgeCode) ?>
  </div>

  <!-- ============================================================ -->
  <!-- 3. SEARCH_BAR                                                 -->
  <!-- ============================================================ -->
  <hr class="border-gray-200">

  <div>
    <h2 class="text-lg font-semibold text-gray-900 mb-1">search_bar</h2>
    <p class="text-sm text-gray-500 mb-4">
      Input pencarian dengan ikon kaca pembesar dan <em>debounce</em> 400ms.
      Membutuhkan <code class="text-gray-800 bg-gray-100 px-1 rounded">x-data="dataTable(endpoint)"</code> di parent.
    </p>

    <div class="bg-white rounded-lg border border-gray-200 p-6"
         x-data="{ search: '', fetch() {} }">
      <?= $this->include('_partials/search_bar', ['placeholder' => 'Cari sesuatu...']) ?>
    </div>

    <?php $searchCode = <<<'CODE'
<div x-data="dataTable('/api/users')">
    <?= $this->include('_partials/search_bar') ?>
</div>
CODE; ?>
    <?= $codeBlock($searchCode) ?>
  </div>

  <!-- ============================================================ -->
  <!-- 4. CONFIRM_DIALOG                                             -->
  <!-- ============================================================ -->
  <hr class="border-gray-200">

  <div>
    <h2 class="text-lg font-semibold text-gray-900 mb-1">confirm_dialog</h2>
    <p class="text-sm text-gray-500 mb-4">
      Modal konfirmasi untuk aksi destruktif (hapus, dll).
      Membutuhkan <code class="text-gray-800 bg-gray-100 px-1 rounded">x-data="confirmDialog()"</code> di parent.
    </p>

    <?php $this->setVar('confirmDialogMessage', 'Apakah Anda yakin ingin menghapus data ini?'); ?>

    <div class="bg-white rounded-lg border border-gray-200 p-6" x-data="confirmDialog()">
      <?= $this->include('_partials/confirm_dialog') ?>

      <button type="button"
              @click="open('Apakah Anda yakin ingin menghapus data contoh ini?', () => { alert('Dikonfirmasi!') })"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-medium hover:bg-red-700 focus:outline-none transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
        </svg>
        <span>Hapus Data Contoh</span>
      </button>
    </div>

    <?php $confirmCode = <<<'CODE'
<div x-data="confirmDialog()">
    <?= $this->include('_partials/confirm_dialog') ?>

    <button type="button"
            @click="open('Yakin ingin menghapus?', () => deleteItem(id))">
        Hapus
    </button>
</div>
CODE; ?>
    <?= $codeBlock($confirmCode) ?>
  </div>

  <!-- ============================================================ -->
  <!-- 5. EMPTY_STATE                                                -->
  <!-- ============================================================ -->
  <hr class="border-gray-200">

  <div>
    <h2 class="text-lg font-semibold text-gray-900 mb-1">empty_state</h2>
    <p class="text-sm text-gray-500 mb-4">
      Tampilan ketika data kosong. Mendukung <em>call-to-action</em> opsional.
      Parameter: <code class="text-gray-800 bg-gray-100 px-1 rounded">$message</code>,
      <code class="text-gray-800 bg-gray-100 px-1 rounded">$cta</code> (['label', 'url']).
    </p>

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
      <div class="flex flex-col items-center justify-center py-16 text-center">
        <svg class="w-20 h-20 text-gray-300 mb-4" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25-2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
        </svg>
        <p class="text-gray-500 text-sm font-medium mb-4">Belum ada data pengguna.</p>
        <a href="/users/create" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-medium hover:bg-gray-700 focus:outline-none transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          Tambah Pengguna
        </a>
      </div>
    </div>

    <?php $emptyCode = <<<'CODE'
<?= $this->include('_partials/empty_state', [
    'message' => 'Belum ada data pengguna.',
    'cta'     => ['label' => 'Tambah Pengguna', 'url' => '/users/create'],
]) ?>
CODE; ?>
    <?= $codeBlock($emptyCode) ?>
  </div>

  <!-- ============================================================ -->
  <!-- 6. LOADING_OVERLAY                                            -->
  <!-- ============================================================ -->
  <hr class="border-gray-200">

  <div>
    <h2 class="text-lg font-semibold text-gray-900 mb-1">loading_overlay</h2>
    <p class="text-sm text-gray-500 mb-4">
      <em>Overlay</em> halaman penuh dengan <em>spinner</em>. Cukup disertakan sekali di layout,
      lalu dikendalikan via <code class="text-gray-800 bg-gray-100 px-1 rounded">Alpine.$data(...)</code>.
    </p>

    <div class="bg-white rounded-lg border border-gray-200 p-6">
      <div class="flex flex-wrap items-center gap-3">
        <button type="button"
                @click="Alpine.$data(document.getElementById('loadingOverlay')).visible = true"
                class="px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-medium hover:bg-gray-700 focus:outline-none transition-colors">
          Tampilkan Overlay
        </button>
        <span class="text-xs text-gray-400">Overlay mencakup seluruh viewport — sentuh untuk mencoba.</span>
      </div>
    </div>

    <?php $loadingCode = <<<'CODE'
// Dalam layout:
<?= $this->include('_partials/loading_overlay') ?>

// Dari JavaScript mana pun:
Alpine.$data(document.getElementById('loadingOverlay')).visible = true;
Alpine.$data(document.getElementById('loadingOverlay')).visible = false;
CODE; ?>
    <?= $codeBlock($loadingCode) ?>
  </div>

  <!-- ============================================================ -->
  <!-- 7. DETAIL_CARD                                                -->
  <!-- ============================================================ -->
  <hr class="border-gray-200">

  <div>
    <h2 class="text-lg font-semibold text-gray-900 mb-1">detail_card</h2>
    <p class="text-sm text-gray-500 mb-4">
      Kartu informasi dengan pasangan label-nilai dalam grid 3 kolom.
      Parameter: <code class="text-gray-800 bg-gray-100 px-1 rounded">$title</code> (opsional),
      <code class="text-gray-800 bg-gray-100 px-1 rounded">$fields</code> (array of ['label', 'value']).
    </p>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="text-base font-semibold text-gray-900">Informasi Pengguna</h2>
      </div>
      <dl class="divide-y divide-gray-100">
        <?php
        $detailFields = [
          ['label' => 'Nama',          'value' => 'John Doe'],
          ['label' => 'Email',         'value' => 'john@example.com'],
          ['label' => 'Telepon',       'value' => '+62 812 3456 7890'],
          ['label' => 'Role',          'value' => 'Administrator'],
          ['label' => 'Tanggal Lahir', 'value' => '17 Agustus 1990'],
          ['label' => 'Catatan',       'value' => null],
        ];
        ?>
        <?php foreach ($detailFields as $df): ?>
        <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
          <dt class="text-sm font-medium text-gray-500 flex items-start"><?= esc($df['label']) ?></dt>
          <dd class="text-sm text-gray-900 col-span-1 sm:col-span-2 break-words">
            <?php if ($df['value'] === null || $df['value'] === ''): ?>
              <span class="text-gray-400 italic">—</span>
            <?php else: ?>
              <?= esc((string) $df['value']) ?>
            <?php endif ?>
          </dd>
        </div>
        <?php endforeach ?>
      </dl>
    </div>

    <?php $detailCode = <<<'CODE'
<?= $this->include('_partials/detail_card', [
    'title'  => 'Informasi Pengguna',
    'fields' => [
        ['label' => 'Nama',          'value' => $user['name']],
        ['label' => 'Email',         'value' => $user['email']],
        ['label' => 'Telepon',       'value' => $user['phone'] ?? null],
        ['label' => 'Tanggal Lahir', 'value' => $user['birth_date']],
    ],
]) ?>
CODE; ?>
    <?= $codeBlock($detailCode) ?>
  </div>

  <!-- ============================================================ -->
  <!-- 8. DATATABLE (mock)                                           -->
  <!-- ============================================================ -->
  <hr class="border-gray-200">

  <div>
    <h2 class="text-lg font-semibold text-gray-900 mb-1">datatable</h2>
    <p class="text-sm text-gray-500 mb-4">
      Tabel data dengan <em>loading skeleton</em>, <em>empty state</em>, dan paginasi.
      Membutuhkan <code class="text-gray-800 bg-gray-100 px-1 rounded">x-data="dataTable(endpoint)"</code> di parent.
      Ditampilkan di sini dengan data statis untuk pratinjau.
    </p>

    <?php
    $mockUsers = [
        ['name' => 'John Doe',       'email' => 'john@example.com',  'role' => 'Admin'],
        ['name' => 'Jane Smith',     'email' => 'jane@example.com',  'role' => 'User'],
        ['name' => 'Bob Johnson',    'email' => 'bob@example.com',   'role' => 'Editor'],
        ['name' => 'Alice Williams', 'email' => 'alice@example.com', 'role' => 'User'],
        ['name' => 'Charlie Brown',  'email' => 'charlie@example.com', 'role' => 'Admin'],
        ['name' => 'Diana Prince',   'email' => 'diana@example.com',  'role' => 'Editor'],
        ['name' => 'Edward Norton',  'email' => 'edward@example.com', 'role' => 'User'],
        ['name' => 'Fiona Green',    'email' => 'fiona@example.com',  'role' => 'Admin'],
    ];

    $roleColor = function (string $role): string {
        if ($role === 'Admin')  return 'green';
        if ($role === 'Editor') return 'blue';
        return 'gray';
    };
    ?>

    <div class="bg-white rounded-lg border border-gray-200 overflow-x-auto">
      <table style="min-width:100%;border-collapse:collapse;font-size:14px">
        <thead>
          <tr style="background:#f9fafb">
            <th style="text-align:left;padding:12px 16px;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;white-space:nowrap">Nama</th>
            <th style="text-align:left;padding:12px 16px;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;white-space:nowrap">Email</th>
            <th style="text-align:left;padding:12px 16px;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;white-space:nowrap">Role</th>
            <th style="text-align:left;padding:12px 16px;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;white-space:nowrap">Aksi</th>
          </tr>
        </thead>
        <tbody style="background:white">
          <?php foreach ($mockUsers as $u): ?>
          <tr style="border-top:1px solid #e5e7eb">
            <td style="padding:12px 16px;color:#374151;white-space:nowrap"><?= esc($u['name']) ?></td>
            <td style="padding:12px 16px;color:#374151;white-space:nowrap"><?= esc($u['email']) ?></td>
            <td style="padding:12px 16px;white-space:nowrap">
              <?php
              $rc = $roleColor($u['role']);
              $rs = $rc === 'green'  ? 'background-color:#dcfce7;color:#166534'
                  : ($rc === 'blue'   ? 'background-color:#dbeafe;color:#1e40af'
                  :                     'background-color:#f3f4f6;color:#1f2937');
              ?>
              <span style="display:inline-flex;align-items:center;padding:2px 10px;border-radius:9999px;font-size:12px;font-weight:500;<?= $rs ?>">
                <?= esc($u['role']) ?>
              </span>
            </td>
            <td style="padding:12px 16px;white-space:nowrap">
              <a href="#" style="font-size:14px;font-weight:500;color:#374151;text-decoration:underline;text-underline-offset:2px">Detail</a>
            </td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      <p class="text-sm text-gray-500 mb-3">Loading skeleton (5 baris pulsing):</p>
      <div class="space-y-3">
        <?php for ($i = 0; $i < 5; $i++): ?>
          <div class="h-10 bg-gray-200 rounded-lg animate-pulse"></div>
        <?php endfor ?>
      </div>
    </div>

    <?php $tableCode = <<<'CODE'
<div x-data="dataTable('/api/users')">
    <?= $this->include('_partials/search_bar') ?>

    <?= $this->include('_partials/datatable', [
        'columns' => [
            ['key' => 'name',  'label' => 'Nama'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'role',  'label' => 'Role'],
        ],
        'actions' => [
            ['label' => 'Detail', 'url' => "'/users/' + row.id"],
        ],
    ]) ?>
</div>
CODE; ?>
    <?= $codeBlock($tableCode) ?>
  </div>

  <!-- ============================================================ -->
  <!-- 9. DATEPICKER                                                 -->
  <!-- ============================================================ -->
  <hr class="border-gray-200">

  <div>
    <h2 class="text-lg font-semibold text-gray-900 mb-1">datepicker</h2>
    <p class="text-sm text-gray-500 mb-4">
      Pilih tanggal dengan kalender <em>overlay</em>, navigasi bulan, dan <em>click-outside dismiss</em>.
      Nilai dikirim dalam format <code class="text-gray-800 bg-gray-100 px-1 rounded">YYYY-MM-DD</code> via hidden input.
      Parameter: <code class="text-gray-800 bg-gray-100 px-1 rounded">$name</code>,
      <code class="text-gray-800 bg-gray-100 px-1 rounded">$label</code>,
      <code class="text-gray-800 bg-gray-100 px-1 rounded">$value</code> (opsional),
      <code class="text-gray-800 bg-gray-100 px-1 rounded">$placeholder</code> (opsional).
    </p>

    <div class="bg-white rounded-lg border border-gray-200 p-6 max-w-sm">
      <?= $this->include('_partials/datepicker', [
          'name'        => 'release_date',
          'label'       => 'Tanggal Rilis',
          'value'       => date('Y-m-d'),
          'placeholder' => 'Pilih tanggal',
      ]) ?>
    </div>

    <?php $datepickerCode = <<<'CODE'
<?= $this->include('_partials/datepicker', [
    'name'        => 'release_date',
    'label'       => 'Tanggal Rilis',
    'value'       => '2025-12-01',
    'placeholder' => 'Pilih tanggal',
]) ?>
CODE; ?>
    <?= $codeBlock($datepickerCode) ?>
  </div>

  <!-- ============================================================ -->
  <!-- 10. CURRENCY_INPUT                                            -->
  <!-- ============================================================ -->
  <hr class="border-gray-200">

  <div>
    <h2 class="text-lg font-semibold text-gray-900 mb-1">currency_input</h2>
    <p class="text-sm text-gray-500 mb-4">
      Input harga/uang dengan format ribuan otomatis (titik), prefix <code class="text-gray-800 bg-gray-100 px-1 rounded">Rp</code>
      sebagai <em>addon inline</em>, dan hidden input untuk nilai mentah.
      Parameter: <code class="text-gray-800 bg-gray-100 px-1 rounded">$name</code>,
      <code class="text-gray-800 bg-gray-100 px-1 rounded">$label</code>,
      <code class="text-gray-800 bg-gray-100 px-1 rounded">$value</code> (opsional),
      <code class="text-gray-800 bg-gray-100 px-1 rounded">$prefix</code> (opsional, default Rp),
      <code class="text-gray-800 bg-gray-100 px-1 rounded">$placeholder</code> (opsional).
    </p>

    <div class="bg-white rounded-lg border border-gray-200 p-6 max-w-sm">
      <?= $this->include('_partials/currency_input', [
          'name'        => 'price',
          'label'       => 'Harga',
          'value'       => '1500000',
          'placeholder' => '0',
      ]) ?>
    </div>

    <?php $currencyCode = <<<'CODE'
<?= $this->include('_partials/currency_input', [
    'name'  => 'price',
    'label' => 'Harga',
    'value' => '1500000',
]) ?>
CODE; ?>
    <?= $codeBlock($currencyCode) ?>
  </div>

  <!-- ============================================================ -->
  <!-- 11. SUBMIT_GROUP                                              -->
  <!-- ============================================================ -->
  <hr class="border-gray-200">

  <div>
    <h2 class="text-lg font-semibold text-gray-900 mb-1">submit_group</h2>
    <p class="text-sm text-gray-500 mb-4">
      Tombol submit + cancel untuk form actions. Cancel mendukung link (href) atau callback (click).
      Parameter: <code class="text-gray-800 bg-gray-100 px-1 rounded">$submitLabel</code>,
      <code class="text-gray-800 bg-gray-100 px-1 rounded">$cancelUrl</code>,
      <code class="text-gray-800 bg-gray-100 px-1 rounded">$cancelClick</code>.
    </p>

    <div class="bg-white rounded-lg border border-gray-200 p-6"
         x-data="{ isSubmitting: false, submitLabel: 'Simpan', cancelLabel: 'Batal', cancelUrl: '#' }">
      <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-1">
        <button type="submit" :disabled="isSubmitting"
            class="px-5 py-2.5 text-sm font-medium text-white bg-gray-900 hover:bg-gray-700 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
          <span x-show="isSubmitting" x-text="'Menyimpan...'"></span>
          <span x-show="!isSubmitting" x-text="submitLabel"></span>
        </button>

        <a :href="cancelUrl"
           class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition"
           x-text="cancelLabel">
        </a>
      </div>
    </div>

    <?php $submitCode = <<<'CODE'
<form x-data="formHandler('/api/users', 'POST', '/users')">
    <?= $this->include('_partials/submit_group', [
        'submitLabel' => 'Simpan',
        'cancelUrl'   => '/users',
    ]) ?>
</form>
CODE; ?>
    <?= $codeBlock($submitCode) ?>
  </div>

  <!-- Bottom spacer -->
  <div class="h-8"></div>

</div>

<?= $this->endSection() ?>
