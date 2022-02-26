<?php

/**
 * Ork Core
 *
 * @package   Ork\Core\Tests
 * @copyright 2015-2022 Alex Howansky (https://github.com/AlexHowansky)
 * @license   https://github.com/AlexHowansky/ork-core/blob/master/LICENSE MIT License
 * @link      https://github.com/AlexHowansky/ork-core
 */

namespace Ork\Core\Tests;

use ArrayIterator;
use DomainException;
use LogicException;
use org\bovigo\vfs\vfsStream;
use Ork\Core\Tests\Fake\ConfigurableTraitConfigured;
use Ork\Core\Tests\Fake\ConfigurableTraitUnconfigured;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;
use TypeError;
use UnexpectedValueException;

/**
 * Test ConfigurableTrait.
 */
class ConfigurableTraitTest extends TestCase
{

    /**
     * Test values.
     *
     * @var array<string, mixed>
     */
    protected array $config = [
        'key1' => 'value1',
        'key2' => 12345,
        'key3' => null,
    ];

    /**
     * Test that the constructor works when passed an array.
     *
     * @return void
     */
    public function testConstructorArray(): void
    {
        $this->assertSame(
            $this->config,
            (new ConfigurableTraitConfigured($this->config))->getConfigs()
        );
    }

    /**
     * Test that the constructor fails when passed a badly formatted file.
     *
     * @return void
     */
    public function testConstructorBadFile(): void
    {
        $this->expectException(RuntimeException::class);
        $file = vfsStream::setup()->url() . '/config.json';
        file_put_contents($file, 'fail');
        new ConfigurableTraitConfigured($file);
    }

    /**
     * Test that the constructor works when passed a file name.
     *
     * @return void
     */
    public function testConstructorFile(): void
    {
        $file = vfsStream::setup()->url() . '/config.json';
        file_put_contents($file, json_encode($this->config));
        $this->assertSame(
            $this->config,
            (new ConfigurableTraitConfigured($file))->getConfigs()
        );
    }

    /**
     * Test that the constructor fails when passed an integer.
     *
     * @return void
     */
    public function testConstructorInteger(): void
    {
        $this->expectException(TypeError::class);
        // @phpstan-ignore-next-line
        new ConfigurableTraitConfigured(1);
    }

    /**
     * Test that the constructor works when passed an object implementing Iterable.
     *
     * @return void
     */
    public function testConstructorIterable(): void
    {
        $this->assertSame(
            $this->config,
            (new ConfigurableTraitConfigured(new ArrayIterator($this->config)))->getConfigs()
        );
    }

    /**
     * Test that the constructor fails when passed a file name that doesn't exist.
     *
     * @return void
     */
    public function testConstructorNoFile(): void
    {
        $this->expectException(RuntimeException::class);
        new ConfigurableTraitConfigured(vfsStream::setup()->url() . '/path/to/bad/file');
    }

    /**
     * Test that the constructor fails when passed an object which does not implement Iterator.
     *
     * @return void
     */
    public function testConstructorNotIterable(): void
    {
        $this->expectException(TypeError::class);
        // @phpstan-ignore-next-line
        new ConfigurableTraitConfigured(new stdClass());
    }

    /**
     * Test that a defined setter filter gets invoked.
     *
     * @return void
     */
    public function testFilterException(): void
    {
        $this->expectException(DomainException::class);
        (new ConfigurableTraitConfigured())->setConfig('key3', 'this is a bad value');
    }

    /**
     * Test that the initial configured values get set and returned correctly.
     *
     * @return void
     */
    public function testGetDefaultValue(): void
    {
        $fake = new ConfigurableTraitConfigured();
        $this->assertSame('default1', $fake->getConfig('key1'));
        $this->assertSame(12345, $fake->getConfig('key2'));
        $this->assertNull($fake->getConfig('key3'));
    }

    /**
     * Test that we get a failure when an object is defined without a $config attribute.
     *
     * @return void
     */
    public function testNoConfigDefined(): void
    {
        $this->expectException(LogicException::class);
        $this->assertObjectNotHasAttribute(
            'config',
            new ConfigurableTraitUnconfigured()
        );
    }

    /**
     * Test that array config values set and get correctly.
     *
     * @return void
     */
    public function testSetAndGetArray(): void
    {
        $fake = new ConfigurableTraitConfigured();
        $config = [];
        foreach (array_keys($this->config) as $key) {
            $config[$key] = md5($key . ':' . microtime(true));
        }
        $fake->setConfigs($config);
        $this->assertSame($config, $fake->getConfigs());
    }

    /**
     * Test that integer config values set and get correctly.
     *
     * @return void
     */
    public function testSetAndGetInteger(): void
    {
        $fake = new ConfigurableTraitConfigured();
        foreach (array_keys($this->config) as $key) {
            $value = mt_rand();
            $fake->setConfig($key, $value);
            $this->assertSame($value, $fake->getConfig($key));
        }
    }

    /**
     * Test that object config values set and get correctly.
     *
     * @return void
     */
    public function testSetAndGetObject(): void
    {
        $fake = new ConfigurableTraitConfigured();
        foreach (array_keys($this->config) as $key) {
            $value = new stdClass();
            $value->foo = md5($key . ':' . microtime(true));
            $value->bar = sha1($key . ':' . microtime(true));
            $fake->setConfig($key, $value);
            $this->assertSame($value, $fake->getConfig($key));
        }
    }

    /**
     * Test that string config values set and get correctly.
     *
     * @return void
     */
    public function testSetAndGetString(): void
    {
        $fake = new ConfigurableTraitConfigured();
        foreach (array_keys($this->config) as $key) {
            $value = md5($key . ':' . microtime(true));
            $fake->setConfig($key, $value);
            $this->assertSame($value, $fake->getConfig($key));
        }
    }

    /**
     * Trying to set a config parameter that doesn't exist should error.
     *
     * @return void
     */
    public function testSetInvalidKey(): void
    {
        $this->expectException(UnexpectedValueException::class);
        (new ConfigurableTraitConfigured())->setConfig('badKey', 'badValue');
    }

}
