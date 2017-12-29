<?php

namespace Shucream0117\SlimBoost\Controllers;

use Shucream0117\SlimBoost\Utils\JsonResponseTrait;

/**
 * JSONを返すAPIのコントローラ基底
 *
 * Class RestApiControllerBase
 * @package Shucream0117\SlimBoost\Controllers
 */
abstract class RestApiControllerBase extends ControllerBase
{
    use JsonResponseTrait;
}
