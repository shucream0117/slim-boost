<?php

namespace Shucream0117\SlimBoost\Constants;

/**
 * HTTPステータスコードとマッチするテキストの定数クラス
 */
final class HttpStatusCode
{
    use ConstantsWithTextTrait;

    const OK = 200;
    const CREATED = 201;
    const MOVED_PERMANENTLY = 301;
    const FOUND = 302;
    const SEE_OTHER = 303;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const INTERNAL_SERVER_ERROR = 500;
    const SERVICE_UNAVAILABLE = 503;

    protected static $text = [
        self::OK => 'OK',
        self::CREATED => 'Created',
        self::MOVED_PERMANENTLY => 'Moved Permanently',
        self::FOUND => 'Found',
        self::SEE_OTHER => 'See Other',
        self::BAD_REQUEST => 'Bad Request',
        self::UNAUTHORIZED => 'Unauthorized',
        self::FORBIDDEN => 'Forbidden',
        self::NOT_FOUND => 'Not Found',
        self::METHOD_NOT_ALLOWED => 'Method Not Allowed',
        self::INTERNAL_SERVER_ERROR => 'Internal Server Error',
        self::SERVICE_UNAVAILABLE => 'Service Unavailable',
    ];
}
