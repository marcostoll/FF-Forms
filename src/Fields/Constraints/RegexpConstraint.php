<?php
/**
 * Definition of RegexpConstraint
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields\Constraints;

/**
 * Class RegexpConstraint
 *
 * @package FF\Forms\Fields\Constraints
 * @see http://php.net/filter-var
 * @see https://www.php.net/manual/en/filter.filters.validate.php
 * @see https://www.php.net/manual/en/book.pcre.php
 */
class RegexpConstraint extends FilterConstraint
{
    /**
     * @param string $pattern
     */
    public function __construct(string $pattern)
    {
        $options = [
            'options' => ['regexp' => $pattern]
        ];

        parent::__construct(FILTER_VALIDATE_REGEXP, $options);
    }
}
