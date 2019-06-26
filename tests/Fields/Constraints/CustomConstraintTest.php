<?php
/**
 * Definition of CustomConstraintTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields\Values\Constraints;

use FF\Forms\Fields\Constraints\CustomConstraint;
use FF\Forms\Fields\Constraints\Violations\InvalidValueViolation;
use FF\Forms\Fields\Constraints\Violations\MissingValueViolation;
use FF\Forms\Fields\Values\AbstractValue;
use FF\Forms\Fields\Values\ArrayValue;
use FF\Forms\Fields\Values\ScalarValue;
use PHPUnit\Framework\TestCase;

/**
 * Test CustomConstraintTest
 *
 * @package FF\Tests
 */
class CustomConstraintTest extends TestCase
{
    /**
     * @var CustomConstraint
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $validator = function (AbstractValue $value) {
            if (!($value instanceof ScalarValue)) {
                return true;
            }
            return $value->getValue() == 'foo';
        };
        $this->uut = new CustomConstraint($validator);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetValidator()
    {
        $value = function (AbstractValue $value) {
            if (!($value instanceof ScalarValue)) {
                return true;
            }
            return $value->getValue() == 'foo';
        };
        $same = $this->uut->setValidator($value);
        $this->assertSame($this->uut, $same);
        $this->assertEquals($value, $this->uut->getValidator());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetViolationClass()
    {
        $value = MissingValueViolation::class;
        $same = $this->uut->setViolationClass($value);
        $this->assertSame($this->uut, $same);
        $this->assertEquals($value, $this->uut->getViolationClass());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetViolationClassInvalidArgument()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->uut->setViolationClass(\Exception::class);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheck()
    {
        $this->assertNull($this->uut->check(new ScalarValue('foo')));
        $this->assertNull($this->uut->check(new ArrayValue()));
        $this->assertInstanceOf(InvalidValueViolation::class, $this->uut->check(new ScalarValue('bar')));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckEmpty()
    {
        $this->assertNull($this->uut->check(new ScalarValue()));
    }
}
