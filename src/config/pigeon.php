<?php

return [

    /*
     * Choose the Mailer Library for Pigeon.
     *
     * Current Options: ['SwiftMailer']
     *
     */
    'library' => 'SwiftMailer',

    /*
     * Choose Default Message Configs that will load for any Pigeon instance.
     * These will be overridden by using a message type or changing variables with
     * Pigeon functions.
     *
     *
     */
    'default' => [
        'layout' => 'emails.layouts.default',
        'template' => 'emails.templates.default',
        'subject' => 'Pigeon Delivery',
        'to' => [],
        'cc' => [],
        'bcc' => [],
        'message_variables' => []
    ],

    /*
     * Set default configs for specific message types.
     *
     * ---Option types---
     * layout - view file path,
     * template - view file path',
     * subject - string',
     * to - single or array of email address
     * cc - single or array of email address
     * bcc - single or array of email address
     * message_variables - array of message variables
     *
     * Ex.
     *    'user_welcome' => [
     *      'layout' => 'emails.layouts.customer',
     *      'template' => 'emails.templates.customer.welcome',
     *      'subject' => 'Welcome New Customer'
     *      'cc' => ['john.doe@myapp.com', 'jane.doe@myapp.com']
     *      'bcc' => 'customerservice@myapp.com'
     *      'message_variables' = []
     *    ]
     *
     */
    'message_types' => [

    ]
];

