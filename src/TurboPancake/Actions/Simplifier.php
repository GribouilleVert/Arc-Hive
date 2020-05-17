<?php
namespace TurboPancake\Actions;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use SimpleXMLElement;

trait Simplifier {

    protected function stringResponse($page, $code = 200): ResponseInterface
    {
        return new Response($code, [], $page);
    }

    protected function xmlResponse(SimpleXMLElement $page, $code = 200): ResponseInterface
    {
        return new Response(
            $code,
            ['Content-Type' => 'application/xml'],
            $page->asXML()
        );
    }


    protected function jsonResponse($object, int $code = 200): ResponseInterface
    {
        return new Response(
            $code,
            ['Content-Type' => 'application/json'],
            json_encode($object)
        );
    }
}
