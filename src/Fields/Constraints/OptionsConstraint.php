<?php
/**
 * Definition of OptionsConstraint
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
use FF\Forms\Fields\Values\ArrayValue;
use FF\Forms\Fields\Values\ScalarValue;
use FF\Forms\Fields\Values\UploadValue;

/**
 * Class OptionsConstraint
 *
 * @package FF\Forms\Fields\Constraints
 */
class OptionsConstraint extends AbstractConstraint
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->setOptions($options);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
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
        if (empty($this->options)) return null;

        if ($value->isEmpty()) return null; // empty values do not raise violations

        switch (get_class($value)) {
            case ScalarValue::class:
                if (in_array($value->getValue(), $this->options)) return null;
                break;
            case ArrayValue::class:
                if (empty(array_diff($value->getValue(), $this->options))) return null;
                break;
            case UploadValue::class:
                // no break -> proceed with next
            default:
                return null;
        }

        return new InvalidValueViolation($this, $value);
    }
}