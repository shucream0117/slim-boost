<?php

namespace Shucream0117\SlimBoost\Validators;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Rules\Optional;
use Respect\Validation\Validator;

/**
 * バリデーションルールについてはこちら↓
 * @see https://github.com/Respect/Validation/blob/master/docs/VALIDATORS.md
 */
abstract class ValidatorBase
{
    /**
     * バリデーションを実行し、結果を返す
     * @param ValidationRuleSet $ruleSet
     * @param array $params
     * @return ValidationResult
     * @throws \ReflectionException
     */
    protected function validate(array $params, ValidationRuleSet $ruleSet): ValidationResult
    {
        $errors = [];
        /**
         * @var string $key
         * @var Validator $validator
         */
        foreach ($ruleSet as $key => $validator) {
            try {
                if (strpos($key, '.')) {
                    Validator::keyNested($key, $validator)->assert($params);
                } else {
                    Validator::key($key, $validator)->assert($params);
                }
            } catch (NestedValidationException $e) {
                $negative = [];
                $ruleIds = [];
                /** @var ValidationException $ex */
                foreach ($e as $ex) {
                    $ruleId = $ex->getId();
                    if (!in_array($ruleId, ['allOf', 'oneOf', 'noneOf', 'when', 'each'])) {
                        $ruleIds[] = $ruleId;
                        $mode = new \ReflectionProperty($ex, 'mode');
                        $mode->setAccessible(true);
                        if ($mode->getValue($ex) === ValidationException::MODE_NEGATIVE) {
                            $negative[$ruleId] = true;
                        }
                    }
                }
                /** @var array $messages */
                if ($messages = $e->findMessages($ruleIds)) {
                    $rules = $validator->getRules();
                    reset($rules);
                    $head = array_shift($rules);
                    if (array_key_exists('key', $messages)) {
                        // キーが存在せずにエラーになった時、optionalならスルーする
                        if ($head instanceof Optional) {
                            continue;
                        }
                    }
                    $errorRuleIds = array_map(function ($id) use ($negative, $rules) {
                        $isNegative = array_key_exists($id, $negative); // not判定かどうか
                        // ネストしたキーがない場合のエラーの識別子は 'keyNested' ではなく 'key' にする
                        if ($id === 'keyNested') {
                            $id = 'key';
                        }
                        if ($isNegative) {
                            $id = 'not' . ucfirst($id);
                        }
                        return $id;
                    }, array_keys($messages));
                    $errors[$key] = $errorRuleIds;
                }
            }
        }
        return new ValidationResult($params, $errors, $ruleSet);
    }
}
