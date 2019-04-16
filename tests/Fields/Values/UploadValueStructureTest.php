<?php
/**
 * Definition of UploadValueStructureTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields\Values;

use FF\Forms\Fields\Values\UploadValueStructure;
use PHPUnit\Framework\TestCase;

/**
 * Test UploadValueStructureTest
 *
 * @package FF\Tests
 */
class UploadValueStructureTest extends TestCase
{
    const SOME_UPLOAD_INFO = [
        'name' => 'test.pdf',
        'type' => 'application/pdf',
        'size' => 654351,
        'tmp_name' => 'tmp/abcdefghi',
        'error' => UPLOAD_ERR_OK
    ];

    /**
     * @var UploadValueStructure
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new UploadValueStructure();
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetName()
    {
        $same = $this->uut->setName('foo');
        $this->assertSame($this->uut, $same);
        $this->assertEquals('foo', $this->uut->getName());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetType()
    {
        $same = $this->uut->setType('foo');
        $this->assertSame($this->uut, $same);
        $this->assertEquals('foo', $this->uut->getType());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetSize()
    {
        $same = $this->uut->setSize(42);
        $this->assertSame($this->uut, $same);
        $this->assertEquals(42, $this->uut->getSize());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetTmpName()
    {
        $same = $this->uut->setTmpName('foo');
        $this->assertSame($this->uut, $same);
        $this->assertEquals('foo', $this->uut->getTmpName());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetError()
    {
        $same = $this->uut->setError(UPLOAD_ERR_NO_FILE);
        $this->assertSame($this->uut, $same);
        $this->assertEquals(UPLOAD_ERR_NO_FILE, $this->uut->getError());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetFromArray()
    {
        $same = $this->uut->setFromArray(self::SOME_UPLOAD_INFO);
        $this->assertSame($this->uut, $same);
        $this->assertEquals(self::SOME_UPLOAD_INFO['name'], $this->uut->getName());
        $this->assertEquals(self::SOME_UPLOAD_INFO['type'], $this->uut->getType());
        $this->assertEquals(self::SOME_UPLOAD_INFO['size'], $this->uut->getSize());
        $this->assertEquals(self::SOME_UPLOAD_INFO['tmp_name'], $this->uut->getTmpName());
        $this->assertEquals(self::SOME_UPLOAD_INFO['error'], $this->uut->getError());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testToArray()
    {
        $array = $this->uut->toArray();
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('type', $array);
        $this->assertArrayHasKey('size', $array);
        $this->assertArrayHasKey('tmp_name', $array);
        $this->assertArrayHasKey('error', $array);

        $this->assertIsString($array['name']);
        $this->assertIsString($array['type']);
        $this->assertIsInt($array['size']);
        $this->assertIsString($array['tmp_name']);
        $this->assertIsInt($array['error']);
    }
}
