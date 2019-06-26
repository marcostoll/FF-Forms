<?php
/**
 * Definition of CheckboxField
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields;

use FF\Forms\Fields\Constraints\BoolConstraint;

/**
 * Class CheckboxField
 *
 * @package FF\Forms\Fields
 */
class CheckboxField extends TextField
{
    /**
     * A BoolConstraint is automatically added to every CheckboxField instance.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->addConstraint(new BoolConstraint());
    }

    /**
     * Checks whether this checkbox field is considered checked
     *
     * @return bool
     */
    public function isChecked(): bool
    {
        return filter_var($this->value->getPlain(), FILTER_VALIDATE_BOOLEAN);
    }
}
