<?php

/**
 * Ork Core
 *
 * @package   Ork\Core
 * @copyright 2015-2022 Alex Howansky (https://github.com/AlexHowansky)
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
    public function testBadWithException(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Input value can not be converted to an abbreviation.');
        (new NameToAbbreviation())->filter('foo');
    }

    /**
     * Verify that bad values don't throw an exception when requested.
     */
    public function testBadWithoutException(): void
    {
        $this->assertEquals('foo', (new NameToAbbreviation(['abortOnInvalidInput' => false]))->filter('foo'));
    }

    /**
     * Verify default settings.
     */
    public function testDefaultConfig(): void
    {
        $filter = new NameToAbbreviation();
        $this->assertEquals(false, $filter->getConfig('includeTerritories'));
        $this->assertEquals(true, $filter->getConfig('abortOnInvalidInput'));
    }

    /**
     * Verify that good conversions work as expected.
     */
    public function testGood(): void
    {
        $this->assertEquals('NY', (new NameToAbbreviation())->filter('new york'));
    }

    /**
     * Verify that territories work correctly.
     */
    public function testTerritory(): void
    {
        $this->assertEquals('PR', (new NameToAbbreviation(['includeTerritories' => true]))->filter('Puerto Rico'));
    }

    /**
     * Verify that values get trimmed properly.
     */
    public function testTrim(): void
    {
        $this->assertEquals('NY', (new NameToAbbreviation())->filter(' new  york   '));
    }

}
