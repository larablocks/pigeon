<?php namespace Larablocks\Pigeon;

/**
 * Class MessageLayout
 * @package Pigeon
 *
 * This class handles layout management for the Pigeon package.
 * This holds variables for view files that be used for building email templates.
 *
 *
 */
class MessageLayout
{
    /**
     * Constant representing a variable that will hold the template path in email view.
     */
    const TEMPLATE_VARIABLE = '_template';

    /**
     * Email Layout View Path
     *
     * @var string
     */
    protected $view_layout = 'emails.layouts.default';

    /**
     * Email Template View Path
     *
     * @var
     */
    protected $view_template = 'emails.templates.default';

    /**
     * Message Variables
     *
     * @var array
     */
    protected $message_variables = [];


    /**
     *
     */
    public function __construct()
    {
        $this->assignTemplate();
    }


    /**
     * Set Email View Layout
     *
     * @param $view
     */
    public function setViewLayout($view)
    {
        $this->view_layout = $view;
    }

    /**
     * Get Email View Layout
     *
     * @return mixed
     */
    public function getViewLayout()
    {
        return $this->view_layout;
    }

    /**
     * Set Email View Template
     *
     * @param $template
     */
    public function setViewTemplate($template)
    {
        $this->view_template = $template;

        $this->assignTemplate();
    }


    /**
     * Get Email View Template
     *
     * @return mixed
     */
    public function getViewTemplate()
    {
        return $this->view_template;
    }

    /**
     * Assign Message Variables
     *
     * @param array $message_variables
     */
    public function includeVariables(array $message_variables)
    {
        // Remove the default template variable key from any variable assignments
        unset($message_variables[self::TEMPLATE_VARIABLE]);

        $this->message_variables = array_merge($this->message_variables, $message_variables);
        $this->assignTemplate();
    }


    /**
     * Clear existing message variables
     *
     */
    public function clearVariables()
    {
        $this->message_variables = [];
        $this->assignTemplate();
    }

    /**
     * Get Message Variables
     *
     * @return mixed
     */
    public function getMessageVariables()
    {
        return $this->message_variables;
    }

    /**
     * Assign the template variable into body data
     *
     */
    private function assignTemplate()
    {
        $this->message_variables['_template'] = $this->view_template;
    }

}