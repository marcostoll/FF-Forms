<?php
/**
 * Definition of AbstractConstraint
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields\Constraints;

use FF\Forms\Fields\Constraints\Violations\AbstractViolation;
use FF\Forms\Fields\Values\AbstractValue;

/**
 * Class AbstractConstraint
 *
 * @package FF\Forms\Fields\Constraints
 */
abstract class AbstractConstraint
{
    /**
     * Checks the given value violates the constraint's rules
     *
     * @param AbstractValue $value
     * @return AbstractViolation|null
     */
    abstract public function check(AbstractValue $value): ?AbstractViolation;
}
