<?php
/**
 * Definition of FileField
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields;

use FF\Forms\Fields\Constraints\UploadedFileConstraint;
use FF\Forms\Fields\Values\AbstractValue;
use FF\Forms\Fields\Values\UploadValue;
use FF\Forms\Fields\Values\UploadValueStructure;

/**
 * Class FileField
 *
 * @package FF\Forms\Fields
 */
class FileField extends AbstractField
{
    /**
     * @var UploadValue|null
     */
    protected $value;

    /**
     * A UploadedFileConstraint is automatically added to every FileField instance.
     *
     * $uploadedFileValidator must be a callback function accepting a single argument
     * (the temporary name of an uploaded file) and returning a boolean value.
     *
     * If $uploadedFileValidator is omitted php's build in is_uploaded_file() function is used instead.
     *
     * @param string $name
     * @param callable $uploadedFileValidator
     * @see http://php.net/is_uploaded_file
     */
    public function __construct(string $name, callable $uploadedFileValidator = null)
    {
        $this->setName($name)
            ->addConstraint(new UploadedFileConstraint($uploadedFileValidator));
    }

    /**
     * {@inheritdoc}
     *
     * @return UploadValue|null
     */
    public function getValue()
    {
        /** @var UploadValue|null $value */
        $value = parent::getValue();
        return $value;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getPlain()
    {
        return $this->hasValue() ? $this->value->getPlain() : (new UploadValue())->getPlain();
    }

    /**
     * {@inheritdoc}
     *
     * @return UploadValue
     */
    public function getDefaultValue(): AbstractValue
    {
        return new UploadValue();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedValueClass(): string
    {
        return UploadValue::class;
    }



    /**
     * {@inheritdoc}
     *
     * @param array
     * @return UploadValue
     */
    protected function makeValue($plainValue): AbstractValue
    {
        if (is_scalar($plainValue)) {
            // use as file name
            $plainValue = ['name' => (string)$plainValue];
        }

        return new UploadValue($plainValue);
    }

    /**
     * Retrieves the original name of the uploaded file (if any)
     *
     * @return string|null
     */
    public function getFileName()
    {
        if (!$this->hasValue()) return null;
        return $this->value->getValue()->getName();
    }

    /**
     * Retrieves the mime type of the uploaded file (if any) as send by the client
     *
     * @return string|null
     */
    public function getFileType()
    {
        if (!$this->hasValue()) return null;
        return $this->value->getValue()->getType();
    }

    /**
     * Retrieves the size in bytes of the uploaded file (if any)
     *
     * @return int|null
     */
    public function getFileSize()
    {
        if (!$this->hasValue()) return null;
        return $this->value->getValue()->getSize();
    }

    /**
     * Retrieves the temporary name of the uploaded file (if any)
     *
     * @return string|null
     */
    public function getFileTmpName()
    {
        if (!$this->hasValue()) return null;
        return $this->value->getValue()->getTmpName();
    }

    /**
     * Retrieves the upload error status (if any)
     *
     * @return int|null
     */
    public function getFileError()
    {
        if (!$this->hasValue()) return null;
        return $this->value->getValue()->getError();
    }
}