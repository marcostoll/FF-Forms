<?php
/**
 * Definition of MaxLengthConstraintTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields\Values\Constraints;

use FF\Forms\Fields\Constraints\MaxLengthConstraint;
use FF\Forms\Fields\Constraints\Violations\InvalidValueViolation;
use FF\Forms\Fields\Values\ArrayValue;
use FF\Forms\Fields\Values\ScalarValue;
use FF\Forms\Fields\Values\UploadValue;
use PHPUnit\Framework\TestCase;

/**
 * Test MaxLengthConstraintTest
 *
 * @package FF\Tests
 */
class MaxLengthConstraintTest extends TestCase
{
    /**
     * @var MaxLengthConstraint
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new MaxLengthConstraint(5);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetMaxLength()
    {
        $value = 42;
        $same = $this->uut->setMaxLength($value);
        $this->assertSame($this->uut, $same);
        $this->assertEquals($value, $this->uut->getMaxLength());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheck()
    {
        $this->assertNull($this->uut->check(new ScalarValue('abcd')));
        $this->assertInstanceOf(InvalidValueViolation::class, $this->uut->check(new ScalarValue('abcdefg')));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckEmpty()
    {
        $this->assertNull($this->uut->check(new ScalarValue()));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckUnsuited()
    {
        $this->assertNull($this->uut->check(new ArrayValue(['foo'])));
        $this->assertNull($this->uut->check(new UploadValue()));
    }
}
