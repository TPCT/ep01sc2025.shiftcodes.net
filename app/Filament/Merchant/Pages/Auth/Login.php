<?php

namespace App\Filament\Merchant\Pages\Auth;

use App\Models\Admin;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Notifications\Notification;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

class Login extends \Filament\Pages\Auth\Login
{

    public function authenticate(): ?LoginResponse
    {
        try {
            $data = $this->form->getState();
            $login_data = $this->getCredentialsFromFormData($data);

            if (Admin::whereEmail($login_data['email'])->first()?->banned)
                throw new AuthenticationException(__("Please Contact Super Admin."));

            $response = parent::authenticate();
            $user = Filament::auth()->user();

            if (!$user)
                $this->throwFailureValidationException();

            $user->ip_address = \Request::ip();
            $user->last_logged_at = Carbon::now();
            $user->save();

            return $response;
        }catch (ValidationException $e){
            Notification::make()
                ->title(__("Login Attempt Failed"))
                ->body(__("Either Username Or Password is Invalid"))
                ->danger()
                ->send();
            return null;
        }catch (AuthenticationException $e){
            Notification::make()
                ->title("Contact Administrator")
                ->body("Your Account Is Banned")
                ->send();
            return null;
        }
    }
}
