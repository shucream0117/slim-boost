<?php

namespace Shucream0117\SlimBoost\Entities\Responses\Json;

use stdClass;

/**
 * JSONのレスポンスの中身を表すクラス
 * {
 *   // ！！！この部分！！！！
 * }
 * についての構造体です。
 *
 * プロパティ名はそのままjsonのキー名になる
 *
 */
abstract class JsonResponseBodyBase
{
    /**
     * json的にオブジェクトを期待するとき、PHPの空配列をjsonで空オブジェクトにするための関数
     * @param array $array
     * @return array|stdClass
     */
    protected function convertToEmptyObjectIfAssocArrayIsEmpty(array $array)
    {
        if (empty($array)) {
            return new stdClass();
        }
        return $array;
    }
}
