<?php
/**
 * Definition of MatchesConstraintTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields\Values\Constraints;

use FF\Forms\Fields\Constraints\MatchesConstraint;
use FF\Forms\Fields\Constraints\Violations\InvalidValueViolation;
use FF\Forms\Fields\MultiSelectField;
use FF\Forms\Fields\TextField;
use FF\Forms\Fields\Values\ArrayValue;
use FF\Forms\Fields\Values\ScalarValue;
use PHPUnit\Framework\TestCase;

/**
 * Test MinLengthConstraintTest
 *
 * @package FF\Tests
 */
class MatchesConstraintTest extends TestCase
{
    /**
     * @var MatchesConstraint
     */
    protected $uut;

    /**
     * @var TextField
     */
    protected $matchingField;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->matchingField = new TextField('foo');
        $this->uut = new MatchesConstraint($this->matchingField);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetMatchingField()
    {
        $value = new TextField('bar');
        $same = $this->uut->setMatchingField($value);
        $this->assertSame($this->uut, $same);
        $this->assertSame($value, $this->uut->getMatchingField());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheck()
    {
        $value = 'abcdef';
        $this->matchingField->setValue(new ScalarValue($value));
        $this->assertNull($this->uut->check(new ScalarValue($value)));
        $this->assertInstanceOf(InvalidValueViolation::class, $this->uut->check(new ScalarValue('abc')));
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
    public function testCheckMatchingFieldHasNoValue()
    {
        $this->matchingField->reset();
        $this->assertInstanceOf(InvalidValueViolation::class, $this->uut->check(new ScalarValue('abc')));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCheckMatchingFieldDifferentValueType()
    {
        $multiSelect = (new MultiSelectField('baz', ['abc', 'def']))->setValue(new ArrayValue(['abc']));
        $this->uut->setMatchingField($multiSelect);
        $this->assertInstanceOf(InvalidValueViolation::class, $this->uut->check(new ScalarValue('abc')));
    }
}
