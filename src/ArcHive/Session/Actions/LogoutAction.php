<?php
namespace ArcHive\Session\Actions;

use ArcHive\Session\OAuth;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TurboPancake\Router;
use TurboPancake\Router\RouterAware;
use TurboPancake\Services\Session\SessionInterface;

class LogoutAction implements MiddlewareInterface {

    /**
     * @var Router
     */
    private $router;

    /**
     * @var SessionInterface
     */
    private $session;

    use RouterAware;

    public function __construct(Router $router, SessionInterface $session)
    {
        $this->router = $router;
        $this->session = $session;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->session->delete('auth.token');
        return $this->temporaryRedirect('auth.login');
    }

}