<?= $this->extend('_layouts/main') ?>

<?= $this->section('content') ?>

<?= $this->include('_partials/page_header') ?>

<div x-data="dataTable('/api/users')">

    <div class="mb-4">
        <?= $this->include('_partials/search_bar', ['placeholder' => 'Cari nama atau email...']) ?>
    </div>

    <?php $this->setData([
        'columns' => [
            ['key' => 'username', 'label' => 'Nama'],
            ['key' => 'email', 'label' => 'Email'],
        ],
        'actions' => [
            ['label' => 'Detail', 'url' => "'/users/' + row.id"],
        ],
    ], 'raw') ?>
    <?= $this->include('_partials/datatable') ?>

</div>
<?= $this->endSection() ?>
