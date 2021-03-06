<?php
/**
 * Definition of MimeTypeConstraint
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
use FF\Forms\Fields\Values\UploadValue;

/**
 * Class MimeTypeConstraint
 *
 * MimeTypeConstraint should only be used in conjunction with UploadValue instances.
 *
 * @package FF\Forms\Fields\Constraints
 * @see http://www.webmaster-toolkit.com/mime-types.shtml
 * @see http://www.freeformatter.com/mime-types-list.html#mime-types-list
 */
class MimeTypeConstraint extends AbstractConstraint
{
    /**
     * @var string[]
     */
    protected $acceptedTypes = [];

    /**
     * $acceptedTypes be an array of strings defining acceptable mime types.
     * Each string must be either
     * - a specific mime type (pattern [supertype]/[subtype], e.g. 'application/pdf' or
     * - a super mime type, e.g. 'image'
     * The validation will be case-sensitive.
     *
     * @param string ...$acceptedTypes
     */
    public function __construct(...$acceptedTypes)
    {
        $this->setAcceptedTypes($acceptedTypes);
    }

    /**
     * @return string[]
     */
    public function getAcceptedTypes(): array
    {
        return $this->acceptedTypes;
    }

    /**
     * @param string[] $acceptedTypes
     * @return $this
     */
    public function setAcceptedTypes(array $acceptedTypes)
    {
        $this->acceptedTypes = $acceptedTypes;
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
        if (empty($this->acceptedTypes)) {
            return null;
        }

        if (!($value instanceof UploadValue) || $value->isEmpty()) {
            // non-upload or empty values do not raise violations
            return null;
        }

        $uploadStructure = $value->getValue();
        $valueType = $uploadStructure->getType();
        if (!preg_match('~^([a-z-]+)/[a-z0-9.-]+$~', $valueType, $match)) {
            // no valid mime type pattern in upload value structure
            return new InvalidValueViolation($this, $value);
        }
        $superType = $match[1];

        return !$this->checkMatch($valueType, $superType) ? new InvalidValueViolation($this, $value) : null;
    }

    /**
     * Checks whether a matching mime type is configured
     *
     * @param string $fullType
     * @param string $superType
     * @return bool
     */
    protected function checkMatch(string $fullType, string $superType): bool
    {
        foreach ($this->acceptedTypes as $mimeType) {
            $superTypeOnly = (strstr($mimeType, '/') === false);
            if (!$superTypeOnly && $mimeType != $fullType) {
                continue;
            } elseif ($superTypeOnly && $mimeType != $superType) {
                continue;
            }

            return true;
        }

        return false;
    }
}
