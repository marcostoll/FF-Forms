<?php
/**
 * Definition of MultiSelectField
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields;

use FF\Forms\Fields\Values\AbstractValue;
use FF\Forms\Fields\Values\ArrayValue;

/**
 * Class MultiSelectField
 *
 * @package FF\Forms\Fields
 */
class MultiSelectField extends SelectField
{
    /**
     * {@inheritdoc}
     *
     * @return ArrayValue|null
     */
    public function getValue()
    {
        /** @var ArrayValue|null $value */
        $value = parent::getValue();
        return $value;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getPlain()
    {
        return $this->hasValue() ? $this->value->getPlain() : [];
    }

    /**
     * {@inheritdoc}
     *
     * @return ArrayValue
     */
    public function getDefaultValue(): AbstractValue
    {
        return new ArrayValue();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedValueClass(): string
    {
        return ArrayValue::class;
    }
}