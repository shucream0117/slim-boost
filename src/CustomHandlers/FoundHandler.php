<?php

namespace Shucream0117\SlimBoost\CustomHandlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\InvocationStrategyInterface;

/**
 * 正常にルーティングが見つかってメソッドを実行する際のハンドラ
 *
 * Class FoundHandler
 * @package Shucream0117\SlimBoost\CustomHandlers
 */
final class FoundHandler implements InvocationStrategyInterface
{
    public function __invoke(
        callable $callable,
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $routeArguments
    )
    {
        return call_user_func($callable, $routeArguments);
    }
}
