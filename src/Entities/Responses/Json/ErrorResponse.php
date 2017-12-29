<?php

namespace Shucream0117\SlimBoost\Entities\Responses\Json;

/**
 * エラーレスポンス
 *
 * 【例】
 * {
 *   "error": {
 *      "code": 404,
 *      "message": "Not Found"
 *   }
 * }
 */
class ErrorResponse extends JsonResponseBodyBase
{
    /** @var array */
    protected $error;

    /**
     * 通常のエラーメッセージ以外に入れたいものがあれば、$additionalDataに連想配列で指定出来る
     *
     * @param int $errorCode
     * @param string $message
     * @param array $additionalData
     */
    public function __construct(int $errorCode, string $message, array $additionalData = [])
    {
        $this->error['code'] = $errorCode;
        $this->error['message'] = $message;

        if ($additionalData) {
            foreach ($additionalData as $key => $value) {
                $this->error[$key] = $value;
            }
        }
    }
}
