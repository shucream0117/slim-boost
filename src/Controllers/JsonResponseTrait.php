<?php

namespace Shucream0117\SlimBoost\Controllers;

use Shucream0117\SlimBoost\Constants\HttpStatusCode;
use Shucream0117\SlimBoost\Entities\Responses\Json\EmptyResponse;
use Shucream0117\SlimBoost\Entities\Responses\Json\ErrorResponse;
use Shucream0117\SlimBoost\Entities\Responses\Json\JsonResponseBodyBase;
use Slim\Http\Response;

trait JsonResponseTrait
{
    protected function isCorsEnabled(): bool
    {
        return false;
    }

    /**
     * @return string[]
     */
    protected function getAllowedOrigin(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    protected function getAcceptedHeaders(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    protected static $DEFAULT_ACCEPTED_HEADERS = ['Content-Type', 'Accept', 'Origin', 'Accept-Encoding'];

    /**
     * レスポンスオブジェクトにCORS用のヘッダーを付加する
     * Access-Control-Allow-Methods については middleware.php で行っているので注意
     * @param Response $response
     * @param array $allowedOrigin
     * @return Response
     */
    private function addHeadersForCORS(Response $response): Response
    {
        $allowedOrigin = $this->getAllowedOrigin();
        $allowedOriginStr = empty($allowedOrigin) ? '*' : implode(', ', $allowedOrigin);
        $acceptedHeaders = array_merge(static::$DEFAULT_ACCEPTED_HEADERS, $this->getAcceptedHeaders());
        return $response->withHeader('Access-Control-Allow-Origin', $allowedOriginStr)
            ->withHeader('Access-Control-Allow-Headers', $acceptedHeaders);
    }

    /**
     * 200 OK
     * @param JsonResponseBodyBase $data
     * @return Response
     */
    protected function ok(?JsonResponseBodyBase $data = null): Response
    {
        return $this->getResponseObject(HttpStatusCode::OK, $data);
    }

    /**
     * 201 Created
     * @param JsonResponseBodyBase $data
     * @return Response
     */
    protected function created(?JsonResponseBodyBase $data = null): Response
    {
        return $this->getResponseObject(HttpStatusCode::CREATED, $data);
    }

    /**
     * 400 Bad Request
     * @param ErrorResponse $data
     * @return Response
     */
    protected function badRequest(ErrorResponse $data): Response
    {
        return $this->getResponseObject(HttpStatusCode::BAD_REQUEST, $data);
    }

    /**
     * 401 Unauthorized
     * @param ErrorResponse $data
     * @return Response
     */
    protected function unauthorized(ErrorResponse $data): Response
    {
        return $this->getResponseObject(HttpStatusCode::UNAUTHORIZED, $data);
    }

    /**
     * 403 Forbidden
     * @param ErrorResponse $data
     * @return Response
     */
    protected function forbidden(ErrorResponse $data): Response
    {
        return $this->getResponseObject(HttpStatusCode::FORBIDDEN, $data);
    }

    /**
     * 404 Not Found
     * @param ErrorResponse $data
     * @return Response
     */
    protected function notFound(ErrorResponse $data): Response
    {
        return $this->getResponseObject(HttpStatusCode::NOT_FOUND, $data);
    }

    /**
     * 405 Method Not Allowed
     * @param ErrorResponse $data
     * @return Response
     */
    protected function methodNotAllowed(ErrorResponse $data): Response
    {
        return $this->getResponseObject(HttpStatusCode::METHOD_NOT_ALLOWED, $data);
    }

    /**
     * 500 Internal Server Error
     * @param ErrorResponse $data
     * @return Response
     */
    protected function internalServerError(ErrorResponse $data): Response
    {
        return $this->getResponseObject(HttpStatusCode::INTERNAL_SERVER_ERROR, $data);
    }

    /**
     * 503 Service Unavailable
     * @param ErrorResponse $data
     * @return Response
     */
    protected function serviceUnavailable(ErrorResponse $data): Response
    {
        return $this->getResponseObject(HttpStatusCode::SERVICE_UNAVAILABLE, $data);
    }

    /**
     * @param int $statusCode
     * @param null|JsonResponseBodyBase $data
     * @return Response
     */
    private function getResponseObject(
        int $statusCode,
        ?JsonResponseBodyBase $data = null
    ): Response {
        if (is_null($data)) {
            $data = new EmptyResponse();
        }
        $response = new Response($statusCode);
        $response = $response->withJson(
            get_object_vars($data) ?: new \stdClass(),
            $statusCode,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR
        );

        if ($this->isCorsEnabled()) {
            $response = $this->addHeadersForCORS($response);
        }
        return $response;
    }
}
