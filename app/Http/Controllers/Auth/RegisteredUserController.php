<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Branch;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Configuration;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $config = Configuration::query()->select('agreement', 'whats_app')->first();
        $branches = Branch::all();
        return view('auth.register')->with(compact( 'config', 'branches'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'checkbox' => ['required'],
            'surname' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'branch' => ['required', 'string', 'max:255'],
            'login' => ['required', 'string', 'max:16', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (Str::contains($request->login, '_')){
            return redirect()->back()->with('error', 'Неверный номер, пожалуйста, перепроверьте');
        }

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'city' => $request->city,
            'login' => $request->login,
            'password' => $request->password,
            'branch' => $request->branch,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
