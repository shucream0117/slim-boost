<?php

namespace Shucream0117\SlimBoost\Validators;

use Respect\Validation\Exceptions\AllOfException;
use Respect\Validation\Exceptions\AttributeException;
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
     */
    protected function validate(array $params, ValidationRuleSet $ruleSet): ValidationResult
    {
        $errors = [];
        /**
         * @var string $key
         * @var Validator $rule
         */
        foreach ($ruleSet as $key => $rule) {
            try {
                if (strpos($key, '.')) {
                    Validator::keyNested($key, $rule)->assert($params);
                } else {
                    Validator::key($key, $rule)->assert($params);
                }
            } catch (AllOfException $e) {
                /** @var AttributeException|ValidationException $exception */
                foreach ($e as $exception) {
                    $validationRuleId = $exception->getId();
                    if ($validationRuleId !== 'allOf') {
                        // ネストしたキーがない場合のエラーの識別子は 'keyNested' ではなく 'key' にする
                        if ($validationRuleId === 'keyNested') {
                            $validationRuleId = 'key';
                        }

                        // キーが存在せずにエラーになった時、optionalならスルーする
                        if ($validationRuleId === 'key') {
                            $rules = $rule->getRules();
                            if (array_shift($rules) instanceof Optional) {
                                break;
                            }
                        }
                        $errors[$key][] = $validationRuleId;
                    }
                }
            }
        }
        return new ValidationResult($params, $errors, $ruleSet);
    }
}
