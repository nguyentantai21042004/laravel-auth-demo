<?php

namespace App\Support;

class Jwt
{
    public static function encode(array $payload, ?string $secret = null): string
    {
        $secret = $secret ?? static::secret();
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];
        $segments = [
            static::b64(json_encode($header)),
            static::b64(json_encode($payload)),
        ];
        $signingInput = implode('.', $segments);
        $signature = hash_hmac('sha256', $signingInput, $secret, true);
        $segments[] = static::b64($signature);
        return implode('.', $segments);
    }

    public static function decode(string $token, ?string $secret = null): ?array
    {
        $secret = $secret ?? static::secret();
        $parts = explode('.', $token);
        if (count($parts) !== 3) return null;
        [$h, $p, $s] = $parts;
        $signingInput = $h . '.' . $p;
        $expected = static::b64(hash_hmac('sha256', $signingInput, $secret, true));
        if (!hash_equals($expected, $s)) return null;
        $payloadJson = static::ub64($p);
        $payload = json_decode($payloadJson, true);
        if (!is_array($payload)) return null;
        if (isset($payload['exp']) && time() >= (int)$payload['exp']) return null;
        return $payload;
    }

    private static function b64(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function ub64(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/')) ?: '';
    }

    private static function secret(): string
    {
        $secret = env('JWT_SECRET');
        if (is_string($secret) && $secret !== '') return $secret;
        $appKey = config('app.key');
        return is_string($appKey) ? $appKey : 'changeme-secret';
    }
}


