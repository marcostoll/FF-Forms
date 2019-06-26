<?php
/**
 * Definition of FileFieldTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields;

use FF\Forms\Fields\Constraints\UploadedFileConstraint;
use FF\Forms\Fields\FileField;
use FF\Forms\Fields\Values\ArrayValue;
use FF\Forms\Fields\Values\UploadValue;
use PHPUnit\Framework\TestCase;

/**
 * Test FileFieldTest
 *
 * @package FF\Tests
 */
class FileFieldTest extends TestCase
{
    /**
     * @var FileField
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new FileField('foo', function (string $tmpFileName) {
            return $tmpFileName == 'foo';
        });
    }

    /**
     * Tests the namesake method/feature
     */
    public function testAutoConstraints()
    {
        foreach ($this->uut->getConstraints() as $constraint) {
            if ($constraint instanceof UploadedFileConstraint) {
                $this->assertTrue(true);
                return;
            }
        }

        $this->fail('no UploadedFileConstraint detected');
    }

    /**
     * Tests the namesake method/feature
     */
    public function testPlain()
    {
        $this->assertIsArray($this->uut->reset()->getPlain());
        $plain = $this->uut->setValue('foo')->getPlain();
        $this->assertEquals('foo', $plain['name']);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetValue()
    {
        $uploadEmpty = new UploadValue();
        $this->assertSame($uploadEmpty, $this->uut->setValue($uploadEmpty)->getValue());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testIsValid()
    {
        $uploadEmpty = new UploadValue();
        $this->assertTrue($this->uut->setValue($uploadEmpty)->isValid());

        $uploadOk = new UploadValue(['tmp_name' => 'foo', 'error' => UPLOAD_ERR_OK]);
        $this->assertTrue($this->uut->setValue($uploadOk)->isValid());

        $uploadFileError = new UploadValue(['tmp_name' => 'bar', 'error' => UPLOAD_ERR_OK]);
        $this->assertFalse($this->uut->setValue($uploadFileError)->isValid());

        $uploadCodeError = new UploadValue(['tmp_name' => 'foo', 'error' => UPLOAD_ERR_FORM_SIZE]);
        $this->assertFalse($this->uut->setValue($uploadCodeError)->isValid());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetters()
    {
        $this->assertNull($this->uut->getFileName());
        $this->assertNull($this->uut->getFileSize());
        $this->assertNull($this->uut->getFileType());
        $this->assertNull($this->uut->getFileTmpName());
        $this->assertNull($this->uut->getFileError());

        $upload = new UploadValue([
            'name' => 'foo.pdf',
            'size' => 1024,
            'type' => 'application/pdf',
            'tmp_name' => 'bar',
            'error' => UPLOAD_ERR_OK
        ]);

        $this->uut->setValue($upload);
        $this->assertEquals($upload->getValue()->getName(), $this->uut->getFileName());
        $this->assertEquals($upload->getValue()->getSize(), $this->uut->getFileSize());
        $this->assertEquals($upload->getValue()->getType(), $this->uut->getFileType());
        $this->assertEquals($upload->getValue()->getTmpName(), $this->uut->getFileTmpName());
        $this->assertEquals($upload->getValue()->getError(), $this->uut->getFileError());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetDefaultValue()
    {
        $this->assertInstanceOf(UploadValue::class, $this->uut->getDefaultValue());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetValueInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->uut->setValue(new ArrayValue());
    }
}
