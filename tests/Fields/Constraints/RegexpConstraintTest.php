<?php
/**
 * Definition of RegexpConstraintTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields\Values\Constraints;

use FF\Forms\Fields\Constraints\RegexpConstraint;
use FF\Forms\Fields\Constraints\Violations\InvalidValueViolation;
use FF\Forms\Fields\Values\ScalarValue;
use PHPUnit\Framework\TestCase;

/**
 * Test RegexpConstraintTest
 *
 * @package FF\Tests
 */
class RegexpConstraintTest extends TestCase
{
    /**
     * @var RegexpConstraint
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new RegexpConstraint('~(foo|bar|baz)~');
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheck()
    {
        $this->assertNull($this->uut->check(new ScalarValue('foo')));
        $this->assertNull($this->uut->check(new ScalarValue('  rabarbar  ')));
        $this->assertInstanceOf(InvalidValueViolation::class, $this->uut->check(new ScalarValue('some value')));
        $this->assertInstanceOf(InvalidValueViolation::class, $this->uut->check(new ScalarValue('FOO')));
    }
}
