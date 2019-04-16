<?php
/**
 * Definition of UploadValue
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields\Values;

/**
 * Class UploadValue
 *
 * @package FF\Forms\Fields\Values
 */
class UploadValue extends AbstractValue
{
    /**
     * @var UploadValueStructure
     */
    protected $value;

    /**
     * @param UploadValueStructure|array $value
     */
    public function __construct($value = null)
    {
        if (is_null($value)) {
            $value = new UploadValueStructure();
        } elseif (is_array($value)) {
            $value = new UploadValueStructure($value);
        }
        $this->setValue($value);
    }

    /**
     * {@inheritdoc}
     *
     * @param UploadValueStructure $value
     */
    public function setValue($value)
    {
        parent::setValue($value);
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return UploadValueStructure
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
        return $this->value->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty(): bool
    {
        return $this->value->getError() == UPLOAD_ERR_NO_FILE;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return $this->value->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return json_encode($this->toArray());
    }
}