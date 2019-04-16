<?php
/**
 * Definition of UploadValueTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields\Values;

use FF\Forms\Fields\Values\UploadValue;
use FF\Forms\Fields\Values\UploadValueStructure;
use PHPUnit\Framework\TestCase;

/**
 * Test UploadValueTest
 *
 * @package FF\Tests
 */
class UploadValueTest extends TestCase
{
    /**
     * @var UploadValue
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new UploadValue();
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetValue()
    {
        $value = new UploadValueStructure();
        $same = $this->uut->setValue($value);
        $this->assertSame($this->uut, $same);
        $this->assertSame($value, $this->uut->getValue());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetPlain()
    {
        $value = [
            'name' => 'foo.pdf',
            'size' => 1024,
            'type' => 'application/pdf',
            'tmp_name' => 'bar',
            'error' => UPLOAD_ERR_OK
        ];
        $this->assertEquals($value, ($this->uut->setValue(new UploadValueStructure($value))->getPlain()));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testIsEmpty()
    {
        $value = new UploadValueStructure();
        $this->assertTrue($this->uut->setValue($value)->isEmpty());
        $this->assertFalse($this->uut->setValue($value->setError(UPLOAD_ERR_OK))->isEmpty());
        $this->assertFalse($this->uut->setValue($value->setError(UPLOAD_ERR_EXTENSION))->isEmpty());
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
