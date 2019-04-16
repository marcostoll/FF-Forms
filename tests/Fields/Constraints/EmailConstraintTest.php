<?php
/**
 * Definition of EmailConstraintTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields\Values\Constraints;

use FF\Forms\Fields\Constraints\EmailConstraint;
use FF\Forms\Fields\Constraints\Violations\InvalidValueViolation;
use FF\Forms\Fields\Values\ScalarValue;
use PHPUnit\Framework\TestCase;

/**
 * Test EmailConstraintTest
 *
 * @package FF\Tests
 */
class EmailConstraintTest extends TestCase
{
    /**
     * @var EmailConstraint
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new EmailConstraint();
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheck()
    {
        $this->assertNull($this->uut->check(new ScalarValue('marco@fast-forward-encoding.de')));
        $this->assertInstanceOf(InvalidValueViolation::class, $this->uut->check(new ScalarValue('foo')));
    }
}
