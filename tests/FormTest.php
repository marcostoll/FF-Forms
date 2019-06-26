<?php
/**
 * Definition of FormTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms;

use FF\Forms\Fields\Constraints\MinLengthConstraint;
use FF\Forms\Fields\Constraints\RequiredConstraint;
use FF\Forms\Fields\PasswordField;
use FF\Forms\Fields\RadioField;
use FF\Forms\Fields\TextField;
use FF\Forms\Form;
use PHPUnit\Framework\Error\Error;
use PHPUnit\Framework\TestCase;

/**
 * Test FormTest
 *
 * @package FF\Tests
 */
class FormTest extends TestCase
{
    /**
     * @var Form
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new Form();
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetFields()
    {
        $value = [
            new TextField('username'),
            new PasswordField('password')
        ];
        $same = $this->uut->setFields($value);
        $this->assertSame($this->uut, $same);

        $fields = $this->uut->getFields();
        foreach ($value as $field) {
            if (array_search($field, $fields) === false) {
                $this->fail('field [' . $field->getName() . '] missing');
                return;
            }
        }
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetField()
    {
        $value = new TextField('username');
        $same = $this->uut->setField($value);
        $this->assertSame($this->uut, $same);
        $this->assertSame($value, $this->uut->getField($value->getName()));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testAddField()
    {
        $value = new TextField('username');
        $same = $this->uut->addField($value);
        $this->assertSame($this->uut, $same);
        $this->assertSame($value, $this->uut->getField($value->getName()));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testRemoveField()
    {
        $value = new TextField('username');
        $same = $this->uut->addField($value)->removeField($value->getName());
        $this->assertSame($this->uut, $same);
        $this->assertNull($this->uut->getField($value->getName()));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testHasField()
    {
        $value = [
            new TextField('username'),
            new PasswordField('password')
        ];
        $this->uut->setFields($value);

        $this->assertTrue($this->uut->hasField('username'));
        $this->assertTrue($this->uut->hasField('password'));
        $this->assertFalse($this->uut->hasField('foo'));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testAssign()
    {
        $this->uut->setFields([
            new TextField('username'),
            new PasswordField('password')
        ]);

        $values = ['username' => 'Wumbo', 'password' => '123456'];
        $same = $this->uut->assign($values);
        $this->assertSame($this->uut, $same);
        $this->assertEquals($values['username'], $this->uut->getField('username')->getPlain());
        $this->assertEquals($values['password'], $this->uut->getField('password')->getPlain());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testAssignIncomplete()
    {
        $this->uut->setFields([
            new TextField('username'),
            new PasswordField('password')
        ]);

        $values = ['username' => 'Wumbo'];
        $this->uut->assign($values);
        $this->assertEquals($values['username'], $this->uut->getField('username')->getPlain());
        $this->assertTrue($this->uut->getField('password')->hasValue());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetValuesEmpty()
    {
        $values = $this->uut->setFields([
            new TextField('username'),
            new PasswordField('password')
        ])->getValues();

        $this->assertArrayHasKey('username', $values);
        $this->assertArrayHasKey('password', $values);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetValues()
    {
        $values = $this->uut->setFields([
            new TextField('username'),
            new PasswordField('password')
        ])->assign(['username' => 'Wumbo'])->getValues();

        $this->assertEquals('Wumbo', $values['username']);
        $this->assertEquals('', $values['password']);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testReset()
    {
        $this->uut->setFields([
            new TextField('username'),
            new PasswordField('password')
        ]);

        $values = ['username' => 'Wumbo', 'password' => '123456'];
        $same = $this->uut->assign($values)->reset();
        $this->assertSame($this->uut, $same);
        $this->assertEquals('', $this->uut->getField('username')->getPlain());
        $this->assertEquals('', $this->uut->getField('password')->getPlain());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testIsValidNoFields()
    {
        $this->assertTrue($this->uut->isValid());
        $this->assertTrue($this->uut->assign(['foo' => 'bar'])->isValid());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testIsValidNoValues()
    {
        $this->uut->setFields([
            new TextField('username'),
            new PasswordField('password')
        ]);

        $this->assertTrue($this->uut->isValid());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testIsValidNoConstraints()
    {
        $this->uut->setFields([
            new TextField('username'),
            new PasswordField('password')
        ]);

        $this->assertTrue($this->uut->assign(['username' => 'Wumbo', 'password' => ''])->isValid());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testIsValidConstraints()
    {
        $this->uut->setFields([
            (new TextField('username'))->addConstraint(new RequiredConstraint()),
            (new PasswordField('password'))->addConstraint((new MinLengthConstraint(6)))
        ]);

        $this->assertTrue($this->uut->assign(['username' => 'Wumbo', 'password' => '123456'])->isValid());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testIsValidConstraintsNoValues()
    {
        $this->uut->setFields([
            (new TextField('username'))->addConstraint(new RequiredConstraint()),
            (new PasswordField('password'))->addConstraint((new MinLengthConstraint(6)))
        ]);

        $this->assertTrue($this->uut->isValid());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testIsValidConstraintsInvalid()
    {
        $this->uut->setFields([
            (new TextField('username'))->addConstraint(new RequiredConstraint()),
            (new PasswordField('password'))->addConstraint((new MinLengthConstraint(6)))
        ]);

        $this->assertFalse($this->uut->assign(['username' => '', 'password' => '1234'])->isValid());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetViolationsNoAssign()
    {
        $this->assertEmpty($this->uut->getViolations());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetViolationNoAssign()
    {
        $this->uut->setFields([
            (new TextField('username'))->addConstraint(new RequiredConstraint()),
            (new PasswordField('password'))->addConstraint((new MinLengthConstraint(6)))
        ]);

        $this->assertNull($this->uut->getViolation('username'));
        $this->assertNull($this->uut->getViolation('password'));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetViolationsValid()
    {
        $this->uut->setFields([
                (new TextField('username'))->addConstraint(new RequiredConstraint()),
                (new PasswordField('password'))->addConstraint((new MinLengthConstraint(6)))
            ])
            ->assign(['username' => 'Wumbo', 'password' => '123456'])
            ->isValid();

        $this->assertEmpty($this->uut->getViolations());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetViolationValid()
    {
        $this->uut->setFields([
            (new TextField('username'))->addConstraint(new RequiredConstraint()),
            (new PasswordField('password'))->addConstraint((new MinLengthConstraint(6)))
        ])
            ->assign(['username' => 'Wumbo', 'password' => '123456'])
            ->isValid();

        $this->assertNull($this->uut->getViolation('username'));
        $this->assertNull($this->uut->getViolation('password'));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetViolationsInvalid()
    {
        $this->uut->setFields([
            (new TextField('username'))->addConstraint(new RequiredConstraint()),
            (new PasswordField('password'))->addConstraint((new MinLengthConstraint(6)))
        ])
            ->assign(['username' => '', 'password' => '1234'])
            ->isValid();

        $violations = $this->uut->getViolations();
        $this->assertEquals(2, count($violations));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetViolationInvalid()
    {
        $this->uut->setFields([
            (new TextField('username'))->addConstraint(new RequiredConstraint()),
            (new PasswordField('password'))->addConstraint((new MinLengthConstraint(6)))
        ])
            ->assign(['username' => '', 'password' => '1234'])
            ->isValid();

        $this->assertNotNull($this->uut->getViolation('username'));
        $this->assertNotNull($this->uut->getViolation('password'));
        $this->assertInstanceOf(RequiredConstraint::class, $this->uut->getViolation('username')->getConstraint());
        $this->assertEquals('1234', $this->uut->getViolation('password')->getValue()->getPlain());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testhasViolation()
    {
        $this->uut->setFields([
            (new TextField('username'))->addConstraint(new RequiredConstraint()),
            (new PasswordField('password'))->addConstraint((new MinLengthConstraint(6)))
        ])
            ->assign(['username' => '', 'password' => '123456'])
            ->isValid();

        $this->assertTrue($this->uut->hasViolation('username'));
        $this->assertFalse($this->uut->hasViolation('password'));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testMagicGet()
    {
        $value = new TextField('username');
        $this->uut->setFields([$value]);

        $this->assertSame($value, $this->uut->username);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testMagicSGet()
    {
        $value = new TextField('username');
        $this->uut->username = $value;

        $this->assertSame($value, $this->uut->getField('username'));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testMagicIsset()
    {
        $value = new TextField('username');
        $this->uut->setFields([$value]);

        $this->assertTrue(isset($this->uut->username));
        $this->assertFalse(isset($this->uut->foo));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testMagicUnset()
    {
        $value = new TextField('username');
        $this->uut->setFields([$value]);
        unset($this->uut->username);

        $this->assertFalse($this->uut->hasField('username'));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testMagicCall()
    {
        $field = Form::text('foo');
        $this->assertInstanceOf(TextField::class, $field);
        $this->assertEquals('foo', $field->getName());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testMagicCallWithArguments()
    {
        $field = Form::radio('foo', ['bar', 'baz']);
        $this->assertInstanceOf(RadioField::class, $field);
        $this->assertEquals(['bar', 'baz'], $field->getOptions());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testMagicCallUnknown()
    {
        $this->expectException(Error::class);

        Form::foo('bar');
    }

    /**
     * Tests the namesake method/feature
     */
    public function testMagic()
    {
        new Form(
            Form::text('username')->required()->minLength(6),
            Form::password('password')->required(),
            Form::radio('gender', ['male', 'female', 'other']),
            Form::text('email')->required()->email()
        );

        $this->assertTrue(true);
    }
}
