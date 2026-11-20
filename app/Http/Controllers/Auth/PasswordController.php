<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Services\AccountService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasswordController extends Controller
{
    public function edit(Request $request): View
    {
        return view('auth.password', ['forced' => $request->user()->must_change_password]);
    }

    public function update(ChangePasswordRequest $request, AccountService $accounts): RedirectResponse
    {
        $accounts->changePassword($request->user(), $request->validated('password'), $request->session()->getId(), $request->validated('current_password'));
        $request->session()->regenerate();
        $request->session()->put('password_hash_web', $request->user()->getAuthPassword());
        $request->session()->forget('url.intended');

        return redirect()->route('dashboard')->with('status', 'Password berhasil diperbarui.');
    }
}
