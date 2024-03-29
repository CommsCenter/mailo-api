<?php

namespace Pckg\Mailo\Swift\Transport;

use Pckg\Mail\Service\Mail\Attachment;
use Pckg\Mailo\Api\Api;
use Pckg\Mailo\Api\Endpoint\Mail;
use Swift_Events_EventListener;
use Swift_Mime_Message;
use Swift_Transport;

/**
 * Implements REST API call for Mailo mailing service.
 */
class MailoTransport implements Swift_Transport
{
    /**
     * @var Api
     */
    protected $mailoApi;

    /**
     * @var Mail
     */
    protected $mailoMail;

    /**
     * @var string
     */
    protected $mailType = self::TYPE_TRANSACTIONAL;

    protected $campaign = null;

    protected $queue = null;

    const TYPE_TRANSACTIONAL = 'transactional';

    const TYPE_NEWSLETTER = 'newsletter';

    /**
     * Constructor.
     */
    public function __construct(Api $mailoApi)
    {
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
        $this->callMailoApi($message);

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
            // @phpstan-ignore-next-line
            if ($child instanceof Attachment) {
                $attachments[] = [
                    'path' => $child->getPath(),
                    'name' => $child->getFilename(),
                ];
            }
        }

        $this->mailoMail = $this->mailoApi->mail()->send([
                                                             'from'     => $from,
                                                             'to'       => $to,
                                                             'subject'  => $subject,
                                                             'html'     => $content,
                                                             'type'     => $this->mailType,
                                                             'webhook'  => [
                                                                 // some url where we process read notifications
                                                                 // but we need to make communication secure :/
                                                                 'read' => 'https://comms.guru/webhook',
                                                             ],
                                                             'campaign' => $this->campaign,
                                                             'send_at'  => $this->queue,
                                                         ], $attachments);
    }

    public function getMailoMail()
    {
        return $this->mailoMail;
    }

    public function setMailType($type)
    {
        $this->mailType = $type;

        return $this;
    }

    public function setCampaign($campaign)
    {
        $this->campaign = $campaign;

        return $this;
    }

    public function setQueue($queue)
    {
        $this->queue = $queue;

        return $this;
    }
}
