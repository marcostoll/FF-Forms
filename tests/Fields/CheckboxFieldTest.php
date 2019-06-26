<?php
/**
 * Definition of CheckboxFieldTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields;

use FF\Forms\Fields\CheckboxField;
use FF\Forms\Fields\Constraints\BoolConstraint;
use FF\Forms\Fields\Values\ArrayValue;
use FF\Forms\Fields\Values\ScalarValue;
use PHPUnit\Framework\TestCase;

/**
 * Test CheckboxFieldTest
 *
 * @package FF\Tests
 */
class CheckboxFieldTest extends TestCase
{
    /**
     * @var CheckboxField
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new CheckboxField('foo');
    }

    /**
     * Tests the namesake method/feature
     */
    public function testAutoConstraints()
    {
        foreach ($this->uut->getConstraints() as $constraint) {
            if ($constraint instanceof BoolConstraint) {
                $this->assertTrue(true);
                return;
            }
        }

        $this->fail('no BoolConstraint detected');
    }
    /**
     * Tests the namesake method/feature
     */
    public function testIsChecked()
    {
        $this->assertTrue($this->uut->setValue(new ScalarValue('on'))->isChecked());
        $this->assertFalse($this->uut->setValue(new ScalarValue())->isChecked());
    }


    /**
     * Tests the namesake method/feature
     */
    public function testIsValid()
    {
        $this->assertTrue($this->uut->setValue(new ScalarValue('on'))->isValid());
        $this->assertTrue($this->uut->setValue(new ScalarValue())->isValid());
        $this->assertFalse($this->uut->setValue(new ScalarValue('foo'))->isValid());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetValueInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->uut->setValue(new ArrayValue());
    }
}
