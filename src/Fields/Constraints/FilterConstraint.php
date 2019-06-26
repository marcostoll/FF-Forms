<?php
/**
 * Definition of FilterConstraint
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields\Constraints;

use FF\Forms\Fields\Constraints\Violations\AbstractViolation;
use FF\Forms\Fields\Constraints\Violations\InvalidValueViolation;
use FF\Forms\Fields\Values\AbstractValue;
use FF\Forms\Fields\Values\ScalarValue;

/**
 * Class FilterConstraint
 *
 * Wraps php's filter_var() functionality.
 * Can be used for different purposes, e.g. emails, urls, ip addresses.
 *
 * BEWARE: Be sure to read the notes to filter_var(),
 * especially when using email or url validation in critical environments!
 *
 * @package FF\Forms\Fields\Constraints
 * @see http://php.net/filter-var
 * @see https://www.php.net/manual/en/filter.filters.validate.php
 */
class FilterConstraint extends AbstractConstraint
{
    /**
     * @var int
     * @see http://php.net/manual/en/filter.filters.validate.php
     */
    protected $filter;

    /**
     * @var mixed|null
     * @see http://php.net/manual/en/filter.filters.validate.php
     */
    protected $options;

    /**
     * @param int $filter
     * @param mixed $options
     */
    public function __construct(int $filter, $options = null)
    {
        $this->setFilter($filter)
            ->setOptions($options);
    }

    /**
     * @return int
     */
    public function getFilter(): int
    {
        return $this->filter;
    }

    /**
     * @param int $filter
     * @return $this
     */
    public function setFilter(int $filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed|null $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Checks the given value violates the constraint's rules
     *
     * @param AbstractValue $value
     * @return InvalidValueViolation|null
     */
    public function check(AbstractValue $value): ?AbstractViolation
    {
        if (!($value instanceof ScalarValue) || $value->isEmpty()) {
            // complex or empty values do not raise violations
            return null;
        }

        $filteredValue = filter_var($value, $this->filter, $this->options);
        if (!is_null($filteredValue) && $filteredValue !== false) {
            return null;
        }

        return new InvalidValueViolation($this, $value);
    }
}
