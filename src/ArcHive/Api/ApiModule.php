<?php
namespace ArcHive\Api;

use ArcHive\Api\Actions\Datas\CreateDataAction;
use TurboPancake\Module;
use TurboPancake\Router;

class ApiModule extends Module {

    /**
     * Dossiers pour la gestion de la base de donnée
     */
    const MIGRATIONS = __DIR__ . '/Database/mgmt/migrations';

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
        $this->router->post('/api/data/create', CreateDataAction::class, 'api.data.create');
    }
}