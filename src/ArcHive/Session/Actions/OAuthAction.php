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

class OAuthAction implements MiddlewareInterface {

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
        $code = $request->getQueryParams()['code']??null;
        if ($code === null) {
            return $this->temporaryRedirect('auth.login');
        }

        $tokens = OAuth::exchangeCode($code);
        if ($tokens === false) {
            return $this->temporaryRedirect('auth.login');
        }

        [$accessToken, $refreshToken] = $tokens;
        $this->session['auth.token'] = $accessToken;

        return $this->temporaryRedirect('dashboard.home');
    }

}