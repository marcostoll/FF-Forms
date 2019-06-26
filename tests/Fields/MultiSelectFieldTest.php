<?php
/**
 * Definition of MultiSelectFieldTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields;

use FF\Forms\Fields\MultiSelectField;
use FF\Forms\Fields\Values\ArrayValue;
use FF\Forms\Fields\Values\ScalarValue;
use PHPUnit\Framework\TestCase;

/**
 * Test MultiSelectFieldTest
 *
 * @package FF\Tests
 */
class MultiSelectFieldTest extends TestCase
{
    /**
     * @var MultiSelectField
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new MultiSelectField('foo', ['bar' => 'Label bar', 'baz' => 'Label baz']);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testPlain()
    {
        $this->assertEquals([], $this->uut->reset()->getPlain());
        $this->assertEquals(['foo'], $this->uut->setValue(new ArrayValue(['foo']))->getPlain());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetDefaultValue()
    {
        $this->assertInstanceOf(ArrayValue::class, $this->uut->getDefaultValue());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testIsValid()
    {
        $this->assertTrue($this->uut->setValue(new ArrayValue(['bar', 'baz']))->isValid());
        $this->assertTrue($this->uut->setValue(new ArrayValue())->isValid());
        $this->assertFalse($this->uut->setValue(new ArrayValue(['Label Baz']))->isValid());
        $this->assertFalse($this->uut->setValue(new ArrayValue(['bar', 'foo']))->isValid());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetValueInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->uut->setValue(new ScalarValue());
    }
}
