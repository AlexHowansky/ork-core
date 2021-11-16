<?php

/**
 * Ork Core
 *
 * @package   Ork\Core
 * @copyright 2015-2021 Alex Howansky (https://github.com/AlexHowansky)
 * @license   https://github.com/AlexHowansky/ork-core/blob/master/LICENSE MIT License
 * @link      https://github.com/AlexHowansky/ork-core
 */

namespace Ork\Core\Filter\State;

use Ork\Core\ConfigurableTrait;
use UnexpectedValueException;

/**
 * Convert a state name to its abbreviation.
 */
class NameToAbbreviation
{

    use ConfigurableTrait;

    protected const ERROR_NO_ABBREVIATION = 'Input value can not be converted to an abbreviation.';

    /**
     * Configurable trait parameters.
     *
     * @var array<string, mixed>
     */
    protected array $config = [

        // If true, throw an exception on invalid input. If false, return it untouched.
        'abortOnInvalidInput' => true,

        // True to include US territories that aren't states.
        'includeTerritories' => false,

    ];

    /**
     * The list of allowable states.
     *
     * @var array<string, string>
     */
    protected array $states = [
        'alabama' => 'AL',
        'alaska' => 'AK',
        'arizona' => 'AZ',
        'arkansas' => 'AR',
        'california' => 'CA',
        'colorado' => 'CO',
        'connecticut' => 'CT',
        'delaware' => 'DE',
        'district of columbia' => 'DC',
        'florida' => 'FL',
        'georgia' => 'GA',
        'hawaii' => 'HI',
        'idaho' => 'ID',
        'illinois' => 'IL',
        'indiana' => 'IN',
        'iowa' => 'IA',
        'kansas' => 'KS',
        'kentucky' => 'KY',
        'louisiana' => 'LA',
        'maine' => 'ME',
        'maryland' => 'MD',
        'massachusetts' => 'MA',
        'michigan' => 'MI',
        'minnesota' => 'MN',
        'mississippi' => 'MS',
        'missouri' => 'MO',
        'montana' => 'MT',
        'nebraska' => 'NE',
        'nevada' => 'NV',
        'new hampshire' => 'NH',
        'new jersey' => 'NJ',
        'new mexico' => 'NM',
        'new york' => 'NY',
        'north carolina' => 'NC',
        'north dakota' => 'ND',
        'ohio' => 'OH',
        'oklahoma' => 'OK',
        'oregon' => 'OR',
        'pennsylvania' => 'PA',
        'rhode island' => 'RI',
        'south carolina' => 'SC',
        'south dakota' => 'SD',
        'tennessee' => 'TN',
        'texas' => 'TX',
        'utah' => 'UT',
        'vermont' => 'VT',
        'virginia' => 'VA',
        'washington' => 'WA',
        'west virginia' => 'WV',
        'wisconsin' => 'WI',
        'wyoming' => 'WY',
    ];

    /**
     * The list of allowable territories.
     *
     * @var array<string, string>
     */
    protected array $territories = [
        'american samoa' => 'AS',
        'federated states of micronesia' => 'FM',
        'guam' => 'GU',
        'marshall islands' => 'MH',
        'northern mariana islands' => 'MP',
        'palau' => 'PW',
        'puerto rico' => 'PR',
        'virgin islands' => 'VI',
    ];

    /**
     * Convert a state name to its abbreviation.
     *
     * @param string $name The state name.
     *
     * @return string The state abbreviation.
     *
     * @throws UnexpectedValueException If the state name can not be converted to an abbreviation.
     */
    public function filter(string $name): string
    {
        $normalized = (string) preg_replace('/\s+/', ' ', strtolower(trim($name)));
        if (array_key_exists($normalized, $this->states) === true) {
            return $this->states[$normalized];
        }
        if (
            $this->getConfig('includeTerritories') === true &&
            array_key_exists($normalized, $this->territories) === true
        ) {
            return $this->territories[$normalized];
        }
        if ($this->getConfig('abortOnInvalidInput') === true) {
            throw new UnexpectedValueException(self::ERROR_NO_ABBREVIATION);
        }
        return $name;
    }

}
