<?php
/**
 * Definition of FileSizeConstraintTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields\Values\Constraints;

use FF\Forms\Fields\Constraints\FileSizeConstraint;
use FF\Forms\Fields\Constraints\Violations\InvalidValueViolation;
use FF\Forms\Fields\Values\ArrayValue;
use FF\Forms\Fields\Values\ScalarValue;
use FF\Forms\Fields\Values\UploadValue;
use PHPUnit\Framework\TestCase;

/**
 * Test FileSizeConstraintTest
 *
 * @package FF\Tests
 */
class FileSizeConstraintTest extends TestCase
{
    /**
     * @var FileSizeConstraint
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new FileSizeConstraint('1K');
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetMaxSize()
    {
        $value = '1K';
        $same = $this->uut->setMaxSize($value);
        $this->assertSame($this->uut, $same);
        $this->assertEquals(1 * 1024, $this->uut->getMaxSize());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetMaxSizeInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->uut->setMaxSize('foo');
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheck()
    {
        $this->assertNull($this->uut->check(new UploadValue(['size' => 666, 'error' => UPLOAD_ERR_OK])));
        $this->assertNull($this->uut->check(new UploadValue(['size' => 666, 'error' => UPLOAD_ERR_EXTENSION])));

        $invalidUpload = ['size' => 1025, 'error' => UPLOAD_ERR_OK];
        $this->assertInstanceOf(InvalidValueViolation::class, $this->uut->check(new UploadValue($invalidUpload)));
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
        $this->assertNull($this->uut->check(new ArrayValue(['foo'])));
        $this->assertNull($this->uut->check(new ScalarValue('foo')));
    }
}
