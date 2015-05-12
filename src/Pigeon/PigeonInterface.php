<?php namespace Larablocks\Pigeon;

/**
 * Interface PigeonInterface
 * @package Pigeon
 *
 * Interface for using different PHP mailer libraries with Pigeon
 *
 */
interface PigeonInterface
{

    /**
     * Start New Message
     *
     * @param $message_type
     * @return object
     */
    public function start($message_type = null);

    /**
     * Set Message Type
     *
     * @param $message_type
     * @return object
     */
    public function load($message_type);

    /**
     * Get Message Type
     *
     */
    public function getType();

    /**
     * Sends mail
     *
     * @param null $raw_message
     * @return
     */
    public function send($raw_message = null);

    /**
     * Set Email Layout
     *
     * @param $layout_path
     * @return object
     */
    public function layout($layout_path);

    /**
     * Set Email Template
     *
     * @param $template_path
     * @return object
     */
    public function template($template_path);

    /**
     * Set To Address
     *
     * @param $email_address
     * @return mixed
     */
    public function to($email_address);

    /**
     * Set Email Subject
     *
     * @param $subject
     * @return object
     */
    public function subject($subject);

    /**
     * Adds a Carbon Copy(CC) address
     *
     * @param $address
     * @return object
     */
    public function cc($address);

    /**
     * Adds a Blind Carbon Copy(BCC) address
     *
     * @param $address
     * @return object
     */
    public function bcc($address);

    /**
     * Pass Message variables
     *
     * @param array $message_variables
     * @return mixed
     */
    public function pass(array $message_variables);

    /**
     * Clear All Message Variables Assigned (except template)
     *
     * @return mixed
     */
    public function clear();

    /**
     * Attaches file to mail
     *
     * @param $pathToFile
     * @return object
     */
    public function attach($pathToFile);

}