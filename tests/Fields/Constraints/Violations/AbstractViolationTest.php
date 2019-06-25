<?php
/**
 * Definition of AbstractViolationTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields\Values\Constraints\Violations;

use FF\Forms\Fields\Constraints\RequiredConstraint;
use FF\Forms\Fields\Constraints\Violations\AbstractViolation;
use FF\Forms\Fields\Values\ScalarValue;
use PHPUnit\Framework\TestCase;

/**
 * Test AbstractViolationTest
 *
 * @package FF\Tests
 */
class AbstractViolationTest extends TestCase
{
    /**
     * @var MyViolation
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new MyViolation(new RequiredConstraint(), new ScalarValue());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetConstraint()
    {
        $value = new RequiredConstraint();
        $same = $this->uut->setConstraint($value);
        $this->assertSame($this->uut, $same);
        $this->assertSame($value, $this->uut->getConstraint());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetValue()
    {
        $value = new ScalarValue();
        $same = $this->uut->setValue($value);
        $this->assertSame($this->uut, $same);
        $this->assertSame($value, $this->uut->getValue());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testToString()
    {
        $this->assertIsString((string)$this->uut);
    }
}

class MyViolation extends AbstractViolation
{

}
