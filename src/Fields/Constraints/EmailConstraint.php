<?php
/**
 * Definition of EmailConstraint
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields\Constraints;

/**
 * Class EmailConstraint
 *
 * @package FF\Forms\Fields\Constraints
 * @see http://php.net/filter-var
 * @see https://www.php.net/manual/en/filter.filters.validate.php
 */
class EmailConstraint extends FilterConstraint
{
    /**
     * @param mixed $options
     */
    public function __construct($options = null)
    {
        parent::__construct(FILTER_VALIDATE_EMAIL, $options);
    }
}