<?php
/**
 * Definition of MinLengthConstraintTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields\Values\Constraints;

use FF\Forms\Fields\Constraints\MinLengthConstraint;
use FF\Forms\Fields\Constraints\Violations\InvalidValueViolation;
use FF\Forms\Fields\Values\ArrayValue;
use FF\Forms\Fields\Values\ScalarValue;
use FF\Forms\Fields\Values\UploadValue;
use PHPUnit\Framework\TestCase;

/**
 * Test MinLengthConstraintTest
 *
 * @package FF\Tests
 */
class MinLengthConstraintTest extends TestCase
{
    /**
     * @var MinLengthConstraint
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new MinLengthConstraint(5);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetMinLength()
    {
        $value = 42;
        $same = $this->uut->setMinLength($value);
        $this->assertSame($this->uut, $same);
        $this->assertEquals($value, $this->uut->getMinLength());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheck()
    {
        $this->assertNull($this->uut->check(new ScalarValue('abcdef')));
        $this->assertInstanceOf(InvalidValueViolation::class, $this->uut->check(new ScalarValue('abc')));
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
