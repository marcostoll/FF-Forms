<?php
/**
 * Definition of RadioField
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields;

use FF\Forms\Fields\Constraints\OptionsConstraint;

/**
 * Class RadioField
 *
 * @package FF\Forms\Fields
 */
class RadioField extends TextField
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * A OptionsConstraint is automatically added to every RadioField instance.
     *
     * @param string $name
     * @param array $options
     */
    public function __construct(string $name, array $options)
    {
        parent::__construct($name);
        $this->setOptions($options)
            ->addConstraint($this->createConstraintFromOptions());
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Made protected to prevent outer manipulation
     * Converts $options to an associative array if necessary
     *
     * @param array $options
     * @return $this
     */
    protected function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return OptionsConstraint
     */
    protected function createConstraintFromOptions(): OptionsConstraint
    {
        return new OptionsConstraint($this->options);
    }
}