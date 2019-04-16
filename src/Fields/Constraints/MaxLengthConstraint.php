<?php
/**
 * Definition of MaxLengthConstraint
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
 * Class MaxLengthConstraint
 *
 * @package FF\Forms\Fields\Constraints
 */
class MaxLengthConstraint extends AbstractConstraint
{
    /**
     * @var int
     */
    protected $maxLength;

    /**
     * @param int $maxLength
     */
    public function __construct(int $maxLength)
    {
        $this->setMaxLength($maxLength);
    }

    /**
     * @param int $maxLength
     * @return $this
     */
    public function setMaxLength(int $maxLength)
    {
        $this->maxLength = $maxLength;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxLength(): int
    {
        return $this->maxLength;
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

        return (strlen($value->getValue()) > $this->maxLength) ?
            new InvalidValueViolation($this, $value) :
            null;
    }
}