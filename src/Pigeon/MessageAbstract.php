<?php namespace Larablocks\Pigeon;

use Illuminate\Config\Repository as Config;

/**
 * Class MessageAbstract
 * @package Pigeon
 *
 * This class holds the general mailing functions that would be used to create email using Pigeon
 *
 */
abstract class MessageAbstract
{
    /**
     * Constant representing path to defaults definition in config file.
     */
    const DEFAULT_CONFIG_PATH = 'pigeon.default';

    /**
     * Constant representing path to message types definitions in config file.
     */
    const DEFAULT_CONFIG_MESSAGE_TYPE_PATH = 'pigeon.message_types';

    /**
     * Config instance
     *
     * @Illuminate\Config\Repository
     */
    protected $config;

    /**
     * Message Layout instance
     *
     * @MailerLayout
     */
    protected $message_layout;

    /**
     * Message Type
     *
     * Used for setting a particular message type to utilize message configs
     *
     * @var string
     */
    protected $message_type = 'default';

    /**
     * Subject
     *
     * @string
     */
    protected $subject;

    /**
     * To Addresses
     *
     * @string
     */
    protected $to = [];

    /**
     * CC Addresses
     *
     * @string array
     */
    protected $cc = [];

    /**
     * BCC Addresses
     *
     * @string array
     */
    protected $bcc = [];

    /**
     * Reply To Address
     *
     * @string
     */
    protected $reply_to = [];

    /**
     * File Attachments
     *
     * @string array
     */
    protected $attachments = [];


    /**
     * Swift Mailer Abstract Constructor
     *
     * @param MessageLayout $message_layout
     * @param Config $config
     * @throws UnknownMessageTypeException
     */
    public function __construct(MessageLayout $message_layout, Config $config)
    {
        $this->message_layout = $message_layout;
        $this->config = $config;

        $this->loadConfigType('default');
    }

    /**
     * Load Message Type
     *
     * @param $message_type (set in config file)
     * @return $this
     */
    public function type($message_type)
    {
        $this->loadConfigType($message_type);

        return $this;
    }

    /**
     * Get Message Type
     *
     * @return null
     */
    public function getType()
    {
       return $this->message_type;
    }

    /**
     * Set Email Layout
     *
     * @param $layout_path
     * @return $this|object
     */
    public function layout($layout_path)
    {
        $this->message_layout->setViewLayout($layout_path);

        return $this;
    }

    /**
     * Set Email Template
     *
     * @param $template_path
     * @return $this|object
     */
    public function template($template_path)
    {
        $this->message_layout->setViewTemplate($template_path);

        return $this;
    }

    /**
     * Set To
     *
     * @param $address
     * @param null $name
     * @return $this
     */
    public function to($address, $name = null)
    {
        if (is_array($address)) {
            $this->addAddressArray($address, 'to');
        } else {
            $this->to[$address] = $name;
        }

        return $this;
    }

    /**
     * Adds a Carbon Copy(CC) address
     *
     * @param $address
     * @param null $name
     * @return $this|object
     */
    public function cc($address, $name = null)
    {
        if (is_array($address)) {
            $this->addAddressArray($address, 'cc');
        } else {
            $this->cc[$address] = $name;
        }

        return $this;
    }

    /**
     * Adds a Blind Carbon Copy(BCC) address
     *
     * @param $address
     * @param null $name
     * @return $this|object
     */
    public function bcc($address, $name = null)
    {
        if (is_array($address)) {
            $this->addAddressArray($address, 'bcc');
        } else {
            $this->bcc[$address] = $name;
        }

        return $this;
    }

    /**
     * Adds a Reply To address
     *
     * @param $address
     * @param null $name
     * @return $this|object
     */
    public function replyTo($address, $name = null)
    {
        if (is_array($address)) {
            $this->addAddressArray($address, 'replyTo');
        } else {
            $this->reply_to[$address] = $name;
        }

        return $this;
    }

    /**
     * Set Subject
     *
     * @param $subject
     * @return $this|object
     */
    public function subject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Pass Message variables
     *
     * @param array $message_variables
     * @return $this
     */
    public function pass(array $message_variables)
    {
        $this->message_layout->includeVariables($message_variables);

        return $this;
    }


    /**
     * Clear All Message Variables Assigned (except template)
     *
     * @return $this
     */
    public function clear()
    {
        $this->message_layout->clearVariables();

        return $this;
    }

    /**
     * Attaches file to mail
     *
     * @param $pathToFile
     * @param array $options
     * @return $this|object
     */
    public function attach($pathToFile, array $options = [])
    {
        if (is_array($pathToFile)) {
            foreach($pathToFile as $attachment) {

                if (isset($attachment['options']) && array_key_exists("options", $attachment)) {
                    $options = $attachment['options'];
                } else {
                    $options = [];
                }
                $this->addAttachment($attachment['path'], $options);
            }
        } else {
            $this->addAttachment($pathToFile, $options);
        }

        return $this;
    }

    private function addAttachment($pathToFile, array $options = [])
    {
        $attachment['path'] = $pathToFile;
        $attachment['options'] = $options;

        array_push($this->attachments, $attachment);
    }

    /**
     * Adds array of addresses to type
     *
     * @param array $address_array
     * @param $type
     * @return bool
     */
    private function addAddressArray(array $address_array, $type)
    {
        foreach ($address_array as $address => $name) {

            if (!method_exists($this, $type)) {
                return false;
            }

            if (is_int($address)) {
                $this->$type($name);
            } else {
                $this->$type($address, $name);
            }
        }

        return true;
    }


    /**
     * Load the config type passed from configuration file
     *
     * @param $config_type
     * @return bool
     * @throws InvalidMessageTypeException
     * @throws UnknownMessageTypeException
     */
    private function loadConfigType($config_type)
    {
        // Get Path for config
        if ($config_type === 'default') {
            $config_path = self::DEFAULT_CONFIG_PATH;
        } else {
            $config_path = self::DEFAULT_CONFIG_MESSAGE_TYPE_PATH.'.'.$config_type;
        }

        $config_array = $this->config->get($config_path);

        if (is_null($config_array)) {
            throw new UnknownMessageTypeException('Pigeon config not found for type: '.$config_type);
        }

        if (!is_array($config_array)) {
            throw new InvalidMessageTypeException('Pigeon config not set up properly for type: '.$config_type);
        }

        if($this->setConfigOptions($config_array)) {
            $this->message_type = $config_type;
        }

        return true;

    }

    /**
     * Set Configuration Options
     *
     * @param array $config_array
     * @return bool
     */
    private function setConfigOptions(array $config_array)
    {
        foreach ($config_array as $type => $value) {
            $this->setConfigOption($type, $value);
        }

        return true;
    }

    /**
     * Set Specific Config Option
     *
     * @param $option_type
     * @param $option_value
     * @return bool
     */
    private function setConfigOption($option_type, $option_value)
    {
        if ($option_type === 'message_variables') {
            $option_type = 'pass';
        } else if ($option_type === 'attachments') {
            $option_type = 'attach';
        }

        if (!method_exists($this, $option_type)) {
            return false;
        }

        $this->$option_type($option_value);

        return true;
    }

    /**
     * Restore Message to Default Configs
     *
     * @throws InvalidMessageTypeException
     * @throws UnknownMessageTypeException
     */
    protected function restoreDefaultMessageType()
    {
        $this->resetMessage();
        $this->loadConfigType('default');
    }

    /**
     * Reset Message Properties to empty
     */
    private function resetMessage()
    {
        $this->subject = '';
        $this->to = [];
        $this->cc = [];
        $this->bcc = [];
        $this->attachments = [];
        $this->message_layout->clearVariables();
    }
}