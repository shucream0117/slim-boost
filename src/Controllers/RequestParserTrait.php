<?php

namespace Shucream0117\SlimBoost\Controllers;

use Slim\Http\UploadedFile;

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

    /**
     * Cookieの値を取得する
     * @param string $key
     * @param null $default
     * @return mixed
     */
    protected function getCookie(string $key, $default = null)
    {
        return $this->request->getCookieParam($key, $default);
    }

    /**
     * Cookieをarrayで取得する
     * @return array
     */
    protected function getCookies(): array
    {
        return $this->request->getCookieParams();
    }

    /**
     * ファイルを取得する
     * @param string $key
     * @return null|UploadedFile
     */
    protected function getFile(string $key): ?UploadedFile
    {
        $files = $this->request->getUploadedFiles();
        return $files[$key] ?? null;
    }

    /**
     * ファイルをarrayで取得する
     * @return UploadedFile[]
     */
    protected function getFiles(): array
    {
        return $this->request->getUploadedFiles();
    }
}
