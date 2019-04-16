<?php
/**
 * Definition of RequiredConstraintTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields\Values\Constraints;

use FF\Forms\Fields\Constraints\RequiredConstraint;
use FF\Forms\Fields\Constraints\Violations\MissingValueViolation;
use FF\Forms\Fields\Values\ArrayValue;
use FF\Forms\Fields\Values\ScalarValue;
use FF\Forms\Fields\Values\UploadValue;
use FF\Forms\Fields\Values\UploadValueStructure;
use PHPUnit\Framework\TestCase;

/**
 * Test RequiredConstraintTest
 *
 * @package FF\Tests
 */
class RequiredConstraintTest extends TestCase
{
    /**
     * @var RequiredConstraint
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new RequiredConstraint();
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckScalarValue()
    {
        $this->assertNull($this->uut->check(new ScalarValue('foo')));
        $this->assertInstanceOf(MissingValueViolation::class, $this->uut->check(new ScalarValue()));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckMultiValue()
    {
        $this->assertNull($this->uut->check(new ArrayValue(['foo'])));
        $this->assertInstanceOf(MissingValueViolation::class, $this->uut->check(new ArrayValue()));
        $this->assertInstanceOf(MissingValueViolation::class, $this->uut->check(new ArrayValue(['', null])));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckUploadValue()
    {
        $uploadOk = new UploadValue(['error' => UPLOAD_ERR_OK]);
        $this->assertNull($this->uut->check($uploadOk));
        $uploadEmpty = new UploadValue(['error' => UPLOAD_ERR_NO_FILE]);
        $this->assertInstanceOf(MissingValueViolation::class, $this->uut->check($uploadEmpty));
    }
}
