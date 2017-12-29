<?php

namespace Shucream0117\SlimBoost\Controllers;

/**
 * Request オブジェクトをコントローラから扱いやすくする氏
 */
trait RequestParserTrait
{
    /** @var \Slim\Http\Request */
    protected $request;

    /**
     * リクエストボディのjsonをarrayで取得する
     * @return array
     */
    protected function getRequestParams(): array
    {
        return $this->request->getParsedBody() ?? [];
    }

    /**
     * リクエストボディの値を取得する
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    protected function getRequestParam(string $key, $default = null)
    {
        return $this->request->getParsedBodyParam($key, $default);
    }

    /**
     * クエリパラメータをarrayで取得する
     * @return array
     */
    protected function getQueryParams(): array
    {
        return $this->request->getQueryParams() ?? [];
    }

    /**
     * クエリパラメータの値を取得する
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    protected function getQueryParam(string $key, $default = null)
    {
        return $this->request->getQueryParam($key, $default);
    }
}
