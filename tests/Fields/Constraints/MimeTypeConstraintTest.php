<?php
/**
 * Definition of MimeTypeConstraintTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields\Values\Constraints;

use FF\Forms\Fields\Constraints\MimeTypeConstraint;
use FF\Forms\Fields\Constraints\Violations\InvalidValueViolation;
use FF\Forms\Fields\Values\ArrayValue;
use FF\Forms\Fields\Values\ScalarValue;
use FF\Forms\Fields\Values\UploadValue;
use PHPUnit\Framework\TestCase;

/**
 * Test MimeTypeConstraintTest
 *
 * @package FF\Tests
 */
class MimeTypeConstraintTest extends TestCase
{
    /**
     * @var MimeTypeConstraint
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new MimeTypeConstraint();
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetAcceptedTypes()
    {
        $value = ['image', 'application/pdf'];
        $same = $this->uut->setAcceptedTypes($value);
        $this->assertSame($this->uut, $same);
        $this->assertEquals($value, $this->uut->getAcceptedTypes());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckNoTypes()
    {
        $this->assertNull($this->uut->check(new UploadValue()));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckEmpty()
    {
        $this->uut->setAcceptedTypes(['image', 'application/pdf']);
        $this->assertNull($this->uut->check(new UploadValue(['error' => UPLOAD_ERR_NO_FILE, 'type' => 'foo/bar'])));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckSuperType()
    {
        $imgFile = ['error' => UPLOAD_ERR_OK, 'type' => 'image/jpeg'];

        $this->uut->setAcceptedTypes(['image']);
        $this->assertNull($this->uut->check(new UploadValue($imgFile)));

        $this->uut->setAcceptedTypes(['video']);
        $this->assertInstanceOf(InvalidValueViolation::class, $this->uut->check(new UploadValue($imgFile)));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckFullType()
    {
        $imgFile = ['error' => UPLOAD_ERR_OK, 'type' => 'image/jpeg'];

        $this->uut->setAcceptedTypes(['image/jpeg']);
        $this->assertNull($this->uut->check(new UploadValue($imgFile)));

        $this->uut->setAcceptedTypes(['image/png']);
        $this->assertInstanceOf(InvalidValueViolation::class, $this->uut->check(new UploadValue($imgFile)));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckErrorTypePattern()
    {
        $imgFile = ['error' => UPLOAD_ERR_OK, 'type' => 'foo'];

        $this->uut->setAcceptedTypes(['image/png']);
        $this->assertInstanceOf(InvalidValueViolation::class, $this->uut->check(new UploadValue($imgFile)));
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
