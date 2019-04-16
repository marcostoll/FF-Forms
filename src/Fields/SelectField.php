<?php
/**
 * Definition of SelectField
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields;

use FF\Forms\Fields\Constraints\OptionsConstraint;
use FF\Utilities\ArrayUtils;

/**
 * Class SelectField
 *
 * @package FF\Forms\Fields
 */
class SelectField extends RadioField
{
    /**
     * Made protected to prevent outer manipulation
     * Converts $options to an associative array if necessary
     *
     * @param array $options
     * @return $this
     */
    protected function setOptions(array $options)
    {
        if (!empty($options) && !ArrayUtils::isAssoc($options)) {
            // enforce associative options
            $options = array_combine(array_values($options), array_values($options));
        }

        $this->options = $options;
        return $this;
    }

    /**
     * @return OptionsConstraint
     */
    protected function createConstraintFromOptions(): OptionsConstraint
    {
        return new OptionsConstraint(array_keys($this->options));
    }
}