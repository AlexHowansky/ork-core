<?php

/**
 * Ork Core
 *
 * @package   Ork\Core\Tests
 * @copyright 2015-2023 Alex Howansky (https://github.com/AlexHowansky)
 * @license   https://github.com/AlexHowansky/ork-core/blob/master/LICENSE MIT License
 * @link      https://github.com/AlexHowansky/ork-core
 */

namespace Ork\Core\Tests\Fake;

use DomainException;

/**
 * Class fake for a configured used of the trait.
 */
class ConfigurableTraitConfigured
{

    use \Ork\Core\ConfigurableTrait;

    /**
     * Configurable trait parameters.
     *
     * @var array<string, mixed> $config
     */
    protected array $config = [
        'key1' => 'default1',
        'key2' => 12345,
        'key3' => null,
    ];

    /**
     * Filter handler for the `key3` parameter.
     *
     * @param mixed $value The value to filter.
     *
     * @return mixed
     *
     * @throws DomainException On invalid value.
     */
    public function filterConfigKey3(mixed $value)
    {
        if ($value === 'this is a bad value') {
            throw new DomainException('Value must be boolean.');
        }
        return $value;
    }

}
