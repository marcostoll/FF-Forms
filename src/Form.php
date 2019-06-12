<?php
/**
 * Definition of Form
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms;

use FF\DataStructures\Record;
use FF\Factories\Exceptions\ClassNotFoundException;
use FF\Forms\Fields\AbstractField;
use FF\Forms\Fields\CheckboxField;
use FF\Forms\Fields\Constraints\Violations\AbstractViolation;
use FF\Forms\Fields\FieldsFactory;
use FF\Forms\Fields\FileField;
use FF\Forms\Fields\MultiSelectField;
use FF\Forms\Fields\PasswordField;
use FF\Forms\Fields\RadioField;
use FF\Forms\Fields\SelectField;
use FF\Forms\Fields\TextField;

/**
 * Class Form
 *
 * @method static CheckboxField checkbox(string $fieldName)
 * @method static FileField file(string $fieldName, callable $uploadedFileValidator = null)
 * @method static MultiSelectField multiSelect(string $fieldName, array $options)
 * @method static PasswordField password(string $fieldName)
 * @method static RadioField radio(string $fieldName, array $options)
 * @method static SelectField select(string $fieldName, array $options)
 * @method static TextField text(string $fieldName)
 *
 * If your project defines additional AbstractField classes, you might consider subclassing Form and add
 * the suitable {@}method tags to register the new magic methods to your IDS.
 *
 * Creating a form using the magic api:
 *
 * <code>
 * $myForm = new Form(
 *      Form::text('username')->required()->minLength(6),
 *      Form::password('password')->required(),
 *      Form::radio('gender', ['male', 'female', 'other']), // no need to manually add an OptionsConstraint
 *      Form::text('email')->required()->email()
 * );
 * </code>
 *
 * Assigning and validating post data:
 *
 * <code>
 * $myForm = new Form( ... add some fields ... );
 * do {
 *      // check for POST requests
 *      if ($_SERVER['REQUEST_METHOD'] != 'POST') {
 *          break;
 *      }
 *
 *      // assign values and validate form
 *      if (!$myForm->assign($_POST)->isValid()) {
 *          var_dump($myForm->getViolations());
 *          break;
 *      }
 *
 *      // after here form is valid
 *      var_dump($myForm->getValues());
 *      var_dump($myForm->{'my_field'}->getPlain();
 * } while (false);
 * </code>
 *
 * Handling uploads
 *
 * <code>
 * $myForm = new Form(
 *      Form::file('file')->mimeType('image')->fileSize('2M'), // no need to manually add an UploadedFileConstraint
 *      Form::text('email')->required()->email()
 * );
 *
 * do {
 *      // check for POST requests
 *      if ($_SERVER['REQUEST_METHOD'] != 'POST') {
 *          break;
 *      }
 *
 *      // assign values and validate form
 *      if (!$myForm->assign($_POST + $_FILES)->isValid()) {
 *          var_dump($myForm->getViolations());
 *          break;
 *      }
 *
 *      // access the file field's value properties
 *      $dest = 'path/to/destination/' . $myForm->file->getValue()->getName();
 *      FF\Utilities\FileUtils::moveUploadedFile($myForm->file->getValue()->getTmpName(), $dest);
 * } while (false);
 * </code>
 *
 * @package FF\Forms
 * @see __call()
 */
class Form
{
    /**
     * Suffix for all suitable field classes
     */
    const FIELDS_CLASS_SUFFIX = 'Field';

    /**
     * @var Record
     */
    protected $fields;

    /**
     * @var Record
     */
    protected $violations;

    /**
     * @param AbstractField ...$fields
     */
    public function __construct(AbstractField ...$fields)
    {
        $this->setFields($fields);
        $this->violations = new Record();
    }

    /**
     * @return AbstractField[]
     */
    public function getFields(): array
    {
        return array_values($this->fields->getDataAsArray());
    }

    /**
     * @param AbstractField[] $fields
     * @return $this
     */
    public function setFields(array $fields)
    {
        $this->fields = new Record();
        foreach ($fields as $field) {
            $this->fields->setField($field->getName(), $field);
        }

        return $this;
    }

    /**
     * Retrieves a form field by name
     *
     * @param string $name
     * @return AbstractField|null
     */
    public function getField(string $name): ?AbstractField
    {
        return $this->fields->getField($name);
    }

    /**
     * Sets a field
     *
     * Replaces any existing field with the same name.
     *
     * @param AbstractField $field
     * @return $this
     */
    public function setField(AbstractField $field)
    {
        $this->fields->setField($field->getName(), $field);
        return $this;
    }

    /**
     * Alias to setField()
     *
     * Replaces any existing field with the same name.
     *
     * @param AbstractField $field
     * @return Form
     * @see setField()
     */
    public function addField(AbstractField $field)
    {
        return $this->setField($field);
    }

    /**
     * Removes a field from the form
     *
     * @param string $name
     * @return $this
     */
    public function removeField(string $name)
    {
        $this->fields->unsetField($name);
        return $this;
    }

    /**
     * Checks if a field is present
     *
     * @param string $name
     * @return bool
     */
    public function hasField(string $name): bool
    {
        return $this->fields->hasField($name);
    }

    /**
     * @return AbstractViolation[]
     */
    public function getViolations(): array
    {
        return array_values($this->violations->getDataAsArray());
    }

    /**
     * Retrieves a field violation by field name
     *
     * @param string $name
     * @return AbstractViolation|null
     */
    public function getViolation(string $name): ?AbstractViolation
    {
        return $this->violations->getField($name);
    }

    /**
     * Checks if a violation is present by field name
     *
     * @param string $name
     * @return bool
     */
    public function hasViolation(string $name): bool
    {
        return $this->violations->hasField($name);
    }

    /**
     * Assigns values to the form
     *
     * $values is Expected to be an associative array.
     * Single values will be assigned to the form's fields according to the keys in $values matching a field's name.
     *
     * If a field's name is missing as key in $values, the field  will be assigned it's default value.
     *
     * @param array $values
     * @return $this
     */
    public function assign(array $values)
    {
        foreach ($this->fields as $name => $field) {
            $value = $values[$name] ?? $field->getDefaultValue(); // generate default value if necessary
            $field->setValue($value);
        }

        return $this;
    }

    /**
     * Retrieves the list of plain field values
     *
     * @return array
     */
    public function getValues(): array
    {
        $values = [];
        foreach ($this->fields as $name => $field) {
            $values[$name] = $field->getPlain();
        }

        return $values;
    }

    /**
     * Resets all form fields and clears all prior violations
     *
     * @return $this
     */
    public function reset()
    {
        foreach ($this->fields as $field) {
            $field->reset();
        }
        $this->violations->clear();

        return $this;
    }

    /**
     * Validates the form's state
     *
     * A form is considered invalid if at least on of it's fields is considered invalid.
     * All fields will be validated whether or not an invalid field was detected prior in the process.
     *
     * Encountered violations will be stored and can be accessed via getViolation() or hasViolation() using
     * a field's name.
     *
     * In a scenario where you must apply validation rules that cannot be represented via field constraints,
     * your form class should overwrite this method.
     *
     * On common example would be case where one field's validation depends on the state of another field.
     * ("field A is required if checkbox field B is checked")
     * In this or similar cases you may add further constraints (here a RequiredConstraint) to a field (field A)
     * on validation (if {field B}->isChecked() == true).
     *
     * @return bool
     */
    public function isValid(): bool
    {
        $this->violations->clear();

        $isValid = true;
        /** @var @var AbstractField $field */
        foreach ($this->fields as $name => $field) {
            if (!$field->isValid()) {
                $this->violations->setField($name, $field->getViolation());
                $isValid = false;
            }
        }

        return $isValid;
    }

    // <editor-fold defaultstate="collapsed" desc="[ Magic API ]">

    /**
     * Magic wrapper for creating new fields
     *
     * Use the first part of the fields class name (stripping the 'Field' suffix)
     * with a lower-cased first letter as magic method.
     * Any arguments passed to the magic method will be passed to FieldsFactory::create()
     * in the given order. The first argument should always be the field's name.
     *
     * Examples:
     * <code>
     * Form::text('foo')              <=> new \FF\Forms\Fields\TextField('foo');
     * Form::radio('foo', ['a', 'b']) <=> new \FF\Forms\Fields\RadioField('foo', ['a', 'b']);
     * </code>
     *
     * @param string $method
     * @param array $args
     * @return AbstractField
     * @throws ClassNotFoundException
     */
    public static function __callStatic(string $method,  array $args)
    {
        try {
            $className = ucfirst($method) . self::FIELDS_CLASS_SUFFIX;

            return FieldsFactory::getInstance()->create($className, ...$args);
        } catch (ClassNotFoundException $e) {
            // trigger fatal error: unsupported method call
            // mimic standard php error message
            // Fatal error: Call to undefined method {class}::{method}() in {file} on line {line}
            $backTrace = debug_backtrace();
            $errorMsg = 'Call to undefined method ' . __CLASS__ . '::' . $method . '() '
                . 'in ' . $backTrace[0]['file'] . ' on line ' . $backTrace[0]['line'];
            trigger_error($errorMsg, E_USER_ERROR);

            return null;
        }
    }

    /**
     * Provides generic access for retrieving a form's field
     *
     * @param string $key
     * @return AbstractField
     */
    public function __get(string $key): ?AbstractField
    {
        return $this->fields->getField($key);
    }

    /**
     * Provides generic access for setting a form's field
     *
     * @param string $key
     * @param AbstractField $value
     */
    public function __set(string $key, AbstractField $value)
    {
        $this->fields->setField($key, $value);
    }

    /**
     * Provides generic access for checking a form's field existence
     *
     * @param string $key
     * @return bool
     */
    public function __isset(string $key): bool
    {
        return $this->hasField($key);
    }

    /**
     * Provides generic access for removing a form's field
     *
     * @param string $key
     */
    public function __unset(string $key)
    {
        $this->fields->unsetField($key);
    }

    // </editor-fold>
}