<?php
/**
 * Definition of AbstractValue
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields\Values;

use FF\Forms\Fields\Constraints\AbstractConstraint;
use FF\Forms\Fields\Constraints\Violations\AbstractViolation;

/**
 * Class AbstractValue
 *
 * @package FF\Forms\Fields\Values
 */
abstract class AbstractValue
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Retrieves a plain, non-object representation of this value
     *
     * @return string|array
     */
    public function getPlain()
    {
        return $this->getValue();
    }

    /**
     * Checks whether this instance is considered empty
     *
     * @return bool
     */
    abstract public function isEmpty(): bool;

    /**
     * Checks if this value meets the given constraint
     *
     * @param AbstractConstraint $constraint
     * @param AbstractViolation $violation Argument to fill with the detected violation (if any)
     * @return bool
     */
    public function meetsConstraint(AbstractConstraint $constraint, AbstractViolation &$violation = null): bool
    {
        $violation = $constraint->check($this);
        return is_null($violation);
    }

    /**
     * Retrieves a string representation if this instance
     *
     * @return mixed
     */
    abstract public function __toString(): string;
}
