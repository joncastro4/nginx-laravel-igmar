<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
 
class TwoFactorController extends Controller
{
    public function setup()
    {
        $google2fa = app('pragmarx.google2fa');
        $user      = Auth::user();
 
        // Already set up — send them to the dashboard
        if ($user->google2fa_secret) {
            return redirect()->route('dashboard');
        }
 
        $secret = $google2fa->generateSecretKey();
 
        $qrUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );
 
        $writer = new Writer(
            new ImageRenderer(
                new RendererStyle(200),
                new SvgImageBackEnd()
            )
        );
 
        $qrCodeSvg = base64_encode($writer->writeString($qrUrl));
 
        session(['2fa_secret' => $secret]);
 
        return view('auth.2fa_setup', compact('qrCodeSvg', 'secret'));
    }
 
    public function enable(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);
 
        $user      = Auth::user();
        $secret    = session('2fa_secret');
        $google2fa = app('pragmarx.google2fa');
 
        if (! $google2fa->verifyKey($secret, $request->otp)) {
            return back()->with('error', 'Invalid OTP. Please try again.');
        }
 
        $user->google2fa_secret = $secret;
        $user->save();
 
        return redirect()->route('dashboard');
    }
}