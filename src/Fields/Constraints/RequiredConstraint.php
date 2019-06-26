<?php
/**
 * Definition of RequiredConstraint
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields\Constraints;

use FF\Forms\Fields\Constraints\Violations\AbstractViolation;
use FF\Forms\Fields\Constraints\Violations\MissingValueViolation;
use FF\Forms\Fields\Values\AbstractValue;

/**
 * Class RequiredConstraint
 *
 * @package FF\Forms\Fields\Constraints
 */
class RequiredConstraint extends AbstractConstraint
{
    /**
     * Checks the given value violates the constraint's rules
     *
     * @param AbstractValue $value
     * @return MissingValueViolation|null
     */
    public function check(AbstractValue $value): ?AbstractViolation
    {
        return $value->isEmpty() ? new MissingValueViolation($this, $value) : null;
    }
}
