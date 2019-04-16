<?php
/**
 * Definition of SelectFieldTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields;

use FF\Forms\Fields\Constraints\OptionsConstraint;
use FF\Forms\Fields\SelectField;
use FF\Forms\Fields\Values\ArrayValue;
use FF\Forms\Fields\Values\ScalarValue;
use PHPUnit\Framework\TestCase;

/**
 * Test SelectFieldTest
 *
 * @package FF\Tests
 */
class SelectFieldTest extends TestCase
{
    /**
     * @var SelectField
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new SelectField('foo', ['bar' => 'Label bar', 'baz' => 'Label baz']);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testIsValid()
    {
        $this->assertTrue($this->uut->setValue(new ScalarValue('bar'))->isValid());
        $this->assertTrue($this->uut->setValue(new ScalarValue())->isValid());
        $this->assertFalse($this->uut->setValue(new ScalarValue('Label Baz'))->isValid());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testAutoAssocOptions()
    {
        $uut = new SelectField('foo', ['bar', 'baz']);

        $this->assertEquals(['bar' => 'bar', 'baz' => 'baz'], $uut->getOptions());
    }
}