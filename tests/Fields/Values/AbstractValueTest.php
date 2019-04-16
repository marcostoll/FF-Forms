<?php
/**
 * Definition of AbstractValueTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields\Values;

use FF\Forms\Fields\Constraints\AbstractConstraint;
use FF\Forms\Fields\Constraints\Violations\AbstractViolation;
use FF\Forms\Fields\Values\AbstractValue;
use PHPUnit\Framework\TestCase;

/**
 * Test AbstractValueTest
 *
 * @package FF\Tests
 */
class AbstractValueTest extends TestCase
{
    /**
     * @var MyValue
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new MyValue('foo');
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetPlain()
    {
        $this->assertEquals('foo', ($this->uut->setValue('foo')->getPlain()));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testMeetsConstraint()
    {
        $constraint = new MyConstraint();

        $this->assertTrue($this->uut->setValue('foo')->meetsConstraint($constraint));
        $this->assertFalse($this->uut->setValue('bar')->meetsConstraint($constraint));
    }
}

class MyValue extends AbstractValue
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->setValue($value);
    }

    /**
     * Checks whether this instance is considered empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return strlen($this->value) > 0;
    }

    /**
     * Retrieves a string representation if this instance
     *
     * @return mixed
     */
    public function __toString(): string
    {
        return $this->value;
    }
}

class MyConstraint extends AbstractConstraint
{
    /**
     * Checks the given value violates the constraint's rules
     *
     * @param AbstractValue $value
     * @return AbstractViolation|null
     */
    public function check(AbstractValue $value): ?AbstractViolation
    {
        return ($value->getValue() != 'foo') ? new MyViolation($this, $value) : null;
    }
}

class MyViolation extends AbstractViolation
{

}