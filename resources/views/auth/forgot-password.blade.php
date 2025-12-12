<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-sky-50 to-white px-4">
        <div class="w-full max-w-md bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg p-8 border border-sky-100">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-semibold text-sky-700">Reset Kata Sandi</h1>
                <p class="text-sm text-sky-500">Masukkan email untuk menerima tautan reset</p>
            </div>


            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-6">
                    <x-input-label for="email" :value="__('Email')" class="text-sky-600" />
                    <x-text-input id="email" class="block mt-1 w-full border-sky-100 focus:border-sky-300 focus:ring focus:ring-sky-100" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between">
                    <a class="text-sm text-sky-600 hover:text-sky-800 underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-200" href="{{ route('login') }}">
                        {{ __('Back to login') }}
                    </a>

                    <x-primary-button class="bg-sky-600 hover:bg-sky-700 focus:ring-sky-300">
                        {{ __('Email Password Reset Link') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
