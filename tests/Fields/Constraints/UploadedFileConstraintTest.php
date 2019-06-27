<?php
/**
 * Definition of UploadedConstraintTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields\Values\Constraints;

use FF\Forms\Fields\Constraints\UploadedFileConstraint;
use FF\Forms\Fields\Constraints\Violations\AbstractViolation;
use FF\Forms\Fields\Values\ArrayValue;
use FF\Forms\Fields\Values\ScalarValue;
use FF\Forms\Fields\Values\UploadValue;
use PHPUnit\Framework\TestCase;

/**
 * Test UploadedConstraintTest
 *
 * @package FF\Tests
 */
class UploadedConstraintTest extends TestCase
{
    /**
     * @var UploadedFileConstraint
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new UploadedFileConstraint(function (string $tmpFileName) {
            return $tmpFileName == 'foo';
        });
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetValidator()
    {
        $value = function (string $tmpFileName) {
            return $tmpFileName == 'foo';
        };
        $same = $this->uut->setValidator($value);
        $this->assertSame($this->uut, $same);
        $this->assertEquals($value, $this->uut->getValidator());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheck()
    {
        $uploadOk = new UploadValue(['tmp_name' => 'foo', 'error' => UPLOAD_ERR_OK]);
        $this->assertNull($this->uut->check($uploadOk));
        $uploadInvalid = new UploadValue(['tmp_name' => 'bar', 'error' => UPLOAD_ERR_OK]);
        $this->assertInstanceOf(AbstractViolation::class, $this->uut->check($uploadInvalid));

        $iniSizeError = new UploadValue(['tmp_name' => 'foo', 'error' => UPLOAD_ERR_INI_SIZE]);
        $this->assertInstanceOf(AbstractViolation::class, $this->uut->check($iniSizeError));
        $formSizeError = new UploadValue(['tmp_name' => 'foo', 'error' => UPLOAD_ERR_FORM_SIZE]);
        $this->assertInstanceOf(AbstractViolation::class, $this->uut->check($formSizeError));
        $extError = new UploadValue(['tmp_name' => 'foo', 'error' => UPLOAD_ERR_EXTENSION]);
        $this->assertInstanceOf(AbstractViolation::class, $this->uut->check($extError));
        $partialError = new UploadValue(['tmp_name' => 'foo', 'error' => UPLOAD_ERR_PARTIAL]);
        $this->assertInstanceOf(AbstractViolation::class, $this->uut->check($partialError));
        $tmpDirError = new UploadValue(['tmp_name' => 'foo', 'error' => UPLOAD_ERR_NO_TMP_DIR]);
        $this->assertInstanceOf(AbstractViolation::class, $this->uut->check($tmpDirError));
        $writeError = new UploadValue(['tmp_name' => 'foo', 'error' => UPLOAD_ERR_CANT_WRITE]);
        $this->assertInstanceOf(AbstractViolation::class, $this->uut->check($writeError));
        $unknownError = new UploadValue(['tmp_name' => 'foo', 'error' => 666]);
        $this->assertInstanceOf(AbstractViolation::class, $this->uut->check($unknownError));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckEmpty()
    {
        $this->assertNull($this->uut->check(new UploadValue()));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckUnsuited()
    {
        $this->assertNull($this->uut->check(new ScalarValue('foo')));
        $this->assertNull($this->uut->check(new ArrayValue(['foo'])));
    }
}
