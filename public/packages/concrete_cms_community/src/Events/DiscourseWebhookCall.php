<?php

/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\Community\Events;

use Concrete\Core\User\User;
use Symfony\Component\EventDispatcher\GenericEvent;

class DiscourseWebhookCall extends GenericEvent
{
    /** @var User */
    protected $user;
    /** @var string */
    protected $eventType;
    /** @var string */
    protected $eventName;
    /** @var array */
    protected $payload;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return DiscourseWebhookCall
     */
    public function setUser(User $user): DiscourseWebhookCall
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getEventType(): string
    {
        return $this->eventType;
    }

    /**
     * @param string $eventType
     * @return DiscourseWebhookCall
     */
    public function setEventType(string $eventType): DiscourseWebhookCall
    {
        $this->eventType = $eventType;
        return $this;
    }

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return $this->eventName;
    }

    /**
     * @param string $eventName
     * @return DiscourseWebhookCall
     */
    public function setEventName(string $eventName): DiscourseWebhookCall
    {
        $this->eventName = $eventName;
        return $this;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @param array $payload
     * @return DiscourseWebhookCall
     */
    public function setPayload(array $payload): DiscourseWebhookCall
    {
        $this->payload = $payload;
        return $this;
    }



}
