<?php

/**
 * Ork Core
 *
 * @package   Ork\Core
 * @copyright 2015-2021 Alex Howansky (https://github.com/AlexHowansky)
 * @license   https://github.com/AlexHowansky/ork-core/blob/master/LICENSE MIT License
 * @link      https://github.com/AlexHowansky/ork-core
 */

namespace Ork\Core\Image;

use DomainException;
use Ork\Core\ConfigurableTrait;
use RuntimeException;

/**
 * Gravatar URI helper.
 */
class Gravatar
{

    use ConfigurableTrait;

    protected const ERROR_NO_EMAIL = 'No email specified.';
    protected const ERROR_TOO_SMALL = 'Size must be between 1 and 512.';

    /**
     * Configurable trait settings.
     *
     * @var array<string, mixed>
     */
    protected $config = [

        // The default image URI to use if none is available for the requested email.
        'defaultUri' => null,

        // The email address to get the gravatar for.
        'email' => null,

        // The requested image size.
        'size' => null,

    ];

    /**
     * Magic string method.
     *
     * @return string The gravatar URI.
     */
    public function __toString()
    {
        return $this->getUri();
    }

    /**
     * Validate the requested image size.
     *
     * @param int $size The image size to set.
     *
     * @return int
     *
     * @throws DomainException If the provided value is not in the acceptable range.
     */
    protected function filterConfigSize(int $size)
    {
        if ($size < 1 || $size > 512) {
            throw new DomainException(self::ERROR_TOO_SMALL);
        }
        return $size;
    }

    /**
     * Get the gravatar URI.
     *
     * @return string The gravatar URI.
     *
     * @throws RuntimeException If no email has been provided.
     */
    public function getUri()
    {
        if (empty($this->getConfig('email')) === true) {
            throw new RuntimeException(self::ERROR_NO_EMAIL);
        }
        $uri = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->getConfig('email')))) . '.jpg';
        $args = http_build_query([
            's' => $this->getConfig('size'),
            'd' => $this->getConfig('defaultUri'),
        ]);
        if (empty($args) === false) {
            $uri .= '?' . $args;
        }
        return $uri;
    }

}
