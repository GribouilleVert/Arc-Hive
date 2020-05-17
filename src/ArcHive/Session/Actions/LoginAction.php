<?php
namespace ArcHive\Session\Actions;

use ArcHive\Session\OAuth;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TurboPancake\Router\RouterAware;

class LoginAction implements MiddlewareInterface {

    use RouterAware;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->directTemporaryRedirect(OAuth::makeOAuthUrl());
    }

}