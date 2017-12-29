<?php

namespace Shucream0117\SlimBoost\Utils;

use Shucream0117\SlimBoost\Constants\HttpStatusCode;
use Shucream0117\SlimBoost\Entities\Responses\Json\EmptyResponse;
use Shucream0117\SlimBoost\Entities\Responses\Json\ErrorResponse;
use Shucream0117\SlimBoost\Entities\Responses\Json\JsonResponseBodyBase;
use Slim\Http\Response;

trait JsonResponseTrait
{
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
    ): Response
    {
        if (is_null($data)) {
            $data = new EmptyResponse();
        }
        $response = new Response($statusCode);
        $response = $response->withJson(
            get_object_vars($data),
            $statusCode,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR
        );
        return $response;
    }
}
