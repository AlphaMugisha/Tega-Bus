<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebhookSignatureMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $signature = $request->header('X-Momo-Signature');
        $secret = config('services.mtn_momo.webhook_secret');

        if (!$signature || !$secret) {
            return response()->json(['message' => 'Unauthorized signature'], 401);
        }

        $computedSignature = hash_hmac('sha256', $request->getContent(), $secret);

        if (!hash_equals($signature, $computedSignature)) {
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        return $next($request);
    }
}
