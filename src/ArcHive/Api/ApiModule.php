<?php
namespace ArcHive\Api;

use ArcHive\Api\Actions\Datas\CreateDataAction;
use ArcHive\Api\Database\Tables\ReportsTable;
use TurboPancake\Module;
use TurboPancake\Router;
use TurboPancake\Services\Session\SessionInterface;

class ApiModule extends Module {

    /**
     * Dossiers pour la gestion de la base de donnée
     */
    const MIGRATIONS = __DIR__ . '/Database/mgmt/migrations';
    const SEEDS = __DIR__ . '/Database/mgmt/seeds';

    /**
     * @var Router
     */
    private $router;

    /**
     * @var SessionInterface
     */
    private $session;

    use Router\RouterAware;

    public function __construct(Router $router, SessionInterface $session)
    {
        $this->router = $router;
        $this->session = $session;
    }

    public function load(): void
    {

        $this->router->post('/api/data/create', CreateDataAction::class, 'api.data.create');
    }
}