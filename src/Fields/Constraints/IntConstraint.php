<?php
/**
 * Definition of IntConstraint
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields\Constraints;

/**
 * Class IntConstraint
 *
 * @package FF\Forms\Fields\Constraints
 * @see http://php.net/filter-var
 * @see https://www.php.net/manual/en/filter.filters.validate.php
 */
class IntConstraint extends FilterConstraint
{
    /**
     * @param int|null $minValue
     * @param int|null $maxValue
     * @param int|null $flags
     */
    public function __construct(int $minValue = null, int $maxValue = null, int $flags = null)
    {
        $options = null;
        if (!is_null($minValue)) {
            $options['options']['min_range'] = $minValue;
        }
        if (!is_null($maxValue)) {
            $options['options']['max_range'] = $maxValue;
        }
        if (!is_null($maxValue)) {
            $options['flags'] = $flags;
        }

        parent::__construct(FILTER_VALIDATE_INT, $options);
    }
}