<?php

namespace Shucream0117\SlimBoost\Controllers;

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
