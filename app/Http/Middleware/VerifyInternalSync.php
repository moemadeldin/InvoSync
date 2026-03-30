<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Utils\APIResponder;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class VerifyInternalSync
{
    use APIResponder;

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-Sync-Token');

        if (! $token || $token !== config('app.internal_sync_token')) {
            return $this->fail('Unauthorized Handshake', Response::HTTP_UNAUTHORIZED);
        }

        /** @var Response $result */
        $result = $next($request);

        return $result;
    }
}
