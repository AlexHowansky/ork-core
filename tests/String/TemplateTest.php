<?php

/**
 * Ork Core
 *
 * @package   Ork\Core\Tests
 * @copyright 2015-2021 Alex Howansky (https://github.com/AlexHowansky)
 * @license   https://github.com/AlexHowansky/ork-core/blob/master/LICENSE MIT License
 * @link      https://github.com/AlexHowansky/ork-core
 */

namespace Ork\Core\Tests\String;

use Ork\Core\String\Template;
use PHPUnit\Framework\TestCase;

/**
 * Template render test.
 */
class TemplateTest extends TestCase
{

    /**
     * Provider for testCustomDelimiters() method.
     */
    public function providerForCustomDelimiters()
    {
        return [
            [
                [
                    'delimiterStart' => '__',
                    'delimiterEnd' => '__',
                    'template' => 'this __one__ is a __two__ with __three__',
                ],
                [
                    'one' => 1,
                    'two' => 2,
                    'three' => 3,
                ],
                'this 1 is a 2 with 3',
            ],
            [
                [
                    'delimiterStart' => '[',
                    'delimiterEnd' => ']',
                    'template' => 'this [one] is a [two] with [three]',
                ],
                [
                    'one' => 1,
                    'two' => 2,
                    'three' => 3,
                ],
                'this 1 is a 2 with 3',
            ],
            [
                [
                    'delimiterStart' => '<<',
                    'delimiterEnd' => '>>',
                    'template' => 'this <<one>> is a <<two>> with <<three>>',
                ],
                [
                    'one' => 1,
                    'two' => 2,
                    'three' => 3,
                ],
                'this 1 is a 2 with 3',
            ],
        ];
    }

    /**
     * Provider for testRender() method.
     */
    public function providerForRender()
    {
        return [

            // Standard replacement.
            [
                'this {{one}} is a {{two}} with {{three}}',
                ['one' => 1, 'two' => 2, 'three' => 3],
                'this 1 is a 2 with 3',
            ],

            // Multiple occurrences of the same tag.
            [
                'this {{test}} is a {{test}} with {{other}}',
                ['test' => 'foo', 'other' => 'bar'],
                'this foo is a foo with bar',
            ],

            // No tags.
            [
                'this is a test',
                ['test' => 'foo'],
                'this is a test',
            ],

        ];
    }

    /**
     * Verify that custom delimiters work as expected.
     *
     * @dataProvider providerForCustomDelimiters
     */
    public function testCustomDelimiters(array $config, array $params, string $expect)
    {
        $this->assertSame($expect, (new Template($config))->render($params));
    }

    /**
     * Verify that basic rendering works as expected.
     *
     * @dataProvider providerForRender
     */
    public function testRender(string $template, array $params, string $expect)
    {
        $this->assertSame($expect, (new Template(['template' => $template]))->render($params));
    }

}
