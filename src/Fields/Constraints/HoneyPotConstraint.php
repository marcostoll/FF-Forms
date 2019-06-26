<?php
/**
 * Definition of HoneyPotConstraint
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields\Constraints;

use FF\Forms\Fields\Constraints\Violations\AbstractViolation;
use FF\Forms\Fields\Constraints\Violations\SecurityViolation;
use FF\Forms\Fields\Values\AbstractValue;

/**
 * Class HoneyPotConstraint
 *
 * @package FF\Forms\Fields\Constraints
 */
class HoneyPotConstraint extends AbstractConstraint
{
    /**
     * Checks the given value violates the constraint's rules
     *
     * @param AbstractValue $value
     * @return SecurityViolation|null
     */
    public function check(AbstractValue $value): ?AbstractViolation
    {
        return (!$value->isEmpty()) ? new SecurityViolation($this, $value) : null;
    }
}
