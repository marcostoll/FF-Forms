<?php
/**
 * Definition of AbstractFieldTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields;

use FF\Forms\Fields\AbstractField;
use FF\Forms\Fields\Constraints\AbstractConstraint;
use FF\Forms\Fields\Constraints\MinLengthConstraint;
use FF\Forms\Fields\Constraints\RequiredConstraint;
use FF\Forms\Fields\Constraints\Violations\AbstractViolation;
use FF\Forms\Fields\Values\AbstractValue;
use FF\Forms\Fields\Values\ArrayValue;
use FF\Forms\Fields\Values\ScalarValue;
use PHPUnit\Framework\Error\Error;
use PHPUnit\Framework\TestCase;

/**
 * Test AbstractFieldTest
 *
 * @package FF\Tests
 */
class AbstractFieldTest extends TestCase
{
    /**
     * @var MyField
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new MyField();
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetName()
    {
        $name = 'foo';
        $same = $this->uut->setName($name);
        $this->assertSame($this->uut, $same);
        $this->assertEquals($name, $this->uut->getName());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetValue()
    {
        $value = new ScalarValue('foo');
        $same = $this->uut->setValue($value);
        $this->assertSame($this->uut, $same);
        $this->assertEquals($value, $this->uut->getValue());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetValueFromPlain()
    {
        $value = '';
        $this->uut->setValue($value);
        $this->assertEquals($value, $this->uut->setValue($value)->getPlain());

        $value = ['foo'];
        $this->uut->setValue($value);
        $this->assertEquals('foo', $this->uut->setValue($value)->getPlain());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testPlain()
    {
        $this->assertEquals('', $this->uut->reset()->getPlain());
        $this->assertEquals('foo', $this->uut->setValue(new ScalarValue('foo'))->getPlain());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetValueInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->uut->setValue(new ArrayValue());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testHasValue()
    {
        $this->assertFalse($this->uut->hasValue());
        $this->assertTrue($this->uut->setValue(new ScalarValue('foo'))->hasValue());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetConstraints()
    {
        $constraints = [new MyConstraint()];
        $same = $this->uut->setConstraints($constraints);
        $this->assertSame($this->uut, $same);
        $this->assertEquals($constraints, $this->uut->getConstraints());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testAddConstraint()
    {
        $constraint = new MyConstraint();
        $same = $this->uut->addConstraint($constraint, $constraint);
        $this->assertSame($this->uut, $same);
        $this->assertEquals([$constraint, $constraint], $this->uut->getConstraints());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testRemoveConstraint()
    {
        $constraint = new MyConstraint();
        $same = $this->uut->addConstraint($constraint, $constraint)->removeConstraint(MyConstraint::class);
        $this->assertSame($this->uut, $same);
        $this->assertEquals([], $this->uut->getConstraints());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testIsRequired()
    {
        $this->assertTrue($this->uut->addConstraint(new RequiredConstraint())->isRequired());
        $this->assertFalse($this->uut->removeConstraint(RequiredConstraint::class)->isRequired());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetViolation()
    {
        $this->assertNull($this->uut->getViolation());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testHasViolation()
    {
        $this->assertFalse($this->uut->hasViolation());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testIsValidNoValue()
    {
        $this->assertTrue($this->uut->isValid());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testIsValidNoConstraints()
    {
        $this->assertTrue($this->uut->setValue(new ScalarValue('foo'))->isValid());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testIsValidValid()
    {
        $this->uut->setConstraints([new MyConstraint()])->setValue(new ScalarValue('foo'));
        $this->assertTrue($this->uut->isValid());
        $this->assertFalse($this->uut->hasViolation());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testIsValidInvalid()
    {
        $this->uut->setConstraints([new MyConstraint()])->setValue(new ScalarValue('bar'));
        $this->assertFalse($this->uut->isValid());
        $this->assertTrue($this->uut->hasViolation());
        $this->assertInstanceOf(MyViolation::class, $this->uut->getViolation());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testReset()
    {
        $same = $this->uut->setConstraints([new MyConstraint()])->setValue(new ScalarValue('bar'))->reset();
        $this->assertSame($this->uut, $same);
        $this->assertFalse($this->uut->hasValue());
        $this->assertFalse($this->uut->hasViolation());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testMagicCall()
    {
        $same = $this->uut->required();
        $this->assertSame($this->uut, $same);
        $this->assertTrue($this->uut->isRequired());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testMagicCallWithArguments()
    {
        $constraints = $this->uut->minLength(6)->getConstraints();
        foreach ($constraints as $constraint) {
            if (!($constraint instanceof MinLengthConstraint)) continue;

            $this->assertEquals(6, $constraint->getMinLength());
            return;
        }

        $this->fail('no MinLengthConstraint found');
    }

    /**
     * Tests the namesake method/feature
     */
    public function testMagicCallUnknown()
    {
        $this->expectException(Error::class);

        $this->uut->foo();
    }
}

class MyField extends AbstractField
{
    /**
     * Retrieves a default value suitable for tis instance
     *
     * @return ScalarValue
     */
    public function getDefaultValue(): AbstractValue
    {
        return new ScalarValue();
    }

    /**
     * Retrieves a suitable value object representing the given plain value
     *
     * @param string|array
     * @return AbstractValue
     */
    protected function makeValue($plainValue): AbstractValue
    {
        if (is_array($plainValue)) {
            // use first array element
            $plainValue = (string)array_shift($plainValue);
        }

        return new ScalarValue($plainValue);
    }

    /**
     * Retrieves the class name of the value class to use for this type of field
     *
     * @return string
     */
    protected function getExpectedValueClass(): string
    {
        return ScalarValue::class;
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