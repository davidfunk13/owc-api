<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirect(Request $request)
    {
        $platform = $request->query('platform', 'web');
        $state = Str::random(40);

        // Store state -> platform mapping in cache (expires in 10 minutes)
        Cache::put("oauth_state:{$state}", $platform, now()->addMinutes(10));

        return Socialite::driver('battlenet')
            ->scopes(['openid'])
            ->with(['state' => $state])
            ->redirect();
    }

    public function callback(Request $request)
    {
        $state = $request->query('state');

        if (!$state) {
            return $this->redirectWithError('web', 'missing_state');
        }

        // Pull removes the key after retrieving (single-use)
        $platform = Cache::pull("oauth_state:{$state}");

        if (!$platform) {
            return $this->redirectWithError('web', 'invalid_state');
        }

        try {
            $battlenetUser = Socialite::driver('battlenet')->stateless()->user();

            $raw = $battlenetUser->getRaw();

            $user = User::updateOrCreate(
                ['battlenet_id' => $battlenetUser->getId()],
                [
                    'sub' => $raw['sub'],
                    'battletag' => $raw['battletag'],
                ]
            );

            $token = $user->createToken('app')->plainTextToken;

            $redirectUrl = $platform === 'web'
                ? config('services.auth.redirect_web')
                : config('services.auth.redirect_mobile');

            return redirect($redirectUrl . '?token=' . $token);

        } catch (\Exception $e) {
            \Log::error('OAuth callback failed', [
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'platform' => $platform,
            ]);

            return $this->redirectWithError($platform, 'auth_failed');
        }
    }

    private function redirectWithError(string $platform, string $error)
    {
        $redirectUrl = $platform === 'web'
            ? config('services.auth.redirect_web')
            : config('services.auth.redirect_mobile');

        return redirect($redirectUrl . '?error=' . $error);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
