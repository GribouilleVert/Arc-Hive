<?php
namespace ArcHive\Api\Actions\Datas;

use ArcHive\Api\Actions\ApiResponseAction;
use ArcHive\Api\Database\Tables\ApiKeysTable;
use ArcHive\Api\Database\Tables\ReportsTable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CreateDataAction extends ApiResponseAction {

    /**
     * @var ApiKeysTable
     */
    private $keysTable;

    /**
     * @var ReportsTable
     */
    private $reportsTable;

    public function __construct(ApiKeysTable $keysTable, ReportsTable $reportsTable)
    {
        $this->keysTable = $keysTable;
        $this->reportsTable = $reportsTable;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $jsonPayload = (string)$request->getBody();
        $data = json_decode($jsonPayload);

        $key = $request->getQueryParams()['key'] ?? '';

        if (!$this->keysTable->checkKey($key)) {
            $this->withStatus('error')
                ->withMessage('Invalid key');
        } elseif (
            $data !== null
            AND isset($data->dht)
            AND isset($data->dht->indoor)
            AND isset($data->dht->indoor->humidity)
            AND isset($data->dht->indoor->temperature)
            AND isset($data->dht->outdoor)
            AND isset($data->dht->outdoor->humidity)
            AND isset($data->dht->outdoor->temperature)
            AND isset($data->presence_sensor)
        ) {
            $this->reportsTable->insert([
                'device_name' => $this->keysTable->findBy('key', $key)[0]->device_name,
                'someone_present' => (bool)$data->presence_sensor,
                'inside_temperature' => (float)$data->dht->indoor->temperature,
                'inside_humidity' => (float)$data->dht->indoor->humidity,
                'outside_temperature' => (float)$data->dht->outdoor->temperature,
                'outside_humidity' => (float)$data->dht->outdoor->humidity,
            ]);

            $this->withStatus('ok')
                ->withMessage('Registered.');
        } else {
            $this->withStatus('error')
                ->withMessage('Not well formatted json object');
        }

        return $this->getResponse();
    }
}