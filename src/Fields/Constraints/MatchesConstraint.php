<?php
/**
 * Definition of MatchesConstraint
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields\Constraints;

use FF\Forms\Fields\AbstractField;
use FF\Forms\Fields\Constraints\Violations\AbstractViolation;
use FF\Forms\Fields\Constraints\Violations\InvalidValueViolation;
use FF\Forms\Fields\Values\AbstractValue;

/**
 * Class MinLengthConstraint
 *
 * @package FF\Forms\Fields\Constraints
 */
class MatchesConstraint extends AbstractConstraint
{
    /**
     * @var AbstractField
     */
    protected $matchingField;

    /**
     * @param AbstractField $matchingField
     */
    public function __construct(AbstractField $matchingField)
    {
        $this->setMatchingField($matchingField);
    }

    /**
     * @return AbstractField
     */
    public function getMatchingField(): AbstractField
    {
        return $this->matchingField;
    }

    /**
     * @param AbstractField $matchingField
     * @return $this
     */
    public function setMatchingField(AbstractField $matchingField)
    {
        $this->matchingField = $matchingField;
        return $this;
    }

    /**
     * Checks the given value violates the constraint's rules
     *
     * @param AbstractValue $value
     * @return InvalidValueViolation|null
     */
    public function check(AbstractValue $value): ?AbstractViolation
    {
        if ($value->isEmpty()) {
            // empty values do not raise violations
            return null;
        }

        $matchingValue = $this->matchingField->getValue();
        if (is_null($matchingValue)) {
            return new InvalidValueViolation($this, $value);
        }
        if (get_class($value) != get_class($matchingValue)) {
            return new InvalidValueViolation($this, $value);
        }

        return ($value->getValue() != $matchingValue->getValue()) ?
            new InvalidValueViolation($this, $value) :
            null;
    }
}
