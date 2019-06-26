<?php
/**
 * Definition of CustomConstraint
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

/**
 * Class CustomConstraint
 *
 * Possible scenarios for using a custom constraint might be:
 *  - examining the strength of a user's pass phrase
 *  - ensuring the uniqueness of a chosen user name (by comparing the value against already stored information)
 *
 * @package FF\Forms\Fields\Constraints
 */
class CustomConstraint extends AbstractConstraint
{
    /**
     * @var callable
     */
    protected $validator;

    /**
     * @var string
     */
    protected $violationClass;

    /**
     * Generic constructor
     *
     * $validator must accept an AbstractValue instance as single argument and must return true,
     * if the value is considered valid, false otherwise.
     *
     * As $violationClass only descendents of AbstractViolation are accepted.
     *
     * @param callable $validator
     * @param string $violationClass
     */
    public function __construct(callable $validator, string $violationClass = InvalidValueViolation::class)
    {
        $this->setValidator($validator);
        $this->setViolationClass($violationClass);
    }

    /**
     * @return callable
     */
    public function getValidator(): callable
    {
        return $this->validator;
    }

    /**
     * @param callable $validator
     * @return $this
     */
    public function setValidator(callable $validator)
    {
        $this->validator = $validator;
        return $this;
    }

    /**
     * @return string
     */
    public function getViolationClass(): string
    {
        return $this->violationClass;
    }

    /**
     * @param string $violationClass
     * @return $this
     * @throws \InvalidArgumentException class name of an AbstractViolation descendent expected
     */
    public function setViolationClass(string $violationClass)
    {
        if (!is_a($violationClass, AbstractViolation::class, true)) {
            throw new \InvalidArgumentException(
                'class name of an ' . AbstractViolation::class . ' descendent expected, got [' . $violationClass . ']'
            );
        }

        $this->violationClass = $violationClass;
        return $this;
    }

    /**
     * Checks the given value violates the constraint's rules
     *
     * Passes the $value to the stored validator callback.
     *
     * @param AbstractValue $value
     * @return AbstractViolation|null
     */
    public function check(AbstractValue $value): ?AbstractViolation
    {
        if ($value->isEmpty()) {
            // empty values do not raise violations
            return null;
        }

        if (call_user_func($this->validator, $value)) {
            return null;
        }

        /** @var AbstractViolation $violation */
        $violation = new $this->violationClass($this, $value);
        return $violation;
    }
}
