<?php

/**
 * Ork Core
 *
 * @package   Ork\Core
 * @copyright 2015-2021 Alex Howansky (https://github.com/AlexHowansky)
 * @license   https://github.com/AlexHowansky/ork-core/blob/master/LICENSE MIT License
 * @link      https://github.com/AlexHowansky/ork-core
 */

namespace Ork\Core;

use InvalidArgumentException;
use LogicException;
use RuntimeException;
use Traversable;
use UnexpectedValueException;

/**
 * Defines a trait for consistent configuration of object features.
 */
trait ConfigurableTrait
{

    /**
     * We can't declare an empty/default $config here because the PHP trait
     * implementation requires that all definitions match exactly.
     */

    /**
     * Constructor.
     *
     * @param Traversable<string, mixed>|array<string, mixed>|string $config The configuration names/values to set, or
     *        a file name that contains them in JSON format.
     *
     * @throws LogicException If a `$config` attribute has not been defined.
     */
    public function __construct($config = null)
    {
        if (
            property_exists($this, 'config') === false ||
            // @phpstan-ignore-next-line
            is_array($this->config) === false
        ) {
            throw new LogicException(
                'Class definition for ' . get_class($this) . ' must include a protected array attribute named config.'
            );
        }
        if ($config !== null) {
            if (is_string($config) === true) {
                $this->loadConfig($config);
            } else {
                $this->setConfigs($config);
            }
        }
    }

    /**
     * Filter a configuration attribute value as it's being set.
     *
     * @param string $name  The name of the configuration to filter.
     * @param mixed  $value The value to filter.
     *
     * @return mixed The filtered value.
     */
    protected function filterConfig(string $name, $value = null)
    {
        $filterMethod = 'filterConfig' . ucfirst($name);
        if (method_exists($this, $filterMethod) === true) {
            $value = $this->$filterMethod($value);
        }
        return $value;
    }

    /**
     * Get the value of a configuration attribute.
     *
     * @param string $name The name of the configuration attribute to get the value for.
     *
     * @return mixed The value of the named configuration attribute.
     */
    public function getConfig(string $name)
    {
        return $this->validateConfig($name)->config[$name];
    }

    /**
     * Get all configuration attributes.
     *
     * @return array<string, mixed> All configuration attributes.
     */
    public function getConfigs(): array
    {
        return $this->config;
    }

    /**
     * Set configuration from a JSON file.
     *
     * @param string $file The file containing the configuration attributes.
     *
     * @return self Allow method chaining.
     *
     * @throws RuntimeException On error.
     */
    public function loadConfig(string $file): self
    {
        if (file_exists($file) === false) {
            throw new RuntimeException('No such config file.');
        }
        $data = json_decode((string) file_get_contents($file), true);
        if ($data === null) {
            throw new RuntimeException('Invalid config file.');
        }
        return $this->setConfigs($data);
    }

    /**
     * Set a configuration attribute.
     *
     * @param string $name  The name of the configuration attribute to set.
     * @param mixed  $value The value to set the configuration attribute to.
     *
     * @return self Allow method chaining.
     */
    public function setConfig(string $name, $value): self
    {
        $this->validateConfig($name)->config[$name] = $this->filterConfig($name, $value);
        return $this;
    }

    /**
     * Set multiple configuration attributes.
     *
     * @param Traversable<string, mixed>|array<string, mixed> $config The configuration attributes to set.
     *
     * @return self Allow method chaining.
     *
     * @throws InvalidArgumentException On error.
     */
    public function setConfigs(iterable $config): self
    {
        foreach ($config as $name => $value) {
            $this->setConfig($name, $value);
        }
        return $this;
    }

    /**
     * Validate that the named configuration attribute exists.
     *
     * @param string $name The configuration attribute to validate.
     *
     * @return self Allow method chaining.
     *
     * @throws UnexpectedValueException If the named configuration attribute does not exist.
     */
    protected function validateConfig(string $name): self
    {
        if (array_key_exists($name, $this->config) === false) {
            throw new UnexpectedValueException('No such configuration attribute: ' . $name);
        }
        return $this;
    }

}
