<?php
namespace ArcHive\Api\Actions;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class ApiResponseAction implements MiddlewareInterface {

    protected $responseObject = [
        'status' => 'unknown'
    ];

    /**
     * @inheritDoc
     */
    public abstract function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;


    protected function withStatus(string $status): self
    {
        $this->responseObject['status'] = $status;
        return $this;
    }

    protected function withMessage(string $message): self
    {
        $this->responseObject['message'] = $message;
        return $this;
    }

    protected function getResponse(int $httpCode = 200): ResponseInterface
    {
        $payload = json_encode($this->responseObject);
        return new Response($httpCode, [
            'Content-Type' => 'application/json'
        ], $payload);
    }

}