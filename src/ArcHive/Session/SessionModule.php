<?php
namespace ArcHive\Session;

use ArcHive\Session\Actions\LoginAction;
use ArcHive\Session\Actions\LogoutAction;
use ArcHive\Session\Actions\OAuthAction;
use TurboPancake\Module;
use TurboPancake\Renderer\RendererInterface;
use TurboPancake\Router;

class SessionModule extends Module {

    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function load(): void
    {
        $this->router->get('/login', LoginAction::class, 'auth.login');
        $this->router->get('/oauth/edu-focus', OAuthAction::class, 'auth.oauth');
        $this->router->get('/logout', LogoutAction::class, 'auth.logout');
    }

}