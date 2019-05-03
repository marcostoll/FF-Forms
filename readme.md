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

# Introduction
FF\Forms addresses three common needs:
1. Defining form value constraints
   What are my required fields?
   Which rules must be respected for specific fields values?
2. Validating Post data
3. Checking the form's overall state and the states of the form's fields

## What FF\Forms is not
**FF\Forms** has no frontend. It provides neither decorators nor any other means of  serializing its state to html or 
any other form of mark-up.

What you can do - especially in conjunction with common templating engines - is adding a form instance (and with it all 
of its fields) to your view and using its api to do conditional output (e.g. rendering error messages for invalid form
states or marking single fields as erroneous when required).

## Examples for defining Forms with Constraints

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
bool constraint restricting its accepted values to generic boolean post expression ('1', 'on', 'yes', and 'true' 
meaning true / '0', 'off', 'no', 'false', and '' meaning false).

**Beware**: If you add multiple constraints to a single field, any given data will checked against this constraints in
the given order. As soon as the first constraint detects a violation, the field validation is stopped.
Therefore in most cases a required constraint should be added first if needed followed by any other constraint.

### The User Registration Form

    use FF\Forms;
    
    /**
     * @param Fields\Values\ScalarValue $value
     * @return bool
     */
    $myUserNameUniquenessValidator = function(Fields\Values\ScalarValue $value) {
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
        Form::text('name'),
        Form::select('gender', ['female', 'male', 'diverse']),
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

THe select field 'gender' has been provided with a list of valid options. Any select field (as well as any multi select 
or radio fields) come with an automatically generated options constraint using the given list of options.

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

File fields will be automatically equipped with an UploadedFileConstraint to provide the necessary checking for valid 
file files (see <https://php.net/is_uploaded_file>).
This particular file field instance was given some additional constraints to limit valid file uploads to 10 mb max file 
size and to any image mime type ('image/jpeg', 'image/png', ...).

**Beware** that the file size checks is done on the backend side and therefore after the file has been transferred to 
the server. If your form's upload fields should be limited to a specific maximum, you probably should add a 
**MAX_FILE_SIZE** hidden field to your form's frontend. Additionally you may limit potential file uploads to a maximum 
file size within your **php.ini** configuration (**upload_max_filesize**).

## Examples for Processing and Validating Post Data

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
    
    $myUploadForm = new Form (/** some fields  including a file field*/);
    
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
must merge the to data scopes into one before assigning the combined data to the form.  
Besides this little extra, form processing and validating of forms having file fields do not differ form forms that do 
not.

## Examples for Checking Form and Field States

    use FF\Forms;
    
    $myForm = new Form (/** some fields **/);
    
    // ... assign value data to the form
    
    // checking the forms overall state
    var_dump($myForm->isValid());
    
    // checking a specific field's state
    var_dump($myForm->{'my_field'}->hasValue()); // bool, checks whether a value was assigned to the field
    var_dump($myForm->{'my_field'}->isValid()); // bool, checks whether the field's current value meets all constraints
    
    // retrieving field constraint violation information    
    var_dump($myForm->getViolations()); // collection of AbstractViolation, empty in case of a valid form data
    var_dump($myForm->hasViolation('my_field)); // bool, true if data of 'my_field' violates at least on of its constraints
    var_dump($myForm->getViolation('my_field')); // AbstractViolation or null in case of a valid field
    
    // inspecting a specific violation
    if ($myForm->hasViolation('my_field)) {
        $violation = $myForm->getViolation('my_field');
        var_dump(get_class($violation)); // check the type of the violation
        var_dump($violation->getConstraint()); // AbstractConstraint, retrieve the constraint instance that detected the violation
        var_dump($violation->getValue()); // AbstractValue, retrieve the value violating the constraint 
    }
     
You may ask the form a any of its fields for its validation state at any time. But naturally before assigning values to
a form instance it will always be considered valid a show no constraint violations at all.

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
    
When showing feedback for invalid form fields, you may also inspect the concrete violation to display a suitable error
message ('required field' vs. 'not a valid email address').    

# Installation

# Basic Usage

# Advanced Usage

## Adding Custom Constraints

## Doing Custom Form Validation 

# Form Security

# Extending FF\Forms

## Adding new Constraint Types

### Extending/Overriding existing Constraint Types

## Adding new Field Types

### Extending/Overriding existing Field Types

### ToDos / Road map
