<?php

/**
 * Ork Core
 *
 * @package   Ork\Core
 * @copyright 2015-2023 Alex Howansky (https://github.com/AlexHowansky)
 * @license   https://github.com/AlexHowansky/ork-core/blob/master/LICENSE MIT License
 * @link      https://github.com/AlexHowansky/ork-core
 */

namespace Ork\Core\String;

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

    /**
     * Configurable trait parameters.
     *
     * @var array<string, mixed>
     */
    protected array $config = [

        // The characters to use for encoding. Defaults to RFC4648.
        'alphabet' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567',

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
        $mod = strlen($data) % 8;
        if ($mod === 1 || $mod === 3 || $mod === 6) {
            throw new DomainException(self::ERROR_INVALID_INPUT);
        }

        // Convert the data to a binary string.
        $bits = '';
        foreach (str_split($data) as $char) {
            $index = stripos($this->getConfig('alphabet'), $char);
            if ($index === false) {
                throw new DomainException(self::ERROR_INVALID_CHARACTER);
            }
            $bits .= sprintf('%05b', $index);
        }

        // Make sure padding is all zeroes.
        if (preg_match('/[^0]/', substr($bits, 0 - strlen($bits) % 8)) === 1) {
            throw new DomainException('Invalid input.');
        }

        // Decode the binary string.
        $output = '';
        foreach (str_split($bits, 8) as $chunk) {
            $output .= chr((int) bindec($chunk));
        }
        return rtrim($output);

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
        return $alphabet;
    }

}
