<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-sky-50 to-white px-4">
        <div class="w-full max-w-md bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg p-8 border border-sky-100">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-semibold text-sky-700">Daftar Akun</h1>
                <p class="text-sm text-sky-500">Buat akun baru untuk mengakses Sister Lab</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <x-input-label for="name" :value="__('Name')" class="text-sky-600" />
                    <x-text-input id="name" class="block mt-1 w-full border-sky-100 focus:border-sky-300 focus:ring focus:ring-sky-100" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" class="text-sky-600" />
                    <x-text-input id="email" class="block mt-1 w-full border-sky-100 focus:border-sky-300 focus:ring focus:ring-sky-100" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <x-input-label for="password" :value="__('Password')" class="text-sky-600" />

                    <x-text-input id="password" class="block mt-1 w-full border-sky-100 focus:border-sky-300 focus:ring focus:ring-sky-100"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sky-600" />

                    <x-text-input id="password_confirmation" class="block mt-1 w-full border-sky-100 focus:border-sky-300 focus:ring focus:ring-sky-100"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a class="text-sm text-sky-600 hover:text-sky-800 underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-200" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <x-primary-button class="ms-4 bg-sky-600 hover:bg-sky-700 focus:ring-sky-300">
                        {{ __('Register') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
