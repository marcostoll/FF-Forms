<?php
/**
 * Definition of BoolConstraint
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields\Constraints;

use FF\Forms\Fields\Constraints\Violations\AbstractViolation;
use FF\Forms\Fields\Constraints\Violations\InvalidValueViolation;
use FF\Forms\Fields\Values\AbstractValue;
use FF\Forms\Fields\Values\ScalarValue;

/**
 * Class IntConstraint
 *
 * @package FF\Forms\Fields\Constraints
 * @see http://php.net/filter-var
 * @see https://www.php.net/manual/en/filter.filters.validate.php
 */
class BoolConstraint extends FilterConstraint
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct(FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }

    /**
     * {@inheritdoc}
     */
    public function check(AbstractValue $value): ?AbstractViolation
    {
        if (!($value instanceof ScalarValue) || $value->isEmpty()) {
            // complex or empty values do not raise violations
            return null;
        }

        $filteredValue = filter_var($value, $this->filter, $this->options);
        if (!is_null($filteredValue)) return null;

        return new InvalidValueViolation($this, $value);
    }

}