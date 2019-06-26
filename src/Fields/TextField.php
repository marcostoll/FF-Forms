<?php
/**
 * Definition of TextField
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields;

use FF\Forms\Fields\Values\AbstractValue;
use FF\Forms\Fields\Values\ScalarValue;

/**
 * Class TextField
 *
 * @package FF\Forms\Fields
 */
class TextField extends AbstractField
{
    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->setName($name);
    }

    /**
     * {@inheritdoc}
     *
     * @return ScalarValue|null
     */
    public function getValue()
    {
        /** @var ScalarValue|null $value */
        $value = parent::getValue();
        return $value;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getPlain()
    {
        return parent::getPlain();
    }

    /**
     * {@inheritdoc}
     *
     * @return ScalarValue
     */
    public function getDefaultValue(): AbstractValue
    {
        return new ScalarValue();
    }

    /**
     * {@inheritdoc}
     *
     * @param string
     * @return ScalarValue
     */
    protected function makeValue($plainValue): AbstractValue
    {
        if (is_array($plainValue)) {
            // use first array element
            $plainValue = (string)array_shift($plainValue);
        }

        return new ScalarValue($plainValue);
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedValueClass(): string
    {
        return ScalarValue::class;
    }
}
