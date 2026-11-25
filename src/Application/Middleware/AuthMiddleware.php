<?php

namespace App\Application\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class AuthMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $loggedInTest = false;

        if ($loggedInTest) {
            $response = $handler->handle($request);
            echo "User authorized.";
            return $response;
        } else {
            $response = new Response();
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
    }
}
