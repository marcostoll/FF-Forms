<?php
/**
 * Definition of PasswordFieldTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields;

use FF\Forms\Fields\PasswordField;
use FF\Forms\Fields\Values\ArrayValue;
use PHPUnit\Framework\TestCase;

/**
 * Test PasswordFieldTest
 *
 * @package FF\Tests
 */
class PasswordFieldTest extends TestCase
{
    /**
     * @var PasswordField
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new PasswordField('foo');
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetValueInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->uut->setValue(new ArrayValue());;
    }
}