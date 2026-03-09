<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Enter the 6-digit code from your Google Authenticator app.
        Codes refresh every 30 seconds.
    </div>
 
    <form method="POST" action="{{ route('2fa') }}">
        @csrf
 
        <div>
            <x-input-label for="one_time_password" :value="__('Authenticator Code')" />
            <x-text-input
                id="one_time_password"
                class="block mt-1 w-full"
                type="text"
                name="one_time_password"
                inputmode="numeric"
                autocomplete="one-time-code"
                autofocus
                required />
        </div>
 
        @if ($errors->any())
            <div class="mt-2 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
 
        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Verify') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>