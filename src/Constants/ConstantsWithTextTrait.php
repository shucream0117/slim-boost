<?php

namespace Shucream0117\SlimBoost\Constants;

/**
 * Class ConstantsTrait
 * 定数クラスで使うと便利になるトレイト
 */
trait ConstantsWithTextTrait
{
    /**
     * static $text という配列を定義すると定数にマッチするテキストを返してくれる。
     * @param mixed $constant 定数の値
     * @return string 定数に対応するテキスト
     * @throws \Exception static $text が未定義の場合に投げる
     * @throws \InvalidArgumentException 定義されていない定数を指定された場合に投げる
     */
    public static function getText($constant): string
    {
        if (!property_exists(get_class(), 'text')) {
            throw new \Exception('static $text is required by ConstantsWithTextTrait');
        }

        if (!empty(self::$text[$constant])) {
            return self::$text[$constant];
        }
        throw new \InvalidArgumentException("no such constant code: $constant");
    }
}
