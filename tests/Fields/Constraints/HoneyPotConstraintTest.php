<?php
/**
 * Definition of HoneyPotConstraintTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields\Values\Constraints;

use FF\Forms\Fields\Constraints\HoneyPotConstraint;
use FF\Forms\Fields\Constraints\Violations\SecurityViolation;
use FF\Forms\Fields\Values\ArrayValue;
use FF\Forms\Fields\Values\ScalarValue;
use FF\Forms\Fields\Values\UploadValue;
use PHPUnit\Framework\TestCase;

/**
 * Test HoneyPotConstraintTest
 *
 * @package FF\Tests
 */
class HoneyPotConstraintTest extends TestCase
{
    /**
     * @var HoneyPotConstraint
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new HoneyPotConstraint();
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckScalarValue()
    {
        $this->assertNull($this->uut->check(new ScalarValue()));
        $this->assertInstanceOf(SecurityViolation::class, $this->uut->check(new ScalarValue('foo')));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckMultiValue()
    {
        $this->assertNull($this->uut->check(new ArrayValue()));
        $this->assertInstanceOf(SecurityViolation::class, $this->uut->check(new ArrayValue(['foo'])));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckUploadValue()
    {
        $this->assertNull($this->uut->check(new UploadValue(['error' => UPLOAD_ERR_NO_FILE])));
        $this->assertInstanceOf(SecurityViolation::class, $this->uut->check(new UploadValue([
            'error' => UPLOAD_ERR_OK
        ])));
    }
}
