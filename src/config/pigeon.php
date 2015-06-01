<?php

return [

    /*
     * Choose the Mailer Library for Pigeon.
     *
     * Current Options: ['IlluminateMailer']
     *
     */
    'library' => 'IlluminateMailer',

    /*
     * Choose Default Message Configs that will load for any Pigeon instance.
     * These will be overridden by using a message type or changing variables with
     * Pigeon functions.
     *
     *
     */
    'default' => [
        'to' => [],
        'cc' => [],
        'bcc' => [],
        'replyTo' => [],
        'from' => [], // if nothing is entered here, your mail.php default will still be used
        'sender' => [],
        'attachments' => [],
        'subject' => 'Pigeon Delivery',
        'layout' => 'emails.layouts.default',
        'template' => 'emails.templates.default',
        'message_variables' => []
    ],

    /*
     * Set default configs for specific message types.
     *
     * ---Option types---
     * to - single or array of email address
     * cc - single or array of email address
     * bcc - single or array of email address
     * replyTo - single or array of email address
     * from - single or array of email address
     * sender - single or array of email address
     * subject - string
     * attachments - array of attachments
     * layout - view file path
     * template - view file path
     * message_variables - array of message variables

     *
     * Ex.
     *    'user_welcome' => [
     *
     *      'cc' => ['john.doe@myapp.com', 'jane.doe@myapp.com'],
     *      'bcc' => ['customerservice@myapp.com' => 'Customer Service'],
     *      'replyTo' => 'contact@myapp.com',
     *      'from' => ['from@myapp.com' => 'My App'],
     *      'sender' => 'sender@mysmtp.com',
     *      'subject' => 'Welcome New Customer',
     *      'attachments' => [
     *           'path' => base_path().'/public/files/test.pdf',
     *           'options' => [
     *               'as' => 'My Test PDF'
     *           ]
     *       ],
     *      'layout' => 'emails.layouts.customer',
     *      'template' => 'emails.templates.customer.welcome',
     *      'message_variables' = ['appName' => 'My App', 'appUrl' => 'www.myapp.com'],
     *
     *    ]
     *
     */
    'message_types' => [
        /* Message Type Test - can remove after testing */
        'custom_message_type' => [
            'from' => ['from@myapp.com' => 'My Custom App'],
            'subject' => 'My Pigeon Custom Message',
            'layout' => 'emails.layouts.default',
            'template' => 'emails.templates.default'
        ]
    ]
];

