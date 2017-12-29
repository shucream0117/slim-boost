<?php

namespace Shucream0117\SlimBoost\Controllers;

use Slim\Container;

abstract class ControllerBase
{
    use RequestParserTrait;

    /** @var Container */
    protected $app;

    /**
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
        $this->request = $app->request;
        $this->before();
    }

    /**
     * コントローラの初期化直後、ルーティング前に呼ばれる
     * @return void
     */
    protected function before(): void
    {

    }
}
