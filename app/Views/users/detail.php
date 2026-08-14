<?= $this->extend('_layouts/main') ?>

<?= $this->section('content') ?>

<?= $this->include('_partials/page_header') ?>

<div x-data="{
    ...detailFetcher('/api/users/<?= esc($id) ?>'),
    editMode: false,
    user: { username: '', email: '' },
    errors: {},
    isSubmitting: false,
    async submitForm(data) {
        this.isSubmitting = true;
        this.errors = {};
        try {
            await api.request('PUT', '/api/users/<?= esc($id) ?>', data);
        } catch (err) {
            if (err && err.errors) {
                this.errors = err.errors;
            }
        } finally {
            this.isSubmitting = false;
        }
    }
}"
     x-init="init().then(() => { user.username = data.username; user.email = data.email })">

    <!-- Loading -->
    <div x-show="loading" class="mt-6 text-sm text-gray-400">Memuat data...</div>

    <div x-show="!loading" class="mt-6 space-y-4" style="display: none;">

        <!-- View Mode -->
        <div x-show="!editMode" class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-900">Informasi User</h3>
                <button @click="editMode = true"
                    class="text-sm font-medium text-gray-500 hover:text-gray-900 underline underline-offset-2 flex-shrink-0 ml-4">
                    Edit
                </button>
            </div>
            <dl class="space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <dt class="text-sm text-gray-500">Username</dt>
                    <dd class="text-sm text-gray-900 col-span-1 sm:col-span-2" x-text="data.username || '—'"></dd>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <dt class="text-sm text-gray-500">Email</dt>
                    <dd class="text-sm text-gray-900 col-span-1 sm:col-span-2" x-text="data.email || '—'"></dd>
                </div>
            </dl>

            <!-- Danger zone -->
            <div class="mt-6 pt-4 border-t border-gray-100" x-data="confirmDialog()">
                <button type="button"
                    @click="open('Apakah Anda yakin ingin menghapus user ini?', async () => {
                        try {
                            await api.delete('/api/users/<?= esc($id) ?>');
                            window.location.href = '/users';
                        } catch (e) {
                            errorHandler.catch(e);
                        }
                    })"
                    class="text-sm text-red-600 hover:text-red-800 font-medium focus:outline-none">
                    Hapus User
                </button>
                <?= $this->include('_partials/confirm_dialog') ?>
            </div>
        </div>

        <!-- Edit Mode -->
        <div x-show="editMode" class="bg-white rounded-lg border border-gray-200 p-6" style="display: none;">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Edit User</h3>
            <form @submit.prevent="submitForm(user).then(() => {
                      if (Object.keys(errors).length === 0) {
                          data.username = user.username;
                          data.email = user.email;
                          editMode = false;
                          errorHandler.show('User berhasil diupdate', 'info');
                      }
                  })">
                <div class="space-y-5 max-w-md">
                    <div>
                        <label for="edit_username" class="block mb-1.5 text-sm font-medium text-gray-700">Username</label>
                        <input type="text" id="edit_username" x-model="user.username"
                            :class="errors.username ? 'border-red-400 focus:ring-red-400' : 'border-gray-300 focus:ring-gray-400'"
                            class="w-full px-3.5 py-2.5 text-sm text-gray-900 bg-white border rounded-lg outline-none focus:ring-1 transition"
                            required>
                        <span x-show="errors.username" x-text="errors.username" class="mt-1 text-xs text-red-600 block"></span>
                    </div>
                    <div>
                        <label for="edit_email" class="block mb-1.5 text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="edit_email" x-model="user.email"
                            :class="errors.email ? 'border-red-400 focus:ring-red-400' : 'border-gray-300 focus:ring-gray-400'"
                            class="w-full px-3.5 py-2.5 text-sm text-gray-900 bg-white border rounded-lg outline-none focus:ring-1 transition"
                            required>
                        <span x-show="errors.email" x-text="errors.email" class="mt-1 text-xs text-red-600 block"></span>
                    </div>

                    <?php $this->setVar('cancelClick', 'editMode = false; user.username = data.username; user.email = data.email'); ?>
                    <?= $this->include('_partials/submit_group') ?>
                </div>
            </form>
        </div>

    </div>
</div>
<?= $this->endSection() ?>
