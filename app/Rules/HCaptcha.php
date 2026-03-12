<?php
 
namespace App\Rules;
 
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
 
class HCaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            $fail('Please complete the CAPTCHA.');
            return;
        }
 
        $response = Http::asForm()->post(
            'https://hcaptcha.com/siteverify',
            [
                'secret'   => config('services.hcaptcha.secret_key'),
                'response' => $value,
                'remoteip' => request()->ip(),
            ]
        );
 
        $body = $response->json();
 
        if (! $response->successful() || ! ($body['success'] ?? false)) {
            $fail('CAPTCHA verification failed. Please try again.');
        }
    }
}