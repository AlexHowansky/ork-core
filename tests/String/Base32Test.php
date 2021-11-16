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

use DomainException;
use Ork\Core\String\Base32;
use PHPUnit\Framework\TestCase;

/**
 * BASE32 encode/decode test.
 */
class Base32Test extends TestCase
{

    /**
     * Provider for testBadAlphabet() method.
     *
     * @return array<mixed>
     */
    public function badAlphabetProvider(): array
    {
        return [

            // Value is empty.
            [''],

            // Value is too short.
            ['foo'],

            // Value is too long.
            ['ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'],

            // Value has repeating characters.
            ['AACDEFGHIJKLMNOPQRSTUVWXYZ234566'],

            // Value is case sensitive.
            ['ABCDEFGHIJKLMNOPQRSTUVWXYZabcdef'],

        ];
    }

    /**
     * Provider for testCustomAlphabet() method.
     *
     * @return array<mixed>
     */
    public function customAlphabetProvider(): array
    {
        return [
            ['0123456789~!@#$%^&*()-+=[]{};:<>'],
            ['abcdefghijklmnopqrstuvwxyz234567'],
            ['0123456789abcdefghijklmnopqrstuv'],
        ];
    }

    /**
     * Provider for testEncodeDecode() method.
     *
     * @return array<mixed>
     */
    public function encodeDecodeProvider(): array
    {
        return [
            [
                '',
                'AA',
            ],
            [
                'foo',
                'MZXW6',
            ],
            [
                'this is a test do not pass go do not collect $200',
                'ORUGS4ZANFZSAYJAORSXG5BAMRXSA3TPOQQHAYLTOMQGO3ZAMRXSA3TPOQQGG33MNRSWG5BAEQZDAMA',
            ],
        ];
    }

    /**
     * Verify that bad alphabets throw the proper exception.
     *
     * @dataProvider badAlphabetProvider
     */
    public function testBadAlphabet(string $alphabet): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Alphabet must have 32 unique case-insensitive characters.');
        (new Base32())->setConfig('alphabet', $alphabet);
    }

    /**
     * Verify that custom alphabets works as expected.
     *
     * @dataProvider customAlphabetProvider
     */
    public function testCustomAlphabet(string $alphabet): void
    {
        $input = base64_encode(random_bytes(512));
        $b32 = (new Base32())->setConfig('alphabet', $alphabet);
        $encoded = $b32->encode($input);
        $this->assertNotSame($input, $encoded);
        foreach (str_split($encoded) as $char) {
            $this->assertStringContainsString($char, $b32->getConfig('alphabet'));
        }
        $this->assertSame($input, $b32->decode($encoded));
        $this->assertSame($input, $b32->decode(strtolower($encoded)));
        $this->assertSame($input, $b32->decode(strtoupper($encoded)));
    }

    /**
     * Verify that encode/decode works as expected.
     *
     * @dataProvider encodeDecodeProvider
     */
    public function testEncodeDecode(string $input, string $expected): void
    {
        $b32 = new Base32();
        $encoded = $b32->encode($input);
        $this->assertSame($expected, $encoded);
        $this->assertSame($input, $b32->decode($encoded));
        $this->assertSame($input, $b32->decode(strtolower($encoded)));
        $this->assertSame($input, $b32->decode(strtoupper($encoded)));
    }

    /**
     * Verify that an input containing characters not in the alphabet throws
     * the expected exception.
     */
    public function testInvalidCharacter(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Invalid character in input.');
        (new Base32())->decode('1234$');
    }

    /**
     * Verify that an input of invalid length throws the expected exception.
     */
    public function testInvalidLength(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Invalid input.');
        (new Base32())->decode('123');
    }

    /**
     * Verify that input with invalid padding throws the expected exception.
     */
    public function testInvalidPadding(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Invalid input.');
        (new Base32())->decode('23');
    }

}
