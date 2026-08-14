<?= $this->extend('_layouts/auth') ?>

<?= $this->section('content') ?>
<div class="flex items-center justify-center min-h-screen bg-white">
    <div class="w-full max-w-sm px-8 py-10">

        <!-- Logo / App Name -->
        <div class="mb-8 text-center">
            <h1 class="text-2xl font-semibold text-gray-900 tracking-tight"><?= esc(env('APP_NAME', 'CI4 Kit')) ?></h1>
            <p class="mt-1 text-sm text-gray-500">Masuk ke akun Anda</p>
        </div>

        <form
            x-data="{
                ...formHandler('/api/auth/login', 'POST'),
                email: '',
                password: '',
                generalError: '',
                async doLogin() {
                    this.isSubmitting = true;
                    this.errors = {};
                    this.generalError = '';
                    try {
                        const formData = { email: this.email, password: this.password };
                        const data = await api.post('/api/auth/login', formData);
                        auth.setToken(data.data.token);
                        auth.setUsername(data.data.username || data.data.email || '');
                        window.location.href = '/dashboard';
                    } catch (err) {
                        if (err?.errors) {
                            this.errors = err.errors;
                        } else {
                            this.generalError = err?.message || 'Email atau password salah.';
                        }
                    } finally {
                        this.isSubmitting = false;
                    }
                }
            }"
            @submit.prevent="doLogin()"
            class="space-y-5"
        >
            <!-- General Error -->
            <div
                x-show="generalError"
                x-text="generalError"
                x-cloak
                class="px-4 py-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg"
            ></div>

            <!-- Email -->
            <div>
                <label for="email" class="block mb-1.5 text-sm font-medium text-gray-700">Email</label>
                <input
                    type="email"
                    id="email"
                    x-model="email"
                    placeholder="nama@email.com"
                    :class="errors.email ? 'border-red-400 focus:ring-red-400 focus:border-red-400' : 'border-gray-300 focus:ring-gray-400 focus:border-gray-400'"
                    class="w-full px-3.5 py-2.5 text-sm text-gray-900 bg-white border rounded-lg outline-none focus:ring-1 transition placeholder-gray-400"
                    required
                >
                <span x-show="errors.email" x-text="errors.email" class="mt-1 text-xs text-red-600 block"></span>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block mb-1.5 text-sm font-medium text-gray-700">Password</label>
                <input
                    type="password"
                    id="password"
                    x-model="password"
                    placeholder="••••••••"
                    :class="errors.password ? 'border-red-400 focus:ring-red-400 focus:border-red-400' : 'border-gray-300 focus:ring-gray-400 focus:border-gray-400'"
                    class="w-full px-3.5 py-2.5 text-sm text-gray-900 bg-white border rounded-lg outline-none focus:ring-1 transition placeholder-gray-400"
                    required
                >
                <span x-show="errors.password" x-text="errors.password" class="mt-1 text-xs text-red-600 block"></span>
            </div>

            <!-- Submit -->
            <button
                type="submit"
                :disabled="isSubmitting"
                class="w-full py-2.5 text-sm font-medium text-white bg-gray-900 hover:bg-gray-700 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span x-show="isSubmitting">Memproses...</span>
                <span x-show="!isSubmitting">Masuk</span>
            </button>
        </form>

    </div>
</div>
<?= $this->endSection() ?>
