<?php

namespace Shucream0117\SlimBoost\Controllers;

use Shucream0117\SlimBoost\Constants\HttpStatusCode;
use Slim\Http\Response;

/**
 * HTMLを表示する場合に使用するコントローラの基底
 *
 * Class HtmlControllerBase
 * @package Shucream0117\SlimBoost\Controllers
 */
abstract class HtmlControllerBase extends ControllerBase
{
    /**
     * example: "twig", "smarty"
     * @return string
     */
    abstract protected static function getTemplateExtension(): string;

    private function render(Response $response, string $templateFileName, array $args): Response
    {
        $templateFileName = self::completeFileName($templateFileName);
        return $this->app['renderer']->render($response, $templateFileName, $args);
    }

    private static function completeFileName(string $fileName): string
    {
        $ext = static::getTemplateExtension();
        if (preg_match("/\.{$ext}$/", $fileName)) {
            return $fileName;
        }
        return "{$fileName}.{$ext}";
    }

    /**
     * 200 OK
     * @param string $templateFileName
     * @param array $args
     * @return Response
     */
    protected function ok(string $templateFileName, array $args = []): Response
    {
        $res = $this->getResponseObject(HttpStatusCode::OK);
        return $this->render($res, $templateFileName, $args);
    }

    /**
     * 400 Bad Request
     * @param string $templateFileName
     * @param array $args
     * @return Response
     */
    protected function badRequest(string $templateFileName, array $args = []): Response
    {
        $res = $this->getResponseObject(HttpStatusCode::BAD_REQUEST);
        return $this->render($res, $templateFileName, $args);
    }

    /**
     * 401 Unauthorized
     * @param string $templateFileName
     * @param array $args
     * @return Response
     */
    protected function unauthorized(string $templateFileName, array $args = []): Response
    {
        $res = $this->getResponseObject(HttpStatusCode::UNAUTHORIZED);
        return $this->render($res, $templateFileName, $args);
    }

    /**
     * 403 Forbidden
     * @param string $templateFileName
     * @param array $args
     * @return Response
     */
    protected function forbidden(string $templateFileName, array $args = []): Response
    {
        $res = $this->getResponseObject(HttpStatusCode::FORBIDDEN);
        return $this->render($res, $templateFileName, $args);
    }

    /**
     * 404 Not Found
     * @param string $templateFileName
     * @param array $args
     * @return Response
     */
    protected function notFound(string $templateFileName, array $args = []): Response
    {
        $res = $this->getResponseObject(HttpStatusCode::NOT_FOUND);
        return $this->render($res, $templateFileName, $args);
    }

    /**
     * 500 Internal Server Error
     * @param string $templateFileName
     * @param array $args
     * @return Response
     */
    protected function internalServerError(string $templateFileName, array $args = []): Response
    {
        $res = $this->getResponseObject(HttpStatusCode::INTERNAL_SERVER_ERROR);
        return $this->render($res, $templateFileName, $args);
    }

    /**
     * 503 Service Unavailable
     * @param string $templateFileName
     * @param array $args
     * @return Response
     */
    protected function serviceUnavailable(string $templateFileName, array $args = []): Response
    {
        $res = $this->getResponseObject(HttpStatusCode::SERVICE_UNAVAILABLE);
        return $this->render($res, $templateFileName, $args);
    }

    /**
     * @param int $statusCode
     * @return Response
     */
    private function getResponseObject(int $statusCode): Response
    {
        $res = new Response($statusCode);
        return $res->withHeader('Content-Type', 'text/html');
    }
}
