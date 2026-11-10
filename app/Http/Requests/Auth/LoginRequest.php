<?php
namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Support\PhoneNumber;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return ['phone'=>['required','string','max:20'],'password'=>['required','string']]; }
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();
        $phone = PhoneNumber::tryNormalize((string) $this->input('phone'));
        $local = $phone ? '0'.substr($phone,3) : null;
        $user = $phone ? User::where('phone',$local)->orWhere('phone',$phone)->first() : null;
        if (!$user || !Hash::check((string)$this->input('password'), $user->password)) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages(['phone'=>'Nomor HP atau kata sandi tidak sesuai.']);
        }
        Auth::login($user, $this->boolean('remember'));
        if (method_exists($user,'isActive') && !$user->isActive()) { Auth::guard('web')->logout(); throw ValidationException::withMessages(['phone'=>'Akun ini sudah dinonaktifkan. Hubungi admin Laundry Coin.']); }
        RateLimiter::clear($this->throttleKey());
    }
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(),5)) return;
        event(new Lockout($this)); $seconds=RateLimiter::availableIn($this->throttleKey());
        throw ValidationException::withMessages(['phone'=>trans('auth.throttle',['seconds'=>$seconds,'minutes'=>ceil($seconds/60)])]);
    }
    public function throttleKey(): string { return Str::transliterate(Str::lower((string)$this->input('phone')).'|'.$this->ip()); }
}
