<?php

namespace App\Services;

use App\Models\DeviceToken;
use App\Models\Notificacion;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    /**
     * Send the persisted notification to all active devices for its recipient.
     *
     * This method is intentionally best-effort: a missing or invalid FCM
     * configuration must never make notification persistence fail.
     */
    public function send(Notificacion $notificacion): void
    {
        if (!config('fcm.enabled')) {
            return;
        }

        $projectId = trim((string) config('fcm.project_id'));
        $serviceAccountPath = base_path((string) config('fcm.service_account_path'));
        $configuredCaBundle = trim((string) config('fcm.ca_bundle_path'));
        $caBundlePath = $configuredCaBundle === ''
            ? null
            : base_path($configuredCaBundle);

        if (
            $projectId === '' ||
            !is_file($serviceAccountPath) ||
            ($caBundlePath !== null && !is_file($caBundlePath))
        ) {
            Log::warning('FCM está habilitado, pero falta configuración o el certificado CA configurado.');
            return;
        }

        $tokens = DeviceToken::query()
            ->where('login', $notificacion->login)
            ->whereNull('revoked_at')
            ->pluck('token');

        if ($tokens->isEmpty()) {
            return;
        }

        foreach ($tokens as $token) {
            $this->sendToToken(
                $token,
                $notificacion,
                $projectId,
                $serviceAccountPath,
                $caBundlePath
            );
        }
    }

    private function sendToToken(
        string $token,
        Notificacion $notificacion,
        string $projectId,
        string $serviceAccountPath,
        ?string $caBundlePath
    ): void {
        try {
            $response = Http::timeout((int) config('fcm.timeout', 5))
                ->withOptions($this->httpOptions($caBundlePath))
                ->withToken($this->accessToken($serviceAccountPath, $caBundlePath))
                ->post(
                    'https://fcm.googleapis.com/v1/projects/' . rawurlencode($projectId) . '/messages:send',
                    [
                        'message' => [
                            'token' => $token,
                            'notification' => [
                                'title' => (string) $notificacion->titulo,
                                'body' => (string) ($notificacion->mensaje ?? ''),
                            ],
                            'data' => [
                                'notification_id' => (string) $notificacion->id,
                                'tipo' => (string) $notificacion->tipo,
                                'url' => (string) ($notificacion->url ?? ''),
                            ],
                            'android' => [
                                'priority' => 'HIGH',
                                'notification' => [
                                    'icon' => 'logo_notificaciones',
                                ],
                            ],
                        ],
                    ]
                );

            if (!$response->successful()) {
                Log::warning('FCM rechazó una notificación.', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                    'notificacion_id' => $notificacion->id,
                ]);
                return;
            }

            Log::info('FCM notification sent successfully.', [
                'status' => $response->status(),
                'notificacion_id' => $notificacion->id,
            ]);
        } catch (\Throwable $exception) {
            Log::warning('No se pudo enviar una notificación FCM.', [
                'notificacion_id' => $notificacion->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function accessToken(string $serviceAccountPath, ?string $caBundlePath): string
    {
        return Cache::remember('fcm_access_token', now()->addMinutes(50), function () use ($serviceAccountPath, $caBundlePath) {
            $credentials = json_decode((string) file_get_contents($serviceAccountPath), true, 512, JSON_THROW_ON_ERROR);
            $clientEmail = $credentials['client_email'] ?? '';
            $privateKey = $credentials['private_key'] ?? '';

            if ($clientEmail === '' || $privateKey === '') {
                throw new \RuntimeException('La cuenta de servicio no contiene client_email o private_key.');
            }

            $now = time();
            $header = $this->base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT'], JSON_THROW_ON_ERROR));
            $claims = $this->base64UrlEncode(json_encode([
                'iss' => $clientEmail,
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => 'https://oauth2.googleapis.com/token',
                'iat' => $now,
                'exp' => $now + 3600,
            ], JSON_THROW_ON_ERROR));
            $unsignedToken = $header . '.' . $claims;

            if (!openssl_sign($unsignedToken, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
                throw new \RuntimeException('No se pudo firmar el token de acceso de FCM.');
            }

            $oauthResponse = Http::asForm()
                ->timeout((int) config('fcm.timeout', 5))
                ->withOptions($this->httpOptions($caBundlePath))
                ->post('https://oauth2.googleapis.com/token', [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $unsignedToken . '.' . $this->base64UrlEncode($signature),
                ])
                ->throw()
                ->json();

            $accessToken = $oauthResponse['access_token'] ?? '';
            if ($accessToken === '') {
                throw new \RuntimeException('Google no devolvió un access token para FCM.');
            }

            return $accessToken;
        });
    }

    /**
     * Uses the operating system CA store by default, with a local override
     * only when the development environment requires one.
     */
    private function httpOptions(?string $caBundlePath): array
    {
        return $caBundlePath === null ? [] : ['verify' => $caBundlePath];
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}
