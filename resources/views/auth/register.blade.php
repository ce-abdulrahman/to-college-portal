{{-- resources/views/auth/register.blade.php --}}
<x-guest-layout>
    <div class="mx-auto mt-10 w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
        <h1 class="mb-6 text-center text-2xl font-semibold">Create Account</h1>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Name (optional) --}}
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            {{-- Code (integer) --}}
            <div class="mt-4">
                <x-input-label for="code" :value="__('Code')" />
                <x-text-input id="code" name="code" type="number" class="mt-1 block w-full" :value="old('code')" required autofocus />
                <x-input-error :messages="$errors->get('code')" class="mt-2" />
            </div>

            {{-- Password --}}
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            {{-- Confirm Password --}}
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required autocomplete="new-password" />
            </div>

            {{-- Role (optional: show only if you want admins to set it here) --}}
            {{-- If you want public register disabled, remove this and set default in controller --}}
            <div class="mt-4">
                <x-input-label for="role" :value="__('Role')" />
                <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300">
                    <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-primary-button class="w-full justify-center">
                    {{ __('Register') }}
                </x-primary-button>
            </div>

            <p class="mt-4 text-center text-sm text-gray-600">
                {{ __('Already registered?') }}
                <a class="text-indigo-600 underline hover:text-indigo-800" href="{{ route('login') }}">
                    {{ __('Log in') }}
                </a>
            </p>
        </form>
    </div>
</x-guest-layout>
