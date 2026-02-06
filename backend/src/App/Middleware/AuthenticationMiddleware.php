<?php

namespace App\Middleware;

use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Session\SessionInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthenticationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = $request->getAttribute(SessionInterface::class);

        if ($request->getUri()->getPath() === "/api/login") {
            return $handler->handle($request);
        }

        if (!$session->has('user_id')) {
            return $this->responseFactory->createResponse(401);
        }

        if (!$session->has('experation_timestamp')) {
            return $this->responseFactory->createResponse(401);
        }

        $experation = $session->get('experation_timestamp');

        if (time() > $experation) {
            $session->clear();
            return $this->responseFactory->createResponse(401);
        }
        return $handler->handle($request);

        return $this->responseFactory->createResponse(401);
    }
}