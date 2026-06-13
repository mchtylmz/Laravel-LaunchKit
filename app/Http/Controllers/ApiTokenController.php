<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiTokenController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
    {
        return view('api-tokens.index', [
            'tokens' => $request->user()->tokens()->orderBy('last_used_at', 'desc')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $token = $request->user()->createToken($request->name);

        activity()->causedBy($request->user())->log('API token oluşturuldu: '.$request->name);
        $request->user()->appNotifications()->create([
            'title' => 'API token oluşturuldu',
            'message' => "{$request->name} adında yeni bir API token oluşturuldu.",
            'type' => 'security',
            'url' => route('api-tokens.index'),
        ]);

        return redirect()->route('api-tokens.index')->with([
            'status' => 'Token oluşturuldu.',
            'token' => $token->plainTextToken,
        ]);
    }

    public function destroy(Request $request, string $tokenId): RedirectResponse
    {
        $token = $request->user()->tokens()->findOrFail($tokenId);

        activity()->causedBy($request->user())->log('API token silindi: '.$token->name);
        $request->user()->appNotifications()->create([
            'title' => 'API token silindi',
            'message' => "{$token->name} adlı API token silindi.",
            'type' => 'security',
            'url' => route('api-tokens.index'),
        ]);

        $token->delete();

        return redirect()->route('api-tokens.index')->with('status', 'Token silindi.');
    }

    public function updateLastUsed(Request $request, string $tokenId): RedirectResponse
    {
        // Called by API endpoints, handled via Sanctum middleware automatically
        return redirect()->route('api-tokens.index');
    }
}
