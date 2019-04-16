<?php
/**
 * Definition of ScalarValueTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields\Values;

use FF\Forms\Fields\Values\ScalarValue;
use PHPUnit\Framework\TestCase;

/**
 * Test ScalarValueTest
 *
 * @package FF\Tests
 */
class ScalarValueTest extends TestCase
{
    /**
     * @var ScalarValue
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new ScalarValue();
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetValue()
    {
        $value = 'foo';
        $same = $this->uut->setValue($value);
        $this->assertSame($this->uut, $same);
        $this->assertEquals($value, $this->uut->getValue());
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
    public function testIsEmpty()
    {
        $this->assertTrue($this->uut->setValue('')->isEmpty());
        $this->assertFalse($this->uut->setValue('foo')->isEmpty());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testToString()
    {
        $this->assertIsString((string)$this->uut);
    }
}
