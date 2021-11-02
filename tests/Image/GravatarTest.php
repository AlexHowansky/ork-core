<?php

/**
 * Ork Core
 *
 * @package   Ork\Core
 * @copyright 2015-2021 Alex Howansky (https://github.com/AlexHowansky)
 * @license   https://github.com/AlexHowansky/ork-core/blob/master/LICENSE MIT License
 * @link      https://github.com/AlexHowansky/ork-core
 */

namespace Ork\Core\Tests\Image;

use DomainException;
use Ork\Core\Image\Gravatar;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Test the Gravatar helper.
 */
class GravatarTest extends TestCase
{

    /**
     * Verify that the size parameter is not too large.
     */
    public function testBadSizeLarge(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Size must be between 1 and 512.');
        new Gravatar(['size' => '513']);
    }

    /**
     * Verify that the size parameter is not too small.
     */
    public function testBadSizeSmall(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Size must be between 1 and 512.');
        new Gravatar(['size' => 0]);
    }

    /**
     * Verify that we get an error if we don't provide an email.
     */
    public function testNoEmail(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No email specified.');
        (new Gravatar())->getUri();
    }

    /**
     * Verify that casting to a string throws an exception.
     */
    public function testToStringException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No email specified.');
        (string) new Gravatar();
    }

    /**
     * Verify that we've built the URI correctly.
     */
    public function testUri(): void
    {
        $gravatar = new Gravatar([
            'email' => 'foo@bar.com',
            'defaultUri' => 'http://a.b?c=1&d=2',
            'size' => 64,
        ]);
        $this->assertStringStartsWith('http://www.gravatar.com/avatar/', $gravatar->getUri());
        $this->assertSame($gravatar->getUri(), (string) $gravatar);
    }

}
