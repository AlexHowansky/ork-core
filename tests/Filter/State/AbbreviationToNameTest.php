<?php

/**
 * Ork Core
 *
 * @package   Ork\Core
 * @copyright 2015-2021 Alex Howansky (https://github.com/AlexHowansky)
 * @license   https://github.com/AlexHowansky/ork-core/blob/master/LICENSE MIT License
 * @link      https://github.com/AlexHowansky/ork-core
 */

namespace Ork\Core\Tests\Filter\State;

use Ork\Core\Filter\State\AbbreviationToName;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * Test converting a state abbreviation to its name.
 */
class AbbreviationToNameTest extends TestCase
{

    /**
     * Verify that bad values throw an exception when requested.
     */
    public function testBadWithException()
    {
        $this->expectException(UnexpectedValueException::class);
        $filter = new AbbreviationToName();
        $filter->filter('foo');
    }

    /**
     * Verify that bad values don't throw an exception when requested.
     */
    public function testBadWithoutException()
    {
        $filter = new AbbreviationToName(['abortOnInvalidInput' => false]);
        $this->assertEquals('foo', $filter->filter('foo'));
    }

    /**
     * Verify default settings.
     */
    public function testDefaultConfig()
    {
        $this->expectException(UnexpectedValueException::class);
        $filter = new AbbreviationToName();
        $this->assertEquals(false, $filter->getConfig('includeTerritories'));
        $this->assertEquals('PR', $filter->filter('PR'));
    }

    /**
     * Verify that good conversions work as expected.
     */
    public function testGood()
    {
        $filter = new AbbreviationToName();
        $this->assertEquals('New York', $filter->filter('ny'));
    }

    /**
     * Verify that territories work correctly.
     */
    public function testTerritory()
    {
        $filter = new AbbreviationToName(['includeTerritories' => true]);
        $this->assertEquals('Puerto Rico', $filter->filter('PR'));
    }

    /**
     * Verify that values get trimmed properly.
     */
    public function testTrim()
    {
        $filter = new AbbreviationToName();
        $this->assertEquals('New York', $filter->filter(' ny '));
    }

}
