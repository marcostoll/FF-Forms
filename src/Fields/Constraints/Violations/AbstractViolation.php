<?php
/**
 * Definition of AbstractViolation
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields\Constraints\Violations;

use FF\Forms\Fields\Constraints\AbstractConstraint;
use FF\Forms\Fields\Values\AbstractValue;
use FF\Utils\ClassUtils;

/**
 * Class AbstractViolation
 *
 * @package FF\Forms\Fields\Constraints\Violations
 */
abstract class AbstractViolation
{
    /**
     * @var AbstractConstraint
     */
    protected $constraint;

    /**
     * @var AbstractValue
     */
    protected $value;

    /**
     * @param AbstractConstraint $constraint
     * @param AbstractValue $value
     */
    public function __construct(AbstractConstraint $constraint, AbstractValue $value)
    {
        $this->setConstraint($constraint)
            ->setValue($value);
    }

    /**
     * @return AbstractConstraint
     */
    public function getConstraint(): AbstractConstraint
    {
        return $this->constraint;
    }

    /**
     * @param AbstractConstraint $constraint
     * @return $this
     */
    public function setConstraint(AbstractConstraint $constraint)
    {
        $this->constraint = $constraint;
        return $this;
    }

    /**
     * @return AbstractValue
     */
    public function getValue(): AbstractValue
    {
        return $this->value;
    }

    /**
     * @param AbstractValue $value
     * @return $this
     */
    public function setValue(AbstractValue $value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Retrieves a string representation if this instance
     *
     * @return mixed
     */
    public function __toString(): string
    {
        return ClassUtils::getLocalClassName(get_class($this->constraint))
            . 'is violated by value [' . $this->value . ']';
    }
}