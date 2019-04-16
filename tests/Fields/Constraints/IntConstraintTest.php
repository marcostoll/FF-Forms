<?php
/**
 * Definition of IntConstraintTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields\Values\Constraints;

use FF\Forms\Fields\Constraints\IntConstraint;
use FF\Forms\Fields\Constraints\Violations\InvalidValueViolation;
use FF\Forms\Fields\Values\ScalarValue;
use PHPUnit\Framework\TestCase;

/**
 * Test IntConstraintTest
 *
 * @package FF\Tests
 */
class IntConstraintTest extends TestCase
{
    /**
     * @var IntConstraint
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new IntConstraint(1, 10, FILTER_FLAG_ALLOW_HEX);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheck()
    {
        $this->assertNull($this->uut->check(new ScalarValue('7')));
        $this->assertNull($this->uut->check(new ScalarValue('0xA')));
        $this->assertInstanceOf(InvalidValueViolation::class, $this->uut->check(new ScalarValue('0')));
        $this->assertInstanceOf(InvalidValueViolation::class, $this->uut->check(new ScalarValue('0xf')));
        $this->assertInstanceOf(InvalidValueViolation::class, $this->uut->check(new ScalarValue('foo')));
    }
}
