FF\Forms | Fast Forward Components Collection
========================================================================================================================

by Marco Stoll

- <marco.stoll@rocketmail.com>
- <http://marcostoll.de>
- <https://github.com/marcostoll>
- <https://github.com/marcostoll/FF-Forms>
------------------------------------------------------------------------------------------------------------------------

# What is the Fast Forward Components Collection?
The Fast Forward Components Collection, in short **Fast Forward** or **FF**, is a loosely coupled collection of code 
repositories each addressing common problems while building web application. Multiple **FF** components may be used 
together if desired. And some more complex **FF** components depend on other rather basic **FF** components.

**FF** is not a framework in and of itself and therefore should not be called so. 
But you may orchestrate multiple **FF** components to build an web application skeleton that provides the most common 
tasks.

# Introduction - What is FF\Forms?
FF\Forms addresses three common needs:
1. Defining form value constraints  
   What are my required fields?  
   Which rules must be respected for specific field values?
2. Validating form data
3. Checking the form's overall state and the states of the form's fields

## What FF\Forms is not
**FF\Forms** has no frontend. It provides neither decorators nor any other means of  serializing its state to html or 
any other form of mark-up.

What you can do - especially in conjunction with common templating engines - is adding a form instance (and with it all 
of its fields) to your view and using its api to do conditional output (e.g. rendering error messages for invalid form
states or marking single fields as erroneous when required).

## Examples of defining Forms with Constraints

### The Contact Form

    use FF\Forms;
    
    $myContactForm = Forms(
        Form::text('name')->required(),
        Form::text('email')->required()->email(),
        Form::text('message')->required()->maxLength(1000),
        Form::checkbox('mail_me_too')
    );

The code above creates an **FF\Forms\Form** instance with four fields (the three text fields 'name', 'email', and 
'message' as well as one single checkbox field 'mail_me_too'). Each text field as marked as required.
The 'email' field is given an email constraint which enforces its value to match a standard email pattern.
And the 'message' field has a max length constraints that causes the field (and therefore the whole form instance) to be
considered invalid if its value exceeds 1000 characters.
No constraint was added to the checkbox field 'mail_to_me'. But its field type (CheckboxField) comes with a built-in 
bool constraint restricting its accepted values to generic boolean post expressions ('1', 'on', 'yes', and 'true' 
meaning true / '0', 'off', 'no', 'false', and '' meaning false).

**Beware**: If you add multiple constraints to a single field, any given data will be checked against this constraints 
in the given order. As soon as the first constraint detects a violation, the field validation stops.
Therefore in most cases a required constraint should be added first if needed followed by any other constraint.

### The User Registration Form

    use FF\Forms;
    
    /**
     * @param Fields\Values\ScalarValue $value
     * @return bool
     */
    $myUserNameUniquenessValidator = function(Fields\Values\ScalarValue $value) {
        // empty values are considiered valid
        if ($value->isEmpty()) return true;
        
        // return false if the username is already in use
        if (isAlreadyInUse($value->getValue())) {
            return false;
        }
        return true;
    }
    
    $myPasswordField = Form::password('password')->minLength(10);
    $myRegistrationForm = new Form(
        Form::text('username')->required()->minLength(6)->maxLength(20)->custom($myUserNameUniquenessValidator),
        $myPasswordField,
        Form::password('password_repeat')->matches($myPasswordField),
        Form::select('gender', ['female', 'male', 'diverse']),
        Form::text('name'),        
        Form::text('email')->email(),
        Form::checkbox('terms')->required()
    );
    
The code above creates a Form instance with a username, a password, and a password repeat field. Besides the expected 
constraints the username should be checked for uniqueness. To fulfill this requirement a custom constraint was added 
using a self defined callback validator function to inspect the username field's value and decide whether to accept it 
or not.

The two password fields must match. To do this, the second one ('password_repeat') was given a matches constraint with a
reference to the original password fields. Because field instances are unaware it the actual form instance which uses 
them, the matching field instance has to be created first and passed as reference to the matches constraint of the 
second field.

The select field 'gender' has been provided with a list of valid options. Any select field (as well as any multi select 
or radio field) comes with an automatically generated options constraint using the given list of options.

The 'email' is not marked required but has an email pattern constraint. Any field constraint besides the required
constraint is considered met by an empty value. In this case the email constraint of the 'email' field will not raise
any violation if no data or an empty string was assigned to the field. The same logic applies to the 'gender' field,
whose option constraint will not be checked on empty values. 

The 'terms' checkbox was marked as required. Therefore the form would be considered invalid if a non-true value was 
provided for the 'checkbox' field.

### The Logon Form

    use FF\Forms;
    
    $myLogonForm = new Form(
        Form::text('username')->required(),
        Form::password('password')->required(),
        Form::checkbox('remember_me')
    );
    
A generic logon form is rather simple.  
**But beware** that the form itself does not authenticate the user's credentials. It solely ensures that the credentials 
provided match every constraint for a valid logon attempt.

### The File Upload Form

    use FF\Forms;
    
    $myUploadForm = new Form(
        Form::text('title')->required(),
        Form::file('image')->required()->fileSize('10M')->mimeType('image')
    );

File fields will be automatically equipped with an uploaded file constraint to provide the necessary checking for valid 
upload files (see <https://php.net/is_uploaded_file>).
This particular file field instance was given some additional constraints to limit valid file uploads to 10 mb max file 
size and to any image mime type ('image/jpeg', 'image/png', ...).

**Beware** that the file size checks is done on the backend side and therefore after the file has been transferred to 
the server. If your form's upload fields should be limited to a specific maximum, you probably should add a 
**MAX_FILE_SIZE** hidden field to your form's frontend. Additionally you may limit potential file uploads to a maximum 
file size within your **php.ini** configuration (**upload_max_filesize**).

## Examples of Processing and Validating Post Data

    use FF\Forms;
    
    $myForm = new Form (/** some fields */);
    
    // process form data
    do {
        if ($_SERVER['REQUEST_EMTHOD'] != 'POST') {
            // stop if no post request was detected
            break;
        }
        
        if (!$myForm->assign($_POST)->isValid()) {
            // assigned values do not satisfy the form's field constraints.
            break;
        }
        
        // do your stuff here
        // by now all form data is considered valid
        // var_dump($form->{'my_field'}->getValue());        
    } while (false);        
    
This examples demonstrates a simple code block for assigning data to and validating data of a Form instance.

After you've assigned POST data to the form and validated its integrity, you may access and process the form's field 
values using the appropriate api. 

### Processing and Validating Upload Data

    use FF\Forms;
    
    $myUploadForm = new Form (/** some fields including a file field */);
    
    // process form data
    do {
        if ($_SERVER['REQUEST_EMTHOD'] != 'POST') {
            // stop if no post request was detected
            break;
        }
        
        // be sure to assign the $_POST and $_FILES scope as well.
        if (!$myUploadForm->assign($_FILES + $_POST)->isValid()) {
            // assigned values do not satisfy the form's field constraints.
            break;
        }
        
        // do your stuff here
        // by now all form data is considered valid
        // var_dump($form->{'my_field'}->getValue());        
    } while (false);      
    
In a file upload scenario value data is most likely split into the **$_POST** and the **$_FILES** scope. Therefore you 
must merge the two data scopes into one before assigning the combined data to the form.  
Besides this little extra, form processing and validating of forms having file fields do not differ from forms that do 
not.

## Examples of Checking Form and Field States

    use FF\Forms;
    
    $myForm = new Form (/** some fields including one named 'my_field' **/);
    
    // ... assign value data to the form
    
    // checking the forms overall state
    var_dump($myForm->isValid()); // bool
    
    // checking a specific field's state
    var_dump($myForm->my_field->hasValue()); // bool, checks whether a value was assigned to the field
    var_dump($myForm->my_field->isValid()); // bool, checks whether the field's current value meets all constraints
    
    // retrieving field constraint violation information    
    var_dump($myForm->getViolations()); // collection of AbstractViolation, empty in case of a valid form data
    var_dump($myForm->hasViolation('my_field)); // bool, true if data of 'my_field' violates at least on of its constraints
    var_dump($myForm->my_field->hasViolation(); // bool, true if data of 'my_field' violates at least on of its constraints
    var_dump($myForm->getViolation('my_field')); // AbstractViolation or null in case of a valid field
    var_dump($myForm->my_field->getViolation()); // AbstractViolation or null in case of a valid field
    
    // inspecting a specific violation
    if ($myForm->my_field->hasViolation()) {
        $violation = $myForm->my_field->getViolation();
        var_dump(get_class($violation)); // check the type of the violation
        var_dump($violation->getConstraint()); // AbstractConstraint, retrieve the constraint instance that detected the violation
        var_dump($violation->getValue()); // AbstractValue, retrieve the value violating the constraint 
    }
     
You may ask the form or any of its fields for their validation state at any time. But naturally before assigning values 
to a form instance it will always be considered valid a will show no constraint violations at all.

### Doing conditional Output with Twig

    {% if not myForm.isValid %}
    <div class="form-error">
        The data you've provided is invalid.
    </div>
    {% endif %}
    
    <form name="my_form" method="post">
        <div class="form-field{% if not myForm.my_field.isValid %} form-field-error{% endif %}">
            <label for="my_field">My Field (*)</label>
            <input type="text" name="my_field" id="my_field" value="{{ myForm.my_field.value }}">
            {% if not myForm.my_field.isValid %}
            <div class="form-field-error-msg">
                This is a required field.
            </div>
            {% endif %}
        </div>
    </form>
    
When showing feedback of invalid form fields, you may also inspect the concrete violation to display a suitable error
message (e.g. 'missing value for a required field' vs. 'not a valid email address').    

# Installation

## via Composer

## manual Installation

# Basic Usage
Some basic examples of using **FF\Forms** you've seen above.  
The most common usage scenarios consists of the following steps:
1. Create a Form instances with a set of form fields and the constraints
   
   You can do this by either call the constructor of **Form**
   
        use FF\Forms;
        
        $myForm = new Form(
            Form::text('first_name')->required(),
            Form::text('last_name')->required(),
            Form::select('gender', ['female', 'male', 'diverse'])
        );
        
   or you just subclass **Form** and do your field and constraint definition there
   
        use FF\Forms;
        
        /**
         * @property Fields\TextField $first_name
         * @property Fields\TextField $last_name
         * @property Fields\SelectField $gender
         */
        class MyForm extends Form
        {
            public function __construct()
            {
                parent::__construct(
                    Form::text('first_name')->required(),
                    Form::text('last_name')->required(),
                    Form::select('gender', ['female', 'male', 'diverse'])
                );
            }
        }
        
   The second approach offers you the benefit of letting your editor and it's auto-complete feature know of the actual 
   form fields you've added to your form - they are accessible via the magic methods of the form.     
   
2. Assign values to the Form instance

   `Form::assign(array $values)` is your method to add values to your form, or to be precise to its fields.
   In most cases you will be using some of php's magic variables $_POST, $_GET or $_FILES (or some combination there of)
   as input. But you are by no means restricted to this. 
   **FF\Forms** is primarily designed to handle html form data. This is reflected by mostly by the naming of many of
   classes, methods and properties. But in the end, you may validate any data represented by a key/value pair.
   
   As soon as you call `assign(array $values)`, the values will be passed to the `setValue()` methods of the 
   corresponding field identified by the key of the `$values` array. Any key in `$values` that is not present as a form 
   field will be ignored. On the other hand existing fields that do not get an entry in `$values` will be passed there
   default values (e.g. an empty string in case of a text field).
   As a result each and every form field will be assigned a value every time you call `assign(array $values)` on your
   form.
   
   You may add values to single form fields directly:
   
        $myForm->my_field->setValue('some value');
        
   But you have to be sure to pass the appropriate simple data type (strings in most cases or an array for multi-select
   fields). You also may pass a suitable instance of the value wrapper classes found under **FF\Forms\Fields\Values**.
   
3. Trigger the form value validation

   `Form::isValid(): bool` is your method to trigger the data validation returning the validation result as a boolean. 
   Once invoked, your Form instance will hold any constraint violation information until you call `Form::reset()`. You
   may access the complete set of violations via `Form::getViolations()` any field specific violation via
   `Form->getViolation('my_field');`.
   
   In detail the form will call the `isValid()` of all of its fields in the given order. Any negative response will 
   make the form instance to return `false` as well. Only a complete list of valid fields will make the form instance
   valid. 
   
   *Important notice*  
   An "empty" form - a form without added values to its fields - is considered valid irrespective of any constraints
   added to its fields.

4. React according the validation results by either process the form's data (in case of no violations) or handle errors 
   (if at least a single constraint was not met)
   
   The most common scenario would probably be receiving form data from a web client that should be processed via a 
   controller action or something similar. My approach for this use case often resembles something like this:
   
        /**
         * an action method defined to answer a form page/form submit request
         */
        public function contactAction()
        {
            $myForm = new ContactForm();
            do {
                // scan for post requests
                if ($_SERVER['REQUEST_METHOD'] != 'POST') break;
                
                // assign $_POST values and validate form
                if (!$myForm->assign($_POST)->isValid()) break;
                
                // After this your form holds valid values only.
                // You may now process the form data by for example
                // persiting them to a storage device or sending it via mail.
                // Subsequent to this could redirect to a message page
                // informing the user of the action's result.
                
                return new RedirectResponse(/** redirect target **/);
            } while (false);
            
            // Here the $myForm instance may either by empty or holding values marked as invalid.
            // So when rendering you form page using the $myForm instance you may use its
            // api to do condintional output (showing error messages or marking invalid fields).
            
            return new MyRespone($myForm);  
        }
        
    **Beware** that this is no functional code. But you should encounter resembling scenarios in many mvc-based 
    framework or application environments.

# Advanced Usage

Sometimes basic usage isn't enough. You may encounter more complex data validation needs that cannot be represented
by the constraint classes coming with this package. In this section I will describe some more advanced scenario. 

## Validating Checkbox Groups

If your form contains a checkbox group meaning a list of checkbox fields using the same field name but different values,
you cannot use the standard checkbox field class to process and validate its values.

An example:

    <form method="post">
        <div class="checkboxgroup">
            <label>Do you like ...</label>
            <div class="checkbox"><input type="checkbox" name="food" value="IT"> Italian food?</div>
            <div class="checkbox"><input type="checkbox" name="food" value="CH"> Chinese food?</div>
            <div class="checkbox"><input type="checkbox" name="food" value="MX"> Mexican food?</div>
            <div class="checkbox"><input type="checkbox" name="food" value="JP"> Japanses food?</div>
        </div>
    </form>
    
The checkboxes used do not represent a yes/no choice but an n of m selection much like a multi-select field.    
And according to that you may just use a MultiSelectField instance to represent this structure on the server side.

    use FF\Forms;
    
    class FoodChoiceForm extends Form
    {
        public function __construct()
        {
            parent::__construct(
                Form::multiSelect('food', ['IT', 'CH', 'MX', 'JP']);
            );
        }
    }
    
You could add a required validator to enforce the checking of at least on checkbox.    

## Adding Custom Constraints

**FF\Forms** comes with a list of built-in constraints. This constraints provide logic for the most common validation
rules to define for a single field (like marking a field as required or testing for specific input).
The **CustomConstraint** class on the other hand lets you define your own custom data validation logic. To use it you
have to provide a callable validation callback and pass it to the constraint.

A valid **CustomConstraint** callback function must meet the following specifications:

1. It must be a `callable` - obviously.
2. It must accept exactly one argument of the type `FF\Forms\Fields\Values\AbstractValue` or any of its sub classes
   matching the value type processed be the form field the constraint is added to.
3. It must return a boolean. True if your logic accepts the value, false if it doesn't.

### Example 1: Testing for Uniqueness of a User Name

    use FF\Forms;
    
    class MyRegistrationForm extends Form
    {
        public function construct()
        {
            parent::__construct(
                Form::text('username')->required()->custom([$this, 'checkUniqueUsername'),
                /** more form fields ++/
            );
        }
        
        /**
         * Validation callback for the username field's custom constraint
         *
         * @param Fields\Values\ScalarValue $value
         * @return bool
         */
        public function checkUniqueUsername(Fields\Values\ScalarValue $value): bool
        {
            // You normally want to consider empty values as valid.
            // Use additional required constraints to ensure user input.
            if ($value->isEmpty()) return true;
            
            $userNameIsUnique = WhatEverYourProjectDoesToCheckThis($value->getValue());
            
            return $userNameIsUnique;
        }
    }
    
### Example 2: Checking the Number of Selected Options    
    
If you have a multi-select field (or a similar checkbox group) that require the user to select at least, at most or 
exactly a specific amount of options, a required constraint just isn't the right choice anymore - at least not as the
only constraint added to the field.

    use FF\Forms;
        
    class FoodChoiceForm extends Form
    {
        public function __construct()
        {
            parent::__construct(
                Form::multiSelect('food', ['IT', 'CH', 'MX', 'JP'])->required()->custom([$this;, 'checkOptionCount'])
            );
        }
        
        /**
         * Validates that at least two options have been chosen
         *
         * @param Fields\Values\ArrayValue $value
         * @return bool
         */
        public function checkOptionCount(Fields\Values\ArrayValue $value): bool
        {
            return count($value->toArray()) >= 2;
        } 
    }
    
## Doing Custom Form Data Assignments     

@todo

## Doing Custom Form Validation

Imagine a scenario where your form contains one field (say an email field) that must be filled (is required) if a 
certain other field has a specific value (say a "send me a notification via email" checkbox).
Here you cannot add a required constraint to the email field because it should only be required in certain cases (the 
checkbox field being checked).

In this and similar cases your option of choice is to subclass **Form** class and override its `isValid()` method.
You may do something like this:

    use FF\Forms;
    
    class MyForm extends Form
    {
        public function __construct()
        {
            parent::__construct(
                Form::checkbox('notify_me'),
                Form::text('email')->email() // not required!!
            );
        }
        
        /**
         * Override the standard validation routine
         *
         * @return bool
         */
        public function isValid(): bool
        {
            // test if notfiy_me checkbox is checked
            if ($this->notify_me->isChecked()) {
                $this->email->required(); // add a required constraint before validation
            }
            
            return parent::isValid();
        }
    }

# Form Security

@todo

# Extending FF\Forms

The main logic of the **FF\Forms** components lays in its fields and constraints definitions. And this would also be the 
most common point for customization and extension. The component library comes with the definition of all common html
field types and a bunch of standard constraints ready to use. But it would be reasonable to assume, that this list does
not satisfy all your project's needs.

## Adding new Constraint Types

To add new constraints you may simply use the CustomConstraint described above to use your own validation logic. But
you may also declare your own Constraint classes. 
To do this you need to sub class from `FF\Forms\Fields\Constraints\AbstractConstraint` and implement all of its abstract 
methods. Alternatively you may sub class one of the concrete constraints classes and override the logic you need to 
change.

    use FF\Forms\Fields\Constraints\AbstractConstraint;
    
    class MyConstraint extends AbstractConstraint
    {
        /**
         * Checks the given value violates the constraint's rules
         *
         * @param AbstractValue $value
         * @return AbstractViolation|null
         */
        public function check(AbstractValue $value): ?AbstractViolation
        {
            // insert your validation logic here
        }
    }
    
Some hints for writing your own constraints:

- Some constraints use parameters (see MaxLengthConstraint for example). If your constraint needs parameters you should 
  define an appropriate constructor accepting these parameters in the way you want it to.
  If you do that, the user of the constraint passes the arguments when adding the constraint to a field via the magic 
  method.
  
        Form::text('my_field')->myConstraint($param1, $param2);

- In most cases your constraints should not raise a violation on empty values. Remember that you do not want to 
  duplicate the RequiredConstraint. So one of the first things you want to is to test the value for its emptiness.
  
        if ($value->isEmpty()) return null; // do not raise violations on empty values     

- Also in most cases you want to return an `FF\Forms\Fields\Constraints\Violations\InvalidValueViolation` if your
  value check fails. There are a number of different predefined violations. And you also may declare your own custom
  violation. The may purpose of different violation types is the possibility for processes to react on different
  violations to for example provide different error messages.
  
        return new InvalidValueViolation($this, $value);
        
- In many cases the usage of your new constraint will be limited to certain field types and therefore certain value 
  types. If this is the case you should test the actual class of the `$value` instance passed to your constraint and
  return null on unsuitable value types (in case someone added your constraint to the wrong field).
  
        if (!($value instanceof ScalarValue)) {
            // non-scalar values do not raise violations
            return null;
        }       
        
### Register new Constraints to the ConstraintsFactory

Whenever you add a constraint to a field via the magic methods (eg. `Form::text('my_field')->required()`) a suitable 
constraint instance will be created by the `ConstraintFactory`. Without further configuration the factory will derive 
the actual name of the constraint class from the magic method's name (`required()` -> `RequiredConstraint`) and will 
search the `FF\Forms\Fields\Constraints`  namespace for the appropriate class definition.

If you define new constraints (or override existing ones) within your project, you should store their class definitions 
in your project's namespace and register this namespace at the `ConstraintsFactory`.

Say your define a new constraint class like this:

    namespace MyProject\Forms\Fields\Constraints; // your project's namespace
    
    use FF\Forms\Fields\Constraints\AbstractConstraint;
        
    class MyConstraint extends AbstractConstraint
    {
        /**
         * Checks the given value violates the constraint's rules
         *
         * @param AbstractValue $value
         * @return AbstractViolation|null
         */
        public function check(AbstractValue $value): ?AbstractViolation
        {
            // insert your validation logic here
        }
    }
    
Then - at some point before actually adding a MyConstraint to a field - you do the namespace registering:

    ConstraintsFactory::getInstance()->getClassLocator()->prependNamespaces('MyProject\Forms\Fields\Constraints');
    
As soon as you've done that you may add the new constraint to a field using the magic methods.

    Form::text('my_field')->myConstraint(); 
    
**BEWARE** that the local class names (without the namespace) must be unique to function with the magic methods.
So a `MyProject\Forms\Fields\Constraints\A\MyConstraint` would replace a `...\Constraints\B\MyConstraint` (or vice
versa) according to the order of the namespaces being registered at the constraints factory.

## Adding new Field Types

@todo

### Extending/Overriding existing Field Types

The overall process of registering new field classes is exactly the same as with new constraint classes.
You have to register your project's namespace containing the your field classes tat the `FieldsFactory` before you can
use the `Form` magic methods to create new instances of your form field.

    FieldsFactory::getInstance()->getClassLocator()->prependNamespaces('MyProject\Forms\Fields');
    
As soon as you've done that you may create the new field using the magic methods.

    Form::myField('my_field');     

### ToDos / Road map

@todo