<?php

/**
 * Ork
 *
 * @package   Ork\Core\Tests
 * @copyright 2015-2021 Alex Howansky (https://github.com/AlexHowansky)
 * @license   https://github.com/AlexHowansky/ork-core/blob/master/LICENSE MIT License
 * @link      https://github.com/AlexHowansky/ork-core
 */

namespace Ork\Core\Tests\Fake;

class ConfigurableTraitConfigured
{

    use \Ork\Core\ConfigurableTrait;

    protected $config = [
        'key1' => 'default1',
        'key2' => 12345,
        'key3' => null,
    ];

    public function filterConfigKey3($value)
    {
        if ($value === 'this is a bad value') {
            throw new \DomainException('Value must be boolean.');
        }
        return $value;
    }

}
