<?php
/**
 * Definition of OptionsConstraintTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields\Values\Constraints;

use FF\Forms\Fields\Constraints\OptionsConstraint;
use FF\Forms\Fields\Constraints\Violations\InvalidValueViolation;
use FF\Forms\Fields\Values\ArrayValue;
use FF\Forms\Fields\Values\ScalarValue;
use FF\Forms\Fields\Values\UploadValue;
use PHPUnit\Framework\TestCase;

/**
 * Test OptionsConstraintTest
 *
 * @package FF\Tests
 */
class OptionsConstraintTest extends TestCase
{
    /**
     * @var OptionsConstraint
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new OptionsConstraint([]);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetOptions()
    {
        $value = ['foo', 'bar'];
        $same = $this->uut->setOptions($value);
        $this->assertSame($this->uut, $same);
        $this->assertEquals($value, $this->uut->getOptions());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckNoOptions()
    {
        $this->assertNull($this->uut->check(new ScalarValue('baz')));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckEmpty()
    {
        $this->uut->setOptions(['foo', 'bar']);
        $this->assertNull($this->uut->check(new ArrayValue([''])));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckSimple()
    {
        $this->uut->setOptions(['foo', 'bar']);

        $this->assertNull($this->uut->check(new ScalarValue('bar')));
        $this->assertInstanceOf(InvalidValueViolation::class, $this->uut->check(new ScalarValue('baz')));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckMulti()
    {
        $this->uut->setOptions(['foo', 'bar']);

        $this->assertNull($this->uut->check(new ArrayValue(['bar'])));
        $this->assertInstanceOf(InvalidValueViolation::class, $this->uut->check(new ArrayValue(['foo', 'baz'])));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckUnsuited()
    {
        $this->assertNull($this->uut->check(new UploadValue()));
    }
}
