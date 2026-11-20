<?php

namespace App\Http\Controllers;

use App\Enums\AppRole;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $roles = collect(AppRole::cases())->filter(fn ($role) => $request->user()->hasRole($role->value));

        return view('dashboard', compact('roles'));
    }
}
