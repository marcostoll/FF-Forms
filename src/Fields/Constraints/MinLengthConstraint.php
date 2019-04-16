<?php
/**
 * Definition of MinLengthConstraint
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
 * Class MinLengthConstraint
 *
 * @package FF\Forms\Fields\Constraints
 */
class MinLengthConstraint extends AbstractConstraint
{
    /**
     * @var int
     */
    protected $minLength;

    /**
     * @param int $minLength
     */
    public function __construct(int $minLength)
    {
        $this->setMinLength($minLength);
    }

    /**
     * @param int $minLength
     * @return $this
     */
    public function setMinLength(int $minLength)
    {
        $this->minLength = $minLength;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinLength(): int
    {
        return $this->minLength;
    }

    /**
     * Checks the given value violates the constraint's rules
     *
     * @param AbstractValue $value
     * @return InvalidValueViolation|null
     */
    public function check(AbstractValue $value): ?AbstractViolation
    {
        if (!($value instanceof ScalarValue) || $value->isEmpty()) {
            // complex or empty values do not raise violations
            return null;
        }

        return (strlen($value->getValue()) < $this->minLength) ?
            new InvalidValueViolation($this, $value) :
            null;
    }
}