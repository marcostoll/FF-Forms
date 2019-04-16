<?php
/**
 * Definition of ArrayValueTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields\Values;

use FF\Forms\Fields\Values\ArrayValue;
use PHPUnit\Framework\TestCase;

/**
 * Test ArrayValueTest
 *
 * @package FF\Tests
 */
class ArrayValueTest extends TestCase
{
    /**
     * @var ArrayValue
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new ArrayValue();
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetValue()
    {
        $value = ['foo', 'bar'];
        $same = $this->uut->setValue($value);
        $this->assertSame($this->uut, $same);
        $this->assertEquals($value, $this->uut->getValue());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetPlain()
    {
        $value = ['foo', 'bar'];
        $this->assertEquals($value, ($this->uut->setValue($value)->getPlain()));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testIsEmpty()
    {
        $this->assertTrue($this->uut->setValue([])->isEmpty());
        $this->assertTrue($this->uut->setValue(['', ''])->isEmpty());
        $this->assertFalse($this->uut->setValue(['foo'])->isEmpty());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testToArray()
    {
        $this->assertIsArray($this->uut->toArray());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testToString()
    {
        $this->assertIsString((string)$this->uut);
    }
}
