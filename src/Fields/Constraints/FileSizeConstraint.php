<?php
/**
 * Definition of FileSizeConstraint
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields\Constraints;

use FF\Common\Utils\FileUtils;
use FF\Forms\Fields\Constraints\Violations\AbstractViolation;
use FF\Forms\Fields\Constraints\Violations\InvalidValueViolation;
use FF\Forms\Fields\Values\AbstractValue;
use FF\Forms\Fields\Values\UploadValue;

/**
 * Class FileSizeConstraint
 *
 * @package FF\Forms\Fields\Constraints
 */
class FileSizeConstraint extends AbstractConstraint
{
    /**
     * @var int
     */
    protected $maxSize;

    /**
     * Accepts either an integer value (number of bytes) or a shorthand byte value
     * for max size.
     * Examples for shorthand values:
     * - '100K' => 100 kilobytes <=> 100 * 1024 bytes
     * - '2M'   => 2 megabytes <=> 2 * 1024 * 1024 bytes
     *
     * @param int|string $maxSize
     * @see https://www.php.net/manual/en/faq.using.php#faq.using.shorthandbytes
     */
    public function __construct($maxSize)
    {
        $this->setMaxSize($maxSize);
    }

    /**
     * @param int|string $maxSize
     * @return $this
     * @throws \InvalidArgumentException expected an integer or valid shorthand bytes expression
     */
    public function setMaxSize($maxSize)
    {
        $this->maxSize = FileUtils::shorthandToBytes($maxSize);
        if (is_null($this->maxSize)) {
            throw new \InvalidArgumentException(
                'expected an integer or valid shorthand bytes expression, got [' . $maxSize . ']'
            );
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxSize(): int
    {
        return $this->maxSize;
    }

    /**
     * Checks the given value violates the constraint's rules
     *
     * @param AbstractValue $value
     * @return InvalidValueViolation|null
     */
    public function check(AbstractValue $value): ?AbstractViolation
    {
        if (!($value instanceof UploadValue) || $value->isEmpty()) {
            // non-upload or empty values do not raise violations
            return null;
        }
        if ($value->getValue()->getError() != UPLOAD_ERR_OK) {
            // do not raise file size violations if upload was not successful
            return null;
        }

        return ($value->getValue()->getSize() > $this->maxSize) ?
            new InvalidValueViolation($this, $value) :
            null;
    }
}