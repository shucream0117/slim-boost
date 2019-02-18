<?php

namespace Shucream0117\SlimBoost\Controllers;

use Dflydev\FigCookies\SetCookies;
use Dflydev\FigCookies\SetCookie;
use Dflydev\FigCookies\FigResponseCookies;
use Psr\Http\Message\ResponseInterface;
use Shucream0117\SlimBoost\Constants\HttpStatusCode;
use Slim\Http\Headers;
use Slim\Http\Response;

trait HtmlResponseTrait
{
    /**
     * example: "twig", "smarty"
     * @return string
     */
    abstract protected static function getTemplateExtension(): string;

    /**
     * @return array
     */
    abstract protected function customResponseHeaders(): array;

    /**
     * please return $app['renderer']
     */
    abstract protected function getRenderer();

    /**
     * this can be overwritten by customResponseHeaders()
     * @return array
     */
    final protected function defaultResponseHeaders(): array
    {
        return ['Content-Type' => 'text/html; charset=utf-8'];
    }

    protected function render(ResponseInterface $response, string $templateFileName, array $args): ResponseInterface
    {
        $response = $this->appendCookieToResponse($response);
        $response = $this->expireCookiesInResponse($response);
        $templateFileName = self::completeFileName($templateFileName);
        return $this->getRenderer()->render($response, $templateFileName, $args);
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
     * @var array
     */
    private $expireTargetCookieKeys = [];

    /**
     * @param string $key
     */
    protected function expireCookie(string $key): void
    {
        $this->expireTargetCookieKeys[] = $key;
    }

    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    private function expireCookiesInResponse(ResponseInterface $response): ResponseInterface
    {
        foreach ($this->expireTargetCookieKeys as $key) {
            $response = FigResponseCookies::expire($response, $key);
        }
        return $response;
    }


    /** @var SetCookies */
    private $responseCookies;

    /**
     * @param string $key
     * @param string $value
     * @param \DateTime|null $expiresIn
     * @param bool $httpOnly
     * @param bool $secure
     * @param string $path
     * @param null|string $domain
     */
    protected function setCookie(
        string $key,
        string $value,
        ?\DateTime $expiresIn = null,
        bool $httpOnly = true,
        bool $secure = true,
        string $path = '/',
        ?string $domain = null
    ): void {
        $setCookie = SetCookie::create($key)
            ->withValue($value)
            ->withSecure($secure)
            ->withHttpOnly($httpOnly)
            ->withPath($path);
        if ($domain) {
            $setCookie = $setCookie->withDomain($domain);
        }
        if ($expiresIn) {
            $setCookie = $setCookie->withExpires($expiresIn);
        } else {
            $setCookie = $setCookie->rememberForever();
        }

        if ($this->responseCookies) {
            $this->responseCookies = $this->responseCookies->with($setCookie);
        } else {
            $this->responseCookies = new SetCookies([$setCookie]);
        }
    }

    protected function appendCookieToResponse(ResponseInterface $response): ResponseInterface
    {
        if ($this->responseCookies) {
            return $this->responseCookies->renderIntoSetCookieHeader($response);
        }
        return $response;
    }

    /**
     * @param string $to
     * @param int $statusCode
     * @param array $additionalHeader
     * @return ResponseInterface
     */
    protected function redirect(
        string $to,
        int $statusCode = HttpStatusCode::MOVED_PERMANENTLY,
        array $additionalHeader = []
    ): ResponseInterface {
        /** @var Response $res */
        $res = $this->getResponseObject($statusCode, $additionalHeader);
        $res = $this->appendCookieToResponse($res);
        $res = $this->expireCookiesInResponse($res);
        return $res->withRedirect($to, $statusCode);
    }

    /**
     * 200 OK
     * @param string $templateFileName
     * @param array $args
     * @param array $additionalHeader
     * @return ResponseInterface
     */
    protected function ok(string $templateFileName, array $args = [], array $additionalHeader = []): ResponseInterface
    {
        $res = $this->getResponseObject(HttpStatusCode::OK, $additionalHeader);
        return $this->render($res, $templateFileName, $args);
    }

    /**
     * 400 Bad Request
     * @param string $templateFileName
     * @param array $args
     * @param array $additionalHeader
     * @return ResponseInterface
     */
    protected function badRequest(string $templateFileName, array $args = [], array $additionalHeader = []): ResponseInterface
    {
        $res = $this->getResponseObject(HttpStatusCode::BAD_REQUEST, $additionalHeader);
        return $this->render($res, $templateFileName, $args);
    }

    /**
     * 401 Unauthorized
     * @param string $templateFileName
     * @param array $args
     * @param array $additionalHeader
     * @return ResponseInterface
     */
    protected function unauthorized(string $templateFileName, array $args = [], array $additionalHeader = []): ResponseInterface
    {
        $res = $this->getResponseObject(HttpStatusCode::UNAUTHORIZED, $additionalHeader);
        return $this->render($res, $templateFileName, $args);
    }

    /**
     * 403 Forbidden
     * @param string $templateFileName
     * @param array $args
     * @param array $additionalHeader
     * @return ResponseInterface
     */
    protected function forbidden(string $templateFileName, array $args = [], array $additionalHeader = []): ResponseInterface
    {
        $res = $this->getResponseObject(HttpStatusCode::FORBIDDEN, $additionalHeader);
        return $this->render($res, $templateFileName, $args);
    }

    /**
     * 404 Not Found
     * @param string $templateFileName
     * @param array $args
     * @param array $additionalHeader
     * @return ResponseInterface
     */
    protected function notFound(string $templateFileName, array $args = [], array $additionalHeader = []): ResponseInterface
    {
        $res = $this->getResponseObject(HttpStatusCode::NOT_FOUND, $additionalHeader);
        return $this->render($res, $templateFileName, $args);
    }

    /**
     * 500 Internal Server Error
     * @param string $templateFileName
     * @param array $args
     * @param array $additionalHeader
     * @return ResponseInterface
     */
    protected function internalServerError(string $templateFileName, array $args = [], array $additionalHeader = []): ResponseInterface
    {
        $res = $this->getResponseObject(HttpStatusCode::INTERNAL_SERVER_ERROR, $additionalHeader);
        return $this->render($res, $templateFileName, $args);
    }

    /**
     * 503 Service Unavailable
     * @param string $templateFileName
     * @param array $args
     * @param array $additionalHeader
     * @return ResponseInterface
     */
    protected function serviceUnavailable(string $templateFileName, array $args = [], array $additionalHeader = []): ResponseInterface
    {
        $res = $this->getResponseObject(HttpStatusCode::SERVICE_UNAVAILABLE, $additionalHeader);
        return $this->render($res, $templateFileName, $args);
    }

    /**
     * @param int $statusCode
     * @param array $additionalHeader
     * @return ResponseInterface
     */
    protected function getResponseObject(int $statusCode, array $additionalHeader = []): ResponseInterface
    {
        $headers = new Headers(array_merge(
            $this->defaultResponseHeaders(),
            $this->customResponseHeaders(),
            $additionalHeader
        ));
        return new Response($statusCode, $headers);
    }
}

