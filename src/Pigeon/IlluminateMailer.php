<?php

namespace Larablocks\Pigeon;

use ErrorException;
use Illuminate\Config\Repository as Config;
use Illuminate\Mail\Mailer;
use Illuminate\Log\Writer as Logger;

/**
 * Class IlluminateMailer
 * @package Pigeon
 *
 * This class utilizes Laravel 5 Illuminate Mailer methods for Pigeon
 *
 */
class IlluminateMailer extends MessageAbstract implements PigeonInterface
{
    /**
     * Mailer instance
     *
     * @Illuminate\Mail\Mailer
     */
    private $mailer;

    /**
     * Logger instance
     *
     * @Illuminate\Support\Facades\Log
     */
    private $logger;

    /**
     * Illuminate Mailer Constructor
     *
     * @param Mailer $mailer
     * @param MessageLayout $message_layout
     * @param Config $config
     * @param Logger $logger
     */
    public function __construct(Mailer $mailer, MessageLayout $message_layout, Config $config, Logger $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;

        parent::__construct($message_layout, $config);
    }

    /**
     * Send Mail
     *
     * @param null $raw_message
     * @return bool
     */
    public function send($raw_message = null)
    {
        // Set Optional Message Data
        if (!is_null($raw_message)) {
            $send_result = $this->sendRawMessage($raw_message);
        } else {
            $send_result = $this->sendMessage();
        }

        // Reset to default after send
        $this->restoreDefaultMessageType();

        return (bool) $send_result;
    }


    /**
     * Send Message with View
     *
     * @return bool
     */
    private function sendMessage()
    {
        try {
            $this->mailer->send($this->message_layout->getViewLayout(), $this->message_layout->getMessageVariables(), function ($message) {

                if (config('pigeon.dev.override')) {
                    $message->to(config('pigeon.dev.override_email'));
                } else {
                    $message->to($this->to);
                }

                // Set message parts
                $message->subject($this->subject)
                    ->cc($this->cc)
                    ->bcc($this->bcc)
                    ->replyTo($this->reply_to)
                    ->sender($this->sender);

                if (!empty($this->from)) {
                    $message->from($this->from);
                }

                // Set all attachments
                foreach ($this->attachments as $a) {
                    $message->attach($a['path'], $a['options']);
                }
            });

        } catch (ErrorException $e) {
            $msg = 'Pigeon could not send message: ' . $e->getMessage();
            $this->logger->error($msg);
            return false;
        } catch (\Swift_TransportException $e) {
            $msg = 'SMTP failure: ' . $e->getMessage();
            $this->logger->error($msg);
            return false;
        }

        return true;
    }


    /**
     * Send Raw Message
     *
     * @param $message
     * @return bool
     */
    private function sendRawMessage($message)
    {
        try {
            $this->mailer->raw($message, function ($message) {


                if (config('pigeon.dev.override')) {
                    $message->to(config('pigeon.dev.override_email'));
                } else {
                    $message->to($this->to);
                }

                // Set message parts
                $message->subject($this->subject)
                    ->cc($this->cc)
                    ->bcc($this->bcc)
                    ->replyTo($this->reply_to)
                    ->sender($this->sender);

                if (!empty($this->from)) {
                    $message->from($this->from);
                }

                // Set all attachments
                foreach ($this->attachments as $a) {
                    $message->attach($a['path'], $a['options']);
                }
            });
        } catch (ErrorException $e) {
            $msg = 'Pigeon could not send message: ' . $e->getMessage();
            $this->logger->error($msg);
            return false;
        } catch (\Swift_TransportException $e) {
            $msg = 'SMTP failure: ' . $e->getMessage();
            $this->logger->error($msg);
            return false;
        }

        return true;
    }
}