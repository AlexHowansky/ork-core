<?php

/**
 * Ork
 *
 * @package   OrkTest\Core
 * @copyright 2015-2017 Alex Howansky (https://github.com/AlexHowansky)
 * @license   https://github.com/AlexHowansky/ork-core/blob/master/LICENSE MIT License
 * @link      https://github.com/AlexHowansky/ork-core
 */

namespace OrkTest\Core;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class ConfigurableTraitTest extends TestCase
{

    protected $config = [
        'key1' => 'value1',
        'key2' => 12345,
        'key3' => null,
    ];

    /**
     * @expectedException \LogicException
     */
    public function testNoConfigDefined()
    {
        $this->assertObjectNotHasAttribute(
            'config',
            new Stub\ConfigurableTraitStubUnconfigured()
        );
    }

    public function testConstructorArray()
    {
        $this->assertSame(
            $this->config,
            (new Stub\ConfigurableTraitStubConfigured($this->config))->getConfigs()
        );
    }

    public function testConstructorIterable()
    {
        $this->assertSame(
            $this->config,
            (new Stub\ConfigurableTraitStubConfigured(new \ArrayIterator($this->config)))->getConfigs()
        );
    }

    public function testConstructorFile()
    {
        $file = vfsStream::setup()->url() . '/config.json';
        file_put_contents($file, json_encode($this->config));
        $this->assertSame(
            $this->config,
            (new Stub\ConfigurableTraitStubConfigured($file))->getConfigs()
        );
    }

    /**
     * @expectedException \TypeError
     */
    public function testConstructorInteger()
    {
        new Stub\ConfigurableTraitStubConfigured(1);
    }

    /**
     * @expectedException \TypeError
     */
    public function testConstructorNotIterable()
    {
        new Stub\ConfigurableTraitStubConfigured(new \stdClass());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testConstructorNoFile()
    {
        new Stub\ConfigurableTraitStubConfigured(vfsStream::setup()->url() . '/path/to/bad/file');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testConstructorBadFile()
    {
        $file = vfsStream::setup()->url() . '/config.json';
        file_put_contents($file, 'fail');
        new Stub\ConfigurableTraitStubConfigured($file);
    }

    public function testGetDefaultValue()
    {
        $stub = new Stub\ConfigurableTraitStubConfigured();
        $this->assertSame('default1', $stub->getConfig('key1'));
        $this->assertSame(12345, $stub->getConfig('key2'));
        $this->assertNull($stub->getConfig('key3'));
    }

    public function testSetAndGetInteger()
    {
        $stub = new Stub\ConfigurableTraitStubConfigured();
        foreach (array_keys($this->config) as $key) {
            $value = mt_rand();
            $stub->setConfig($key, $value);
            $this->assertSame($value, $stub->getConfig($key));
        }
    }

    public function testSetAndGetString()
    {
        $stub = new Stub\ConfigurableTraitStubConfigured();
        foreach (array_keys($this->config) as $key) {
            $value = md5($key . ':' . microtime(true));
            $stub->setConfig($key, $value);
            $this->assertSame($value, $stub->getConfig($key));
        }
    }

    public function testSetAndGetArray()
    {
        $stub = new Stub\ConfigurableTraitStubConfigured();
        $config = [];
        foreach (array_keys($this->config) as $key) {
            $config[$key] = md5($key . ':' . microtime(true));
        }
        $stub->setConfigs($config);
        $this->assertSame($config, $stub->getConfigs());
    }

    public function testSetAndGetObject()
    {
        $stub = new Stub\ConfigurableTraitStubConfigured();
        foreach (array_keys($this->config) as $key) {
            $value = new \stdClass();
            $value->foo = md5($key . ':' . microtime(true));
            $value->bar = sha1($key . ':' . microtime(true));
            $stub->setConfig($key, $value);
            $this->assertSame($value, $stub->getConfig($key));
        }
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testSetInvalidKey()
    {
        (new Stub\ConfigurableTraitStubConfigured())->setConfig('badKey', 'badValue');
    }

    /**
     * @expectedException \DomainException
     */
    public function testFilterException()
    {
        (new Stub\ConfigurableTraitStubConfigured())->setConfig('key3', 'this is a bad value');
    }

}
