<?php

/**
 *
 * This file was build with the Entity Designer add-on.
 *
 * https://www.concrete5.org/marketplace/addons/entity-designer
 *
 */

/** @noinspection DuplicatedCode */
/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
/** @noinspection PhpFullyQualifiedNameUsageInspection */

namespace PortlandLabs\Skyline\Entity;

use Doctrine\ORM\Mapping as ORM;
use HtmlObject\Element;
use PortlandLabs\Skyline\Neighborhood\Neighborhood;
use PortlandLabs\Skyline\NeighborhoodListFactory;
use Stripe\Invoice;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\Subscription;

/**
 * @ORM\Entity
 * @ORM\Table(name="`SkylineSites`")
 */
class Site implements \JsonSerializable
{

    const STATUS_INSTALLING = 10;
    const STATUS_ACTIVE = 50;
    const STATUS_SUSPENDED_TRIAL_CANCELLED = 80;
    const STATUS_SUSPENDED_UNPAID = 90;
    const STATUS_DELETED_REMOVAL_IMMINENT = 99;

    const SUBSCRIPTION_STATUS_TRIALING = 'trialing';
    const SUBSCRIPTION_STATUS_ACTIVE = 'active';
    const SUBSCRIPTION_STATUS_PAST_DUE = 'past_due';
    const SUBSCRIPTION_STATUS_UNPAID = 'unpaid';

    /**
     * @ORM\Id @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="`name`", type="string", nullable=true)
     */
    protected $name = '';


    /**
     * @ORM\Column(type="integer", options={"unsigned": true})
     */
    protected $dateCreated;

    public function __construct()
    {
        $this->dateCreated = time();
    }

    /**
     * @ORM\ManyToOne(targetEntity="\Concrete\Core\Entity\User\User")
     * @ORM\JoinColumn(name="uID", referencedColumnName="uID", onDelete="SET NULL")
     */
    protected $author;

    /**
     * @var string
     * @ORM\Column(name="`handle`", type="string", nullable=true)
     */
    protected $handle = '';

    /**
     * @var string
     * @ORM\Column(name="`subscriptionId`", type="string", nullable=true)
     */
    protected $subscriptionId = '';

    /**
     * @var string
     * @ORM\Column(name="`subscriptionStatus`", type="string", nullable=true)
     */
    protected $subscriptionStatus = '';

    /**
     * @var string
     * @ORM\Column(name="`neighborhood`", type="string", nullable=true)
     */
    protected $neighborhood = '';

    /**
     * @var string
     * @ORM\Column(name="`adminPassword`", type="string", nullable=true)
     */
    protected $adminPassword = '';

    /**
     * @var string
     * @ORM\Column(name="`bytesUsed`", type="integer", nullable=true, options={"unsigned": true})
     */
    protected $bytesUsed;

    /**
     * @var integer
     * @ORM\Column(name="`status`", type="integer", nullable=true)
     */
    protected $status;

    /**
     * @var integer
     * @ORM\Column(name="`suspendedTimestamp`", type="integer", nullable=true)
     */
    protected $suspendedTimestamp;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubscriptionId()
    {
        return $this->subscriptionId;
    }

    /**
     * @return string
     */
    public function getSubscriptionStatus()
    {
        return $this->subscriptionStatus;
    }

    /**
     * @return string
     */
    public function getNeighborhood()
    {
        return $this->neighborhood;
    }

    /**
     * @return string
     */
    public function getAdminPassword()
    {
        return $this->adminPassword;
    }

    /**
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function getStatusText()
    {
        switch ($this->status) {
            case self::STATUS_INSTALLING:
                return t('Installation in Progress.');
            case self::STATUS_ACTIVE:
                return t('Site installed and running.');
            case self::STATUS_SUSPENDED_TRIAL_CANCELLED:
                return t('Site suspended (trial cancelled.');
            case self::STATUS_SUSPENDED_UNPAID:
                return t('Site suspended (past due/unpaid)');
            case self::STATUS_DELETED_REMOVAL_IMMINENT:
                return t('Site deleted – removal imminent.');
        }
    }

    /**
     * @return integer
     */
    public function getSuspendedTimestamp()
    {
        return $this->suspendedTimestamp;
    }


    /**
     * @param string $name
     * @return Site
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getDateCreated(string $format = null)
    {
        if ($format) {
            $datetime = new \DateTime();
            $datetime->setTimestamp($this->dateCreated);
            return $datetime->format($format);
        } else {
            return $this->dateCreated;
        }
    }

    /**
     * @param int $dateCreated
     */
    public function setDateCreated(int $dateCreated)
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    /**
     * @param string $handle
     * @return Site
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;
        return $this;
    }

    /**
     * @param string $subscriptionId
     * @return Site
     */
    public function setSubscriptionId($subscriptionId)
    {
        $this->subscriptionId = $subscriptionId;
        return $this;
    }

    /**
     * @param string $subscriptionStatus
     * @return Site
     */
    public function setSubscriptionStatus($subscriptionStatus)
    {
        $this->subscriptionStatus = $subscriptionStatus;
        return $this;
    }

    /**
     * @param string $neighborhood
     * @return Site
     */
    public function setNeighborhood($neighborhood)
    {
        $this->neighborhood = $neighborhood;
        return $this;
    }

    /**
     * @param string $adminPassword
     * @return Site
     */
    public function setAdminPassword($adminPassword)
    {
        $this->adminPassword = $adminPassword;
        return $this;
    }

    /**
     * @param integer $status
     * @return Site
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param integer $suspendedTimestamp
     * @return Site
     */
    public function setSuspendedTimestamp($suspendedTimestamp)
    {
        $this->suspendedTimestamp = $suspendedTimestamp;
        return $this;
    }

    public function getDisplayValue()
    {
        return $this->getName();
    }

    public function getControlPanelUrl()
    {
        return (string) \URL::to('/account/hosting', 'project', $this->getId());
    }

    public function getNeighborhoodObject(): Neighborhood
    {
        $neighborhoodListFactory = app(NeighborhoodListFactory::class);
        $neighborhoodList = $neighborhoodListFactory->createList();
        $neighborhood = $neighborhoodList->getByHandle($this->neighborhood);
        return $neighborhood;
    }

    public function getPublicUrl()
    {
        return $this->getNeighborhoodObject()->getSitePublicUrl($this);
    }

    public function getPublicDomain()
    {
        return $this->getNeighborhoodObject()->getSitePublicDomain($this);
    }

    /**
     * @return string
     */
    public function getBytesUsed(): ?int
    {
        return $this->bytesUsed;
    }

    /**
     * @param string $bytesUsed
     */
    public function setBytesUsed(int $bytesUsed)
    {
        $this->bytesUsed = $bytesUsed;
        return $this;
    }

    /**
     * Returns the size in bytes that the site allowed to use. Determines this from the site subscription type (trial, full),
     * maybe the type of site provisioned, the hosting plan, etc... This is still @TODO.
     * @return int
     */
    public function getBytesQuota(): int
    {
        return 1073741824; // 1gb should be enough for anybody
    }

    public function getSubscription(): ?Subscription
    {
        /**
         * @var $stripe Stripe
         */
        if ($this->subscriptionId) {
            $stripe = app(StripeClient::class);
            return $stripe->subscriptions->retrieve(
                $this->subscriptionId,
                ['expand' => ['customer','latest_invoice']]
            );
        }
        return null;
    }

    public function getUpcomingInvoice(): ?Invoice
    {
        /**
         * @var $stripe Stripe
         */
        if ($this->subscriptionId) {
            $stripe = app(StripeClient::class);
            return $stripe->invoices->upcoming(
                ['subscription' => $this->subscriptionId]
            );
        }
        return null;
    }


    /**
     * Returns a nicely formatted badge representing the site's status in the system. This is fuzzy - it might
     * use the stripe subscription status to popuplate it's value (e.g. "Trial"), or it might use our own internal
     * status for something. It doesn't cleanly map to our internal status OR the stripe status. And that's fine.
     *
     * @return Element
     */
    public function getStatusBadge(): Element
    {
        $badge = new Element('span', '', ['class' => 'bg-info badge badge-info']);
        if ($this->getStatus() === self::STATUS_INSTALLING) {
            $badge->setValue('Installing...');
        } else if ($this->getStatus() === self::STATUS_SUSPENDED_TRIAL_CANCELLED || $this->getStatus() === self::STATUS_SUSPENDED_UNPAID) {
            $badge->class('badge bg-danger badge-danger')->setValue('Cancelled');
        } else if ($this->getStatus() === self::STATUS_DELETED_REMOVAL_IMMINENT) {
            $badge->class('badge bg-danger badge-danger')->setValue('Deleting');
        } else {
            if ($this->getStatus() === self::STATUS_ACTIVE) {
                if ($this->getSubscriptionStatus() == self::SUBSCRIPTION_STATUS_UNPAID) {
                    $badge->class('badge bg-danger badge-danger');
                    $badge->setValue('Suspended');
                }
                if ($this->getSubscriptionStatus() == self::SUBSCRIPTION_STATUS_PAST_DUE) {
                    $badge->class('badge bg-warning badge-warning');
                    $badge->setValue('Past Due');
                }
                if ($this->getSubscriptionStatus() == self::SUBSCRIPTION_STATUS_TRIALING) {
                    $badge->class('badge bg-warning badge-warning');
                    $badge->setValue('Trial');
                }
                if ($this->getSubscriptionStatus() == self::SUBSCRIPTION_STATUS_ACTIVE) {
                    $badge->class('badge bg-success badge-success');
                    $badge->setValue('Active');
                }
            }
        }
        return $badge;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'handle' => $this->getHandle(),
            'name' => $this->getName(),
            'status' => $this->getStatus(),
            'publicDomain' => $this->getPublicDomain(),
            'publicUrl' => $this->getPublicUrl(),
            'controlPanelUrl' => $this->getControlPanelUrl(),
            'password' => $this->getAdminPassword(),
        ];
    }
}
