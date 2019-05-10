<?php
/**
 * Definition of AbstractField
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields;

use FF\Forms\Fields\Constraints\AbstractConstraint;
use FF\Forms\Fields\Constraints\ConstraintsFactory;
use FF\Forms\Fields\Constraints\RequiredConstraint;
use FF\Forms\Fields\Constraints\Violations\AbstractViolation;
use FF\Forms\Fields\Constraints\Violations\InvalidValueViolation;
use FF\Forms\Fields\Values\AbstractValue;
use FF\Utilities\Factories\Exceptions\ClassNotFoundException;

/**
 * Class AbstractField
 *
 * @method $this bool()
 * @method $this custom(callable $validator, string $violationClass = InvalidValueViolation::class)
 * @method $this email(mixed $options = null)
 * @method $this fileSize(int|string $maxSize)
 * @method $this filter(int $filter, mixed $options = null)
 * @method $this honeyPot()
 * @method $this int(int $minValue = null, int $maxValue = null, int $flags = null)
 * @method $this matches(AbstractField $matchingField)
 * @method $this maxLength(int $maxLength)
 * @method $this mimeType(string[] $acceptedTypes)
 * @method $this minLength(int $minLength)
 * @method $this options(array $options)
 * @method $this regexp(string $pattern)
 * @method $this required()
 * @method $this uploadedFile(callable $uploadedFileValidator = null)
 *
 * @package FF\Forms\Fields
 */
abstract class AbstractField
{
    /**
     * Suffix for all suitable constraint classes
     */
    const CONSTRAINT_CLASS_SUFFIX = 'Constraint';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var AbstractValue|null
     */
    protected $value;

    /**
     * @var AbstractConstraint[]
     */
    protected $constraints = [];

    /**
     * @var AbstractViolation
     */
    protected $violation;

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param AbstractValue|string|array $value
     * @return $this
     * @throws \InvalidArgumentException expects values of type {descendent of AbstractValue}
     */
    public function setValue($value)
    {
        if (!($value instanceof AbstractValue)) {
            $value = $this->makeValue($value);
        }
        if (!$this->acceptsValue($value)) {
            throw new \InvalidArgumentException(
                'expects values of type [' . $this->getExpectedValueClass() . '], got [' . get_class($value) . ']'
            );
        }
        $this->value = $value;
        return $this;
    }

    /**
     * @return AbstractValue|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Retrieves the plain value
     *
     * @return string|array
     */
    public function getPlain()
    {
        return $this->hasValue() ? $this->value->getPlain() : '';
    }

    /**
     * Checks whether a value was set
     *
     * @return bool
     */
    public function hasValue(): bool
    {
        return !is_null($this->value);
    }

    /**
     * Retrieves a default value suitable for tis instance
     *
     * @return AbstractValue
     */
    public abstract function getDefaultValue(): AbstractValue;

    /**
     * Retrieves a suitable value object representing the given plain value
     *
     * @param string|array
     * @return AbstractValue
     */
    protected abstract function makeValue($plainValue): AbstractValue;

    /**
     * Retrieves the class name of the value class to use for this type of field
     *
     * @return string
     */
    protected abstract function getExpectedValueClass(): string;

    /**
     * Checks whether the given value meets the expected value class restriction
     *
     * @param AbstractValue $value
     * @return bool
     */
    protected function acceptsValue(AbstractValue $value): bool
    {
        return is_a($this->getExpectedValueClass(), get_class($value), true);
    }

    /**
     * @param AbstractConstraint[] $constraints
     * @return $this
     */
    public function setConstraints(array $constraints)
    {
        $this->constraints = $constraints;
        return $this;
    }

    /**
     * @return AbstractConstraint[]
     */
    public function getConstraints(): array
    {
        return $this->constraints;
    }

    /**
     * @param AbstractConstraint[] $constraints
     * @return $this
     */
    public function addConstraint(AbstractConstraint ...$constraints)
    {
        foreach ($constraints as $constraint) {
            $this->constraints[] = $constraint;
        }

        return $this;
    }

    /**
     * Removes each constraint that is an instance of the class
     *
     * @param string $constraintClass
     * @return $this
     */
    public function removeConstraint(string $constraintClass)
    {
        $filter = function (AbstractConstraint $constraint) use ($constraintClass) {
            return get_class($constraint) != $constraintClass;
        };

        $this->constraints = array_filter($this->constraints, $filter);
        return $this;
    }

    /**
     * Convenient method for checking if a RequiredConstraint was added to this field
     *
     * @return bool
     */
    public function isRequired(): bool
    {
        foreach ($this->constraints as $constraint) {
            if ($constraint instanceof RequiredConstraint) return true;
        }

        return false;
    }

    /**
     * @return AbstractViolation|null
     */
    public function getViolation()
    {
        return $this->violation;
    }

    /**
     * Checks if a constraint violation was detected during last validation
     *
     * @return boolean
     */
    public function hasViolation()
    {
        return !is_null($this->violation);
    }

    /**
     * Checks if current value passes all value constraints
     *
     * Constraints will be checked in the order they were added to the field.
     * Halts checking additional constraints on first violation.
     *
     * If no value is set to the field, the field is considered in a valid state.
     *
     * @return bool
     */
    public function isValid(): bool
    {
        $this->violation = null; // clear last violation (if any)

        if (!$this->hasValue()) return true;

        foreach ($this->constraints as $constraint) {
            if (!$this->value->meetsConstraint($constraint, $this->violation)) return false;
        }

        return true;
    }

    /**
     * Resets the field
     *
     * Resets both the field's value and any constraint violation.
     *
     * @return $this
     */
    public function reset()
    {
        $this->value = null;
        $this->violation = null;
        return $this;
    }

    // <editor-fold defaultstate="collapsed" desc="[ Magic API ]">

    /**
     * Magic wrapper for adding constraints to a field
     *
     * Use the first part of the constraints class name (stripping the 'Constraint' suffix)
     * with a lower-cased first letter as magic method.
     * Any additional arguments passed to the magic method will be passed to the constraint's constructor
     * in the given order.
     *
     * Examples:
     * <code>
     * $myField->required()     <=> $myField->addConstraint(new \FF\Forms\Constraints\RequiredConstraint())
     * $myField->minLength(8)   <=> $myField->addConstraint(new \FF\Forms\Constraints\MinLengthConstraint(8))
     * </code>
     *
     * Can be chained.
     *
     * Example:
     * <code>
     * $myField->required()->minLength(8);
     * </code>
     *
     * @param string $method
     * @param array $args
     * @return $this
     * @throws ClassNotFoundException
     */
    public function __call($method, array $args)
    {
        try {
            $className = ucfirst($method) . self::CONSTRAINT_CLASS_SUFFIX;

            return $this->addConstraint(ConstraintsFactory::getInstance()->create($className, ...$args));
        } catch (ClassNotFoundException $e) {
            // trigger fatal error: unsupported method call
            // mimic standard php error message
            // Fatal error: Call to undefined method {class}::{method}() in {file} on line {line}
            $backTrace = debug_backtrace();
            $errorMsg = 'Call to undefined method ' . __CLASS__ . '::' . $method . '() '
                . 'in ' . $backTrace[0]['file'] . ' on line ' . $backTrace[0]['line'];
            trigger_error($errorMsg, E_USER_ERROR);

            return $this;
        }
    }

    // </editor-fold>
}