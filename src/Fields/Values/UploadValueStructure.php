<?php
/**
 * Definition of UploadValueStructure
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields\Values;

/**
 * Class UploadValueStructure
 *
 * @package FF\Forms\Fields\Values
 */
class UploadValueStructure
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var
     */
    protected $type = '';

    /**
     * @var int
     */
    protected $size = 0;

    /**
     * @var string
     */
    protected $tmpName = '';

    /**
     * @var int
     */
    protected $error = UPLOAD_ERR_NO_FILE;

    /**
     * @param array $value
     */
    public function __construct(array $value = [])
    {
        $this->setFromArray($value);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     * @return $this
     */
    public function setSize(int $size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return string
     */
    public function getTmpName(): string
    {
        return $this->tmpName;
    }

    /**
     * @param string $tmpName
     * @return $this
     */
    public function setTmpName(string $tmpName)
    {
        $this->tmpName = $tmpName;
        return $this;
    }

    /**
     * @return int
     */
    public function getError(): int
    {
        return $this->error;
    }

    /**
     * @param int $error
     * @return $this
     */
    public function setError(int $error)
    {
        $this->error = $error;
        return $this;
    }

    /**
     * Extracts scalar data from the given upload information structure
     *
     * Expects an array using the typical upload keys and value types.
     * Non-matching upload info structures will be ignored.
     *
     * @param array $uploadInfo
     * @return $this
     * @see https://www.php.net/manual/en/features.file-upload.post-method.php
     */
    public function setFromArray(array $uploadInfo)
    {
        $this->name = $uploadInfo['name'] ?? '';
        $this->type = $uploadInfo['type'] ?? '';
        $this->size = $uploadInfo['size'] ?? 0;
        $this->tmpName = $uploadInfo['tmp_name'] ?? '';
        $this->error = $uploadInfo['error'] ?? UPLOAD_ERR_NO_FILE;

        return $this;
    }

    /**
     * Retrieves an array representation of this instance
     *
     * @return array
     * @see https://www.php.net/manual/en/features.file-upload.post-method.php
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'size' => $this->size,
            'tmp_name' => $this->tmpName,
            'error' => $this->error
        ];
    }
}