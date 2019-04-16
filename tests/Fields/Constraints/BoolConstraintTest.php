<?php
/**
 * Definition of BoolConstraintTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields\Values\Constraints;

use FF\Forms\Fields\Constraints\BoolConstraint;
use FF\Forms\Fields\Constraints\Violations\InvalidValueViolation;
use FF\Forms\Fields\Values\ScalarValue;
use PHPUnit\Framework\TestCase;

/**
 * Test BoolConstraintTest
 *
 * @package FF\Tests
 */
class BoolConstraintTest extends TestCase
{
    /**
     * @var BoolConstraint
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new BoolConstraint();
    }

    /**
     * Tests the namesake method/feature
     *
     * @see https://www.php.net/manual/en/filter.filters.validate.php
     */
    public function testCheck()
    {
        // true values
        $this->assertNull($this->uut->check(new ScalarValue('1')));
        $this->assertNull($this->uut->check(new ScalarValue('true')));
        $this->assertNull($this->uut->check(new ScalarValue('on')));
        $this->assertNull($this->uut->check(new ScalarValue('yes')));

        // false values
        $this->assertNull($this->uut->check(new ScalarValue('0')));
        $this->assertNull($this->uut->check(new ScalarValue('false')));
        $this->assertNull($this->uut->check(new ScalarValue('off')));
        $this->assertNull($this->uut->check(new ScalarValue('no')));
        $this->assertNull($this->uut->check(new ScalarValue()));

        $this->assertInstanceOf(InvalidValueViolation::class, $this->uut->check(new ScalarValue('foo')));
        $this->assertInstanceOf(InvalidValueViolation::class, $this->uut->check(new ScalarValue('none')));
        $this->assertInstanceOf(InvalidValueViolation::class, $this->uut->check(new ScalarValue('si')));
    }
}
