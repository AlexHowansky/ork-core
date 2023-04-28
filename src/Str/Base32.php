<?php

/**
 * Ork Core
 *
 * @package   Ork\Core
 * @copyright 2015-2023 Alex Howansky (https://github.com/AlexHowansky)
 * @license   https://github.com/AlexHowansky/ork-core/blob/master/LICENSE MIT License
 * @link      https://github.com/AlexHowansky/ork-core
 */

namespace Ork\Core\Str;

use DomainException;
use Ork\Core\ConfigurableTrait;

/**
 * Perform BASE32 encoding/decoding.
 */
class Base32
{

    use ConfigurableTrait;

    protected const ERROR_INVALID_ALPHABET = 'Alphabet must have 32 unique case-insensitive characters.';
    protected const ERROR_INVALID_CHARACTER = 'Invalid character in input.';
    protected const ERROR_INVALID_INPUT = 'Invalid input.';
    protected const ERROR_INVALID_PAD_CHARACTER = 'Invalid pad character.';

    /**
     * Configurable trait parameters.
     *
     * @var array<string, mixed>
     */
    protected array $config = [

        // The characters to use for encoding. Defaults to RFC4648.
        'alphabet' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567',

        // The charater to pad the encoded string with.
        'padCharacter' => '=',

    ];

    /**
     * Decode a string from BASE32.
     *
     * @param string $data The BASE32 encoded string to decode.
     *
     * @return string The decoded string.
     *
     * @throws DomainException On invalid input.
     */
    public function decode(string $data): string
    {

        // Make sure we have a valid data string.
        if (strlen($data) % 8 !== 0) {
            throw new DomainException(self::ERROR_INVALID_INPUT);
        }

        // Trim the padding.
        $data = rtrim($data, $this->getConfig('padCharacter'));

        // Convert the data to a binary string.
        $bits = '';
        foreach (str_split($data) as $char) {
            $index = stripos($this->getConfig('alphabet'), $char);
            if ($index === false) {
                throw new DomainException(self::ERROR_INVALID_CHARACTER);
            }
            $bits .= sprintf('%05b', $index);
        }

        // Decode the binary string.
        $output = '';
        foreach (str_split($bits, 8) as $chunk) {
            if (strlen($chunk) < 8) {
                continue;
            }
            $output .= chr((int) bindec($chunk));
        }
        return $output;

    }

    /**
     * Encode a string to BASE32.
     *
     * @param string $data The string to encode.
     *
     * @return string The BASE32 encoded string.
     */
    public function encode(string $data): string
    {

        if ($data === '') {
            return '';
        }

        // Convert the data to a binary string.
        $bits = '';
        foreach (str_split($data) as $char) {
            $bits .= sprintf('%08b', ord($char));
        }

        // Make sure the string length is a multiple of 5, padding if needed.
        $len = strlen($bits);
        $mod = $len % 5;
        if ($mod !== 0) {
            $bits = str_pad($bits, $len + 5 - $mod, '0', STR_PAD_RIGHT);
        }

        // Split the binary string into 5-bit chunks and encode each chunk.
        $output = '';
        foreach (str_split($bits, 5) as $chunk) {
            $output .= substr($this->getConfig('alphabet'), (int) bindec($chunk), 1);
        }

        // Add padding.
        $len  = strlen($output);
        $mod = $len % 8;
        if ($mod !== 0) {
            $output = str_pad($output, $len + 8 - $mod, $this->getConfig('padCharacter'), STR_PAD_RIGHT);
        }

        return $output;

    }

    /**
     * Make sure we have 32 unique case-insensitive characters.
     *
     * @param string $alphabet The alphabet to use.
     *
     * @throws DomainException On invalid input.
     */
    protected function filterConfigAlphabet(string $alphabet): string
    {
        if (count(array_unique(str_split(strtoupper($alphabet)))) !== 32) {
            throw new DomainException(self::ERROR_INVALID_ALPHABET);
        }
        if (str_contains($alphabet, $this->getConfig('padCharacter')) === true) {
            throw new DomainException(self::ERROR_INVALID_PAD_CHARACTER);
        }
        return $alphabet;
    }

    protected function filterConfigPadCharacter(string $padCharacter): string
    {
        if (strlen($padCharacter) > 1) {
            throw new DomainException(self::ERROR_INVALID_PAD_CHARACTER);
        }
        if (str_contains($this->getConfig('alphabet'), $padCharacter) === true) {
            throw new DomainException(self::ERROR_INVALID_PAD_CHARACTER);
        }
        return $padCharacter;
    }

}
