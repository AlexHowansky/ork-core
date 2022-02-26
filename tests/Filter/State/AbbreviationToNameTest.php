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
    public function testBadWithException(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Input value can not be converted to a name.');
        (new AbbreviationToName())->filter('foo');
    }

    /**
     * Verify that bad values don't throw an exception when requested.
     */
    public function testBadWithoutException(): void
    {
        $this->assertEquals('foo', (new AbbreviationToName(['abortOnInvalidInput' => false]))->filter('foo'));
    }

    /**
     * Verify default settings.
     */
    public function testDefaultConfig(): void
    {
        $filter = new AbbreviationToName();
        $this->assertEquals(true, $filter->getConfig('abortOnInvalidInput'));
        $this->assertEquals(false, $filter->getConfig('includeTerritories'));
    }

    /**
     * Verify that good conversions work as expected.
     */
    public function testGood(): void
    {
        $this->assertEquals('New York', (new AbbreviationToName())->filter('ny'));
    }

    /**
     * Verify that territories work correctly.
     */
    public function testTerritory(): void
    {
        $this->assertEquals('Puerto Rico', (new AbbreviationToName(['includeTerritories' => true]))->filter('PR'));
    }

    /**
     * Verify that values get trimmed properly.
     */
    public function testTrim(): void
    {
        $this->assertEquals('New York', (new AbbreviationToName())->filter(' ny '));
    }

}
