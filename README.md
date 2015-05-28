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

Note: All Larablocks packages will have versions in line with the core Laravel framework.

Update your packages with `composer update` or install with `composer install`.

## Laravel Integration

To wire this up in your Laravel project you need to add the service provider. Open `app.php`, and add a new item to the providers array.

```php
'Larablocks\Pigeon\PigeonServiceProvider',
```

Then you may add a Facade for more convenient usage. In your `app.php` config file add the following line to the `aliases` array.

```php
'Pigeon' => 'Larablocks\Pigeon\Pigeon',
```

Note: The Pigeon facade will load automatically, so you don't have to add it to the `app.php` file but you may still want 
to keep record of the alias.

To publish the default config file `config/pigeon.php` along with the default email view files use the artisan command: 

`php artisan vendor:publish --provider="Larablocks\Pigeon\PigeonServiceProvider"`

If you wish to not publish the view files and only publish the config then use the artisan command:

`php artisan vendor:publish --provider="Larablocks\Pigeon\PigeonServiceProvider" --tag="config"`

## Usage as a Facade

```php
Pigeon::
```

### Setting the General Message Properties

Pigeon will load all properties set in the `default` area of your config before you construct your message.

####Set addresses

All these address add methods can be used with any of the address add functions (to, cc, bcc, replyTo)

Add a single address with no name
```php
Pigeon::to('john.doe@domain.com') 
Pigeon::cc('john.doe@domain.com') 
Pigeon::bcc('john.doe@domain.com') 
Pigeon::replyTo('john.doe@domain.com') 
```

Add a single address with name
```php
Pigeon::to('john.doe@domain.com', 'John Doe')
....
```

Add array of addresses with no names
```php
Pigeon::to(['john.doe@domain.com', 'jane.doe@domain.com']) 
...
```

Add array of addresses some with names, some without names
```php
Pigeon::to(['john.doe@domain.com' => 'John Doe', 'jane.doe@domain.com']) 
...
```

####Set Subject:
```php
Pigeon::subject('My Subject') 
```

####File Attachments:

Attach a single file with no options
```php
Pigeon::attach('/path/to/file/attachment')
```

Attach a single file with options
```php
Pigeon::attach('/path/to/file/attachment', ['as' => 'Attachment', 'mime' => 'jpg'])
```

Attach an array of files
```php
Pigeon::attach([
    [
     'path' => '/path/to/file/attachment1'
     'options' => []
    ],
    [
     'path' => '/path/to/file/attachment2'
     'options' => ['as' => 'Attachment 2', 'mime' => 'pdf']
    ]
])
```

### Setting the Message View Properties

####Set layout view file:
```php
Pigeon::layout('emails.layouts.my_layout_view')
```

####Set template view file:
```php
Pigeon::template('emails.templates.my_template_view')
```

####Passing View Variables:
```php
Pigeon::pass([
 'firstVariable' => 'test string', 
 'secondVariable' => 2, 
 'thirdVariable' => true
])
```
Note: Make sure all variables pre-defined in your layout and template view files are passed to your Pigeon message.

####Clearing View Variables
```php
Pigeon::clear()
```

###Using Pretend:
```php
Pigeon::pretend()
```

This will set the pretend function of Swift Mailer to true so message will be not actually be mailed.
See http://laravel.com/docs/5.0/mail#mail-and-local-development

### Custom Messages Types

Custom message types will be configured in the `config/pigeon.php` file. In this file you can find examples on how 
to properly set up a custom message type.

####Load Custom Message:
```php
Pigeon::type('custom_message_type');
```

This will load all the message properties from your config defined for `custom_message_type`.

### Sending the Message

####Send Message:
```php
Pigeon::send();
```

####Send Raw Message:
```php
Pigeon::send('This is my raw message');
```

Using a raw message will ignore any view files set and variables passed and only send whats in string param passed.


### Example - Using it all together

```php
Pigeon::to(['john.doe@domain.com', 'jane.doe@domain.com'])
->cc('fred.doe@domain.com')
->bcc('george.doe@domain.com')
->subject('This is the Subject')
->attach('/path/to/file/attachment')
->layout('emails.layouts.my_layout_view')
->template('emails.templates.my_template_view')
->pass(['firstVariable' => 'test string', 'secondVariable' => 2, 'thirdVariable' => true])
->send();
```

### Example - Simple call

```php
Pigeon::to('me@domain.com')->subject('Testing Pigeon')->send('Sending myself a quick raw message');
```

### Example - Sending a custom message

```php
Pigeon::type('test_message')->to('me@domain.com')->send();
```

## Usage as a Passed Dependency

To pass Pigeon as a dependency will will pass the interface `Larablocks\Pigeon\PigeonInterface`. For now the only library 
that implements this interface is `Larablocks\Pigeon\SwiftMailer` but we want to allow for other mailing libraries to be used in the future.
The `config/pigeon.php` config file for Pigeon automatically sets SwiftMailer as the default mailer library for you.

```php
'library' => 'SwiftMailer',
```

###Passing Pigeon to a constructor:
```php
public function __construct(Larablocks\Pigeon\PigeonInterface $pigeon) 
{
$this->pigeon = $pigeon;
}
```

###Starting a new default message:

```php
$this->pigeon->to('me@domain.com')->subject('Pigeon Raw Test Message')->send('Sending myself a quick raw message');
```

###Starting a new custom message type:
```php
$this->pigeon->type('test_message')->to('me@domain.com')->send();
```

## License

Pigeon is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)