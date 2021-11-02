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

use Ork\Core\Filter\State\NameToAbbreviation;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * Test converting a state name to its abbreviation.
 */
class NameToAbbreviationTest extends TestCase
{

    /**
     * Verify that bad values throw an exception when requested.
     */
    public function testBadWithException()
    {
        $this->expectException(UnexpectedValueException::class);
        $filter = new NameToAbbreviation();
        $filter->filter('foo');
    }

    /**
     * Verify that bad values don't throw an exception when requested.
     */
    public function testBadWithoutException()
    {
        $filter = new NameToAbbreviation(['abortOnInvalidInput' => false]);
        $this->assertEquals('foo', $filter->filter('foo'));
    }

    /**
     * Verify default settings.
     */
    public function testDefaultConfig()
    {
        $this->expectException(UnexpectedValueException::class);
        $filter = new NameToAbbreviation();
        $this->assertEquals(false, $filter->getConfig('includeTerritories'));
        $filter->filter('Puerto Rico');
    }

    /**
     * Verify that good conversions work as expected.
     */
    public function testGood()
    {
        $filter = new NameToAbbreviation();
        $this->assertEquals('NY', $filter->filter('new york'));
    }

    /**
     * Verify that territories work correctly.
     */
    public function testTerritory()
    {
        $filter = new NameToAbbreviation(['includeTerritories' => true]);
        $this->assertEquals('PR', $filter->filter('Puerto Rico'));
    }

    /**
     * Verify that values get trimmed properly.
     */
    public function testTrim()
    {
        $filter = new NameToAbbreviation();
        $this->assertEquals('NY', $filter->filter(' new  york   '));
    }

}
