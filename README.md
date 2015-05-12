Pigeon
===============
A more flexible email message builder for Laravel 5 including chained methods, reusable message type configurations, and email layout and template view management.

## Installation

Add `larablocks/pigeon` as a requirement to `composer.json`:

```javascript
{
    "require": {
        "larablocks/pigeon": "5.0.*"
    }
}
```

Note: All Larablocks packages will have versions in line with the laravel framework.

Update your packages with `composer update` or install with `composer install`.

## Laravel Integration

To wire this up in your Laravel project you need to add the service provider. Open `app.php`, and add a new item to the providers array.

```php
'Larablocks\Pigeon\PigeonServiceProvider',
```

Then, add a Facade for more convenient usage. In your `app.php` config file add the following line to the `aliases` array.
Note: The Pigeon facade will load automatically, so you don't have to add it to the `app.php` file but you may still want 
to keep record of the alias.

```php
'Pigeon' => 'Larablocks\Pigeon\Pigeon',
```

To publish the default config file `config/pigeon.php` along with the default email view files use the artisan command: 

`vendor:publish --vendor="Larablocks\Pigeon\PigeonServiceProvider"`

If you wish to not publish the view files and only publish the config then use the artisan command:

`vendor:publish --vendor="Larablocks\Pigeon\PigeonServiceProvider" --tag="config"`

## Usage as a Facade

```php
Pigeon::
```

When Pigeon is used as a facade, each access to the facade will create a new instance of Pigeon therefore all message
properties will be reset to default for each call.

### Setting the Message Properties

Pigeon will load all properties set in the `default` area of your config before you start to manipulate the properties.

####Set layout view file:
```php
Pigeon::layout('emails.layout.my_layout_view')
```

####Set template view file:
```php
Pigeon::template('emails.layout.my_template_view')
```

####Set "to" address or array of "to" addresses:
```php
Pigeon::to('john.doe@domain.com') 
```
or
```php
Pigeon::to(['john.doe@domain.com', 'jane.doe@domain.com']) 
```

####Set "CC" address or array of "CC" addresses:
```php
Pigeon::cc('jane.doe@domain.com') // set the cc address or array of cc addresses
```
or
```php
Pigeon::cc(['john.doe@domain.com', 'jane.doe@domain.com']) 
```

####Set "BCC" address or array of "BCC" addresses:
```php
Pigeon::bcc('jane.doe@domain.com') // set the cc address or array of cc addresses
```
or
```php
Pigeon::bcc(['john.doe@domain.com', 'jane.doe@domain.com']) 
```

####Set Subject:
```php
Pigeon::subject('This is the Subject') 
```

####File Attachment:
```php
Pigeon::attach('/path/to/file/attachment')
```

####Passing View Variables:
```php
Pigeon::pass(['firstVariable' => 'test string', 'secondVariable' => 2, 'thirdVariable' => true])
```
Note: Make sure all variables defined in your layout and template view files are passed.


####Using Pretend:
```php
Pigeon::pretend()
```
This will set the pretend function of Swift Mailer to true so message will be not actually be mailed.
See http://laravel.com/docs/5.0/mail#mail-and-local-development

### Custom Messages

####Load Custom Message:
```php
Pigeon::load('custom_message_type');
```
This will load all the message properties from your config defined for `custom_message_type`.

### Sending the Message

####Send Message:
```php
Pigeon::send();
```

####Send Raw Message:
```php
Pigeon::send('This is my raw message);
```
Using a raw message will ignore any view files set and variables passed and only send whats in string param passed.


### Example - Using it all together

```php
Pigeon::layout('emails.layout.my_layout_view')->template('emails.layout.my_template_view')->to(['john.doe@domain.com', 'jane.doe@domain.com'])->cc('fred.doe@domain.com')
->bcc('george.doe@domain.com')-subject('This is the Subject')->attach('/path/to/file/attachment')->pass(['firstVariable' => 'test string', 'secondVariable' => 2, 'thirdVariable' => true])->send();
```

### Example - Simple call

```php
Pigeon::to('me@domain.com')->subject('Testing Pigeon')->send('Sending myself a quick raw message');
```

### Example - Sending a custom message

```php
Pigeon::load('custom_message_type')->send();
```

## Usage as a Passed Dependency

When Pigeon is injected into a you will be reusing the instance and therefore you must be careful to call start on 
each new message creation to not have properties persist to the next send.

We will use the implements the `Larablocks\Pigeon\PigeonInterface`. For now the only library that we use to implment this
interface is `Larablocks\Pigeon\SwiftMailer`.

###Passing Pigeon to a constructor:
```php
public function __construct(Larablocks\Pigeon\PigeonInterface $pigeon) 
{
$this->pigeon = $pigeon;
}
```

###Starting a new default message:

```php
$this->pigeon->start()->to('me@domain.com')->subject('Testing Pigeon')->send('Sending myself a quick raw message');
```

See how we use the start() method here to make sure the properties from the first send are cleared before we send any subsequent
messages. You may use the fact that the properties persist to your advantage to fire off multiple email types to the same
address for example:

###Starting a new custom message type:
```php
$this->pigeon->start('my_custom_message')->send();
```

Start() can receive a param for the custom message type so we can clear any properties from the last send and load the configs for that message type
at the same time.


### Example - Sending multiple message types to the same address

```php
$pigeon->load('first_custom_message')->to('john.doe@domain.com')->send();
```
```php
$pigeon->load('second_custom_message')->send();
```

## License

Pigeon is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)