<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 font-medium">
        Your account requires two-factor authentication.
        Scan the QR code below with the Google Authenticator app,
        then enter the 6-digit code to activate it.
    </div>
 
    <p class="mb-2 text-xs text-gray-500">
        Can't scan? Enter this code manually in the app:
        <strong class="font-mono tracking-widest">{{ $secret }}</strong>
    </p>
 
    <div class="flex justify-center my-4">
        <img src="data:image/svg+xml;base64,{{ $qrCodeSvg }}" alt="2FA QR Code">
    </div>
 
    @if (session('error'))
        <p class="mb-3 text-sm text-red-600">{{ session('error') }}</p>
    @endif
 
    <form method="POST" action="{{ route('2fa.enable') }}">
        @csrf
        <div>
            <x-input-label for="otp" :value="__('Enter the 6-digit code to confirm')" />
            <x-text-input
                id="otp"
                class="block mt-1 w-full"
                type="text"
                name="otp"
                inputmode="numeric"
                autocomplete="one-time-code"
                autofocus
                required />
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>
 
        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Activate 2FA & Continue') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>