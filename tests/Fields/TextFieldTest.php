<?php
/**
 * Definition of TextFieldTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields;

use FF\Forms\Fields\TextField;
use FF\Forms\Fields\Values\ArrayValue;
use PHPUnit\Framework\TestCase;

/**
 * Test TextFieldTest
 *
 * @package FF\Tests
 */
class TextFieldTest extends TestCase
{
    /**
     * @var TextField
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new TextField('foo');
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
    public function testSetValueArrayConvert()
    {
        $value = 'foo';
        $this->assertEquals($value, $this->uut->setValue([$value])->getValue()->getValue());
    }
}