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
abstract class JsonResponseBodyBase implements \JsonSerializable
{
    /**
     * 空配列のときにもJSONのオブジェクト形式で返却したいフィールド名を列挙する
     * @var  string[]
     */
    protected static $objectTypeFields = [];

    /**
     * @return array|stdClass
     */
    public function jsonSerialize()
    {
        if (!$vars = get_object_vars($this)) {
            return new stdClass();
        }

        if (!static::$objectTypeFields) {
            return $vars;
        }

        foreach (static::$objectTypeFields as $field) {
            if (($vars[$field] ?? null) === []) {
                $vars[$field] = new \stdClass();
            }
        }
        return $vars;
    }
}
