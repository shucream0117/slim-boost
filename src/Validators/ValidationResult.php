<?php

namespace Shucream0117\SlimBoost\Validators;

class ValidationResult
{
    /** @var array */
    private $validatedParams = [];

    /** @var array  */
    private $rawParams = [];

    /** @var array */
    private $errors;

    /**
     * @param array $params バリデーションターゲットとなった値
     * @param array $errors バリデーションのエラー
     * @param ValidationRuleSet $ruleSet
     * @internal param array $validated
     */
    public function __construct(array $params, array $errors, ValidationRuleSet $ruleSet)
    {
        $this->errors = $errors;
        $this->rawParams = $params;

        // エラーがない時のみ、バリデーション済の値を格納する
        if (empty($errors)) {
            /*
             * キーと値をひっくり返して連想配列にする。
             * (まぁ誤差の範囲だけど、一応効率的に探索するため)
             */
            $ruleKeysMap = array_flip($ruleSet->getKeys());
            $this->validatedParams = array_filter($params, function ($key) use ($errors, $ruleKeysMap) {
                return !array_key_exists($key, $errors) && array_key_exists($key, $ruleKeysMap);
            }, ARRAY_FILTER_USE_KEY);
        }
    }

    /**
     * バリデーション済の値を取得する
     * @param string $key "user.name" のようにネストをドットつなぎで取得できる
     * @param mixed $default
     * @return mixed
     */
    public function validated(string $key = '', $default = null)
    {
        return $this->getFromArray($this->validatedParams, $key, $default);
    }

    /**
     * バリデーション前の値を取得する
     * @param string $key
     * @return array|mixed|null
     */
    public function getRawParams(string $key = '')
    {
        return $this->getFromArray($this->rawParams, $key, null);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return !empty($this->errors);
    }

    /**
     * @param array $array
     * @param string $key
     * @param null $default
     * @return array|mixed|null
     */
    private function getFromArray(array $array, string $key = '', $default = null)
    {
        if ($key === '') {
            return $array;
        }
        $nest = explode('.', $key);
        $tmp = $array;
        foreach ($nest as $k) {
            if (!array_key_exists($k, $tmp)) {
                return $default;
            }
            $tmp = $tmp[$k];
        }
        return $tmp;
    }
}
