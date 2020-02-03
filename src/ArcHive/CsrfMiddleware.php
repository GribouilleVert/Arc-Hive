<?php
namespace ArcHive;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CsrfMiddleware extends \TurboPancake\Middlewares\CsrfMiddleware {

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (strpos($request->getUri()->getPath(), '/api') === 0) {
            return $handler->handle($request);
        }
        return parent::process($request, $handler);
    }

}