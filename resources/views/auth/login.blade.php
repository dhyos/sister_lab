<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-sky-50 to-white px-4">
        <div class="w-full max-w-md bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg p-8 border border-sky-100">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-semibold text-sky-700">Selamat Datang</h1>
                <p class="text-sm text-sky-500">Masuk untuk melanjutkan ke Sister Lab</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" class="text-sky-600" />
                    <x-text-input id="email" class="block mt-1 w-full border-sky-100 focus:border-sky-300 focus:ring focus:ring-sky-100" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <x-input-label for="password" :value="__('Password')" class="text-sky-600" />

                    <x-text-input id="password" class="block mt-1 w-full border-sky-100 focus:border-sky-300 focus:ring focus:ring-sky-100"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center gap-2 mb-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-sky-200 text-sky-600 shadow-sm focus:ring-sky-300" name="remember">
                        <span class="ms-2 text-sm text-sky-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-between mt-6">
                    <div>
                        @if (Route::has('password.request'))
                            <a class="text-sm text-sky-600 hover:text-sky-800 underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-200" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>

                    <div>
                        <x-primary-button class="ms-3 bg-sky-600 hover:bg-sky-700 focus:ring-sky-300">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
