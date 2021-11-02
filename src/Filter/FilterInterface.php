<?php

/**
 * Ork Core
 *
 * @package   Ork\Core
 * @copyright 2015-2021 Alex Howansky (https://github.com/AlexHowansky)
 * @license   https://github.com/AlexHowansky/ork-core/blob/master/LICENSE MIT License
 * @link      https://github.com/AlexHowansky/ork-core
 */

namespace Ork\Core\Filter;

/**
 * Filter interface.
 */
interface FilterInterface
{

    /**
     * Filter a value.
     *
     * @param mixed $value The value to filter.
     *
     * @return mixed The filtered value.
     */
    public function filter($value);

}
