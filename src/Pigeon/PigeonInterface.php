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
     * Load Message Type
     *
     * @param $message_type
     * @return object
     */
    public function type($message_type);

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
     * @param $address
     * @param null $name
     * @return
     */
    public function to($address, $name = null);

    /**
     * Adds a Carbon Copy(CC) address
     *
     * @param $address
     * @param null $name
     * @return object
     */
    public function cc($address, $name = null);

    /**
     * Adds a Blind Carbon Copy(BCC) address
     *
     * @param $address
     * @param null $name
     * @return object
     */
    public function bcc($address, $name = null);


    /**
     * Adds a Reply To address
     *
     * @param $address
     * @param null $name
     * @return object
     */
    public function replyTo($address, $name = null);

    /**
     * Set Email Subject
     *
     * @param $subject
     * @return object
     */
    public function subject($subject);

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