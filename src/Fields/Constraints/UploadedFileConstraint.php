<?php
/**
 * Definition of UploadedFileConstraint
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields\Constraints;

use FF\Forms\Fields\Constraints\Violations\AbstractViolation;
use FF\Forms\Fields\Constraints\Violations\ConfigurationViolation;
use FF\Forms\Fields\Constraints\Violations\InvalidValueViolation;
use FF\Forms\Fields\Constraints\Violations\SystemStateViolation;
use FF\Forms\Fields\Values\AbstractValue;
use FF\Forms\Fields\Values\UploadValue;

/**
 * Class UploadedFileConstraint
 *
 * @package FF\Forms\Fields\Constraints
 */
class UploadedFileConstraint extends AbstractConstraint
{
    /**
     * @var callable
     */
    protected $uploadedFileValidator;

    /**
     * $uploadedFileValidator must be a callback function accepting a single argument
     * (the temporary name of an uploaded file) and returning a boolean value.
     *
     * If $uploadedFileValidator is omitted php's build in is_uploaded_file() function is used instead.
     *
     * @param callable $uploadedFileValidator
     * @see http://php.net/is_uploaded_file
     */
    public function __construct(callable $uploadedFileValidator = null)
    {
        if (is_null($uploadedFileValidator)) {
            $uploadedFileValidator = function (string $tmpFileName) {
                return is_uploaded_file($tmpFileName);
            };
        }

        $this->setUploadedFileValidator($uploadedFileValidator);
    }

    /**
     * @return callable
     */
    public function getUploadedFileValidator(): callable
    {
        return $this->uploadedFileValidator;
    }

    /**
     * @param callable $uploadedFileValidator
     * @return $this
     */
    public function setUploadedFileValidator(callable $uploadedFileValidator)
    {
        $this->uploadedFileValidator = $uploadedFileValidator;
        return $this;
    }

    /**
     * Checks the given value violates the constraint's rules
     *
     * @param AbstractValue $value
     * @return AbstractViolation|null
     */
    public function check(AbstractValue $value): ?AbstractViolation
    {
        if (!($value instanceof UploadValue) || $value->isEmpty()) {
            // non-upload or empty values do not raise violations
            return null;
        }

        switch ($value->getValue()->getError()) {
            case UPLOAD_ERR_OK:
                if (!call_user_func($this->uploadedFileValidator, $value->getValue()->getTmpName())) {
                    return new InvalidValueViolation($this, $value);
                }
                return null;
            case UPLOAD_ERR_NO_FILE:
                // treat empty uploads as valid (@see RequiredValidator)
                return null;
            case UPLOAD_ERR_INI_SIZE:
                //file size exceeds the upload_max_filesize directive in php configuration
                return new ConfigurationViolation($this, $value);
            case UPLOAD_ERR_FORM_SIZE:
                // file size exceeds the MAX_FILE_SIZE directive 'specified in the HTML form configuration
                return new ConfigurationViolation($this, $value);
            case UPLOAD_ERR_EXTENSION:
                // file upload stopped by php extension
                return new ConfigurationViolation($this, $value);
            case UPLOAD_ERR_PARTIAL:
                // file was only uploaded partially
                return new SystemStateViolation($this, $value);
            case UPLOAD_ERR_NO_TMP_DIR:
                // temporary folder missing
                return new SystemStateViolation($this, $value);
            case UPLOAD_ERR_CANT_WRITE:
                // failed to write to temporary files directory
                return new SystemStateViolation($this, $value);
            default:
                // unknown upload error
                return new SystemStateViolation($this, $value);
        }
    }
}
