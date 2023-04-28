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

use Ork\Core\ConfigurableTrait;

/**
 * A simple template expander.
 */
class Template
{

    use ConfigurableTrait;

    /**
     * Configurable trait parameters.
     *
     * @var array<string, mixed>
     */
    protected array $config = [

        // The ending tag delimiter.
        'delimiterEnd' => '}}',

        // The starting tag delimiter.
        'delimiterStart' => '{{',

        // The template to expand.
        'template' => '',

    ];

    /**
     * Render the template.
     *
     * @param array<string, string> $params The replacement values to render into the template.
     */
    public function render(array $params): string
    {
        return str_replace(
            array_map(
                fn($key) => $this->getConfig('delimiterStart') . $key . $this->getConfig('delimiterEnd'),
                array_keys($params)
            ),
            array_values($params),
            $this->getConfig('template')
        );
    }

}
