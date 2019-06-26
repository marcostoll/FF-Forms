<?php
/**
 * Definition of RadioFieldTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields;

use FF\Forms\Fields\Constraints\OptionsConstraint;
use FF\Forms\Fields\RadioField;
use FF\Forms\Fields\Values\ArrayValue;
use FF\Forms\Fields\Values\ScalarValue;
use PHPUnit\Framework\TestCase;

/**
 * Test RadioFieldTest
 *
 * @package FF\Tests
 */
class RadioFieldTest extends TestCase
{
    /**
     * @var RadioField
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new RadioField('foo', ['bar', 'baz']);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testAutoConstraints()
    {
        foreach ($this->uut->getConstraints() as $constraint) {
            if ($constraint instanceof OptionsConstraint) {
                $this->assertTrue(true);
                return;
            }
        }

        $this->fail('no OptionsConstraint detected');
    }

    /**
     * Tests the namesake method/feature
     */
    public function testIsValid()
    {
        $this->assertTrue($this->uut->setValue(new ScalarValue('bar'))->isValid());
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
