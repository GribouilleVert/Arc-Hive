<?php
namespace ArcHive\Api\Actions\Datas;

use ArcHive\Api\Actions\ApiResponseAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CreateDataAction extends ApiResponseAction {

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $jsonPayload = (string)$request->getBody();
        $data = json_decode($jsonPayload);

        $key = $request->getQueryParams()['key'] ?? '';

        if (true) {
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

        } else {
            $this->withStatus('error')
                ->withMessage('Not well formatted json object');
        }

        return $this->getResponse();
    }
}