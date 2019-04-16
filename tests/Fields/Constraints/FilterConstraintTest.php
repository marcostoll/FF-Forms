<?php
/**
 * Definition of FilterConstraintTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields\Values\Constraints;

use FF\Forms\Fields\Constraints\FilterConstraint;
use FF\Forms\Fields\Constraints\Violations\InvalidValueViolation;
use FF\Forms\Fields\Values\ArrayValue;
use FF\Forms\Fields\Values\ScalarValue;
use FF\Forms\Fields\Values\UploadValue;
use PHPUnit\Framework\TestCase;

/**
 * Test FilterConstraintTest
 *
 * @package FF\Tests
 */
class FilterConstraintTest extends TestCase
{
    /**
     * @var FilterConstraint
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new FilterConstraint(FILTER_VALIDATE_EMAIL);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetFilter()
    {
        $value = FILTER_VALIDATE_INT;
        $same = $this->uut->setFilter($value);
        $this->assertSame($this->uut, $same);
        $this->assertEquals($value, $this->uut->getFilter());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetOptions()
    {
        $value = [
            'options' => [
                'default' => 7,
                'min_range' => 1,
                'max_range' => 10
            ],
            'flags' => FILTER_FLAG_ALLOW_HEX
        ];
        $same = $this->uut->setOptions($value);
        $this->assertSame($this->uut, $same);
        $this->assertEquals($value, $this->uut->getOptions());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheck()
    {
        $this->assertNull($this->uut->check(new ScalarValue('marco@fast-forward-encoding.de')));
        $this->assertInstanceOf(InvalidValueViolation::class, $this->uut->check(new ScalarValue('foo')));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckEmpty()
    {
        $this->assertNull($this->uut->check(new ScalarValue()));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckUnsuited()
    {
        $this->assertNull($this->uut->check(new ArrayValue(['foo'])));
        $this->assertNull($this->uut->check(new UploadValue()));
    }
}
