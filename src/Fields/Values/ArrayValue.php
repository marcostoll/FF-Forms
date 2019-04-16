<?php
/**
 * Definition of ArrayValue
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields\Values;

/**
 * Class ArrayValue
 *
 * @package FF\Forms\Fields\Values
 */
class ArrayValue extends AbstractValue
{
    /**
     * @var array
     */
    protected $value;

    /**
     * @param array $value
     */
    public function __construct(array $value = [])
    {
        $this->setValue($value);
    }

    /**
     * {@inheritdoc}
     *
     * @param array $value
     */
    public function setValue($value)
    {
        parent::setValue($value);
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getValue()
    {
        return parent::getValue();
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getPlain()
    {
        return parent::getPlain();
    }

    /**
     * {@inheritdoc}
     *
     * A value consisting solely of empty strings is considered empty.
     */
    public function isEmpty(): bool
    {
        if (empty($this->value)) return true;

        return strlen(implode('', $this->value)) == 0;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return json_encode($this->value);
    }

    /**
     * Retrieves an array representation of this instance
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->value;
    }
}