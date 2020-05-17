<?php
namespace ArcHive\Dashboard;

use ArcHive\Dashboard\Actions\DashboardAction;
use TurboPancake\Module;
use TurboPancake\Renderer\RendererInterface;
use TurboPancake\Router;

class DashboardModule extends Module {

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var Router
     */
    private $router;

    public function __construct(RendererInterface $renderer, Router $router)
    {
        $this->renderer = $renderer;
        $this->router = $router;
    }

    public function load(): void
    {
        $this->renderer->addPath(__DIR__ . '/views', 'dashboard');

        $this->router->get('/', DashboardAction::class, 'dashboard.home');
    }
}