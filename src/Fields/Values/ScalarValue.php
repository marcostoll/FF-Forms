<?php
/**
 * Definition of ScalarValue
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields\Values;

/**
 * Class ScalarValue
 *
 * @package FF\Forms\Fields\Values
 */
class ScalarValue extends AbstractValue
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @param string $value
     */
    public function __construct(string $value = '')
    {
        $this->setValue($value);
    }

    /**
     * {@inheritdoc}
     *
     * @param string $value
     */
    public function setValue($value)
    {
        parent::setValue($value);
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getValue()
    {
        return parent::getValue();
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
     */
    public function isEmpty(): bool
    {
        return strlen($this->value) == 0;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return $this->value;
    }
}