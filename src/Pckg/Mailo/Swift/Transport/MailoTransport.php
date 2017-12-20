<?php namespace Pckg\Mailo\Swift\Transport;

use Pckg\Mail\Service\Mail\Attachment;
use Pckg\Mailo\Api\Api;
use Swift_Events_EventDispatcher;
use Swift_Events_EventListener;
use Swift_Events_SendEvent;
use Swift_Mime_Message;
use Swift_Transport;

/**
 * Implements REST API call for Mailo mailing service.
 */
class MailoTransport implements Swift_Transport
{

    /** The event dispatcher from the plugin API */
    private $_eventDispatcher;

    /**
     * @var Api
     */
    protected $mailoApi;

    /**
     * Constructor.
     */
    public function __construct(Swift_Events_EventDispatcher $eventDispatcher, Api $mailoApi)
    {
        $this->_eventDispatcher = $eventDispatcher;
        $this->mailoApi = $mailoApi;
    }

    /**
     * Tests if this Transport mechanism has started.
     *
     * @return bool
     */
    public function isStarted()
    {
        return true;
    }

    /**
     * Starts this Transport mechanism.
     */
    public function start()
    {
    }

    /**
     * Stops this Transport mechanism.
     */
    public function stop()
    {
    }

    /**
     * Sends the given message.
     *
     * @param Swift_Mime_Message $message
     * @param string[]           $failedRecipients An array of failures by-reference
     *
     * @return int The number of sent emails
     */
    public function send(Swift_Mime_Message $message, &$failedRecipients = null)
    {
        if ($evt = $this->_eventDispatcher->createSendEvent($this, $message)) {
            $this->_eventDispatcher->dispatchEvent($evt, 'beforeSendPerformed');
            if ($evt->bubbleCancelled()) {
                return 0;
            }
        }

        dd('in transport');
        $this->callMailoApi($message);

        if ($evt) {
            $evt->setResult(Swift_Events_SendEvent::RESULT_SUCCESS);
            $this->_eventDispatcher->dispatchEvent($evt, 'sendPerformed');
        }

        $count = (
            count((array)$message->getTo())
            + count((array)$message->getCc())
            + count((array)$message->getBcc())
        );

        return $count;
    }

    /**
     * Register a plugin.
     *
     * @param Swift_Events_EventListener $plugin
     */
    public function registerPlugin(Swift_Events_EventListener $plugin)
    {
        $this->_eventDispatcher->bindEventListener($plugin);
    }

    /**
     * @param Swift_Mime_Message $message
     */
    private function callMailoApi(Swift_Mime_Message $message)
    {
        $subject = $message->getSubject();
        $content = $message->getBody();
        $from = $message->getFrom();
        $to = $message->getTo();

        $attachments = [];
        foreach ($message->getChildren() as $child) {
            if ($child instanceof Attachment) {
                $attachments[] = [
                    'path' => $child->getPath(),
                    'name' => $child->getFilename(),
                ];
            }
        }

        $this->mailoApi->mail()->send([
                                          'from'    => $from,
                                          'to'      => $to,
                                          'subject' => $subject,
                                          'html'    => $content,
                                      ], $attachments);
    }

}
