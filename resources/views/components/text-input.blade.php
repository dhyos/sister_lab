@props(['disabled' => false])

{{-- Ganti semua kelas bawaan dengan kelas yang terang dan berbayangan --}}
<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'border-blue-200 bg-white text-gray-800 placeholder-gray-500 
                focus:border-blue-400 focus:ring-blue-400 
                rounded-lg shadow-sm w-full transition'
]) !!}>