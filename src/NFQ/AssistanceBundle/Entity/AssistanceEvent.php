<?php

namespace NFQ\AssistanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AssistanceEvents
 *
 * @ORM\Table(name="assistance_events")
 * @ORM\Entity(repositoryClass="NFQ\AssistanceBundle\Entity\AssistanceEventRepository")
 * @ORM\HasLifecycleCallbacks
 */
class AssistanceEvent
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="NFQ\AssistanceBundle\Entity\AssistanceRequest", inversedBy="events")
     * @ORM\JoinColumn(name="assistance_request_id", referencedColumnName="id")
     */
    private $assistanceRequest;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="NFQ\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


    /**
     * @var string
     * @ORM\Column(name="assistance_request_status", type="string", columnDefinition="enum('WAITING', 'TAKEN', 'DONE', 'CANCELED')")
     */
    private $assistanceRequestStatus;


    /**
     * @ORM\Column(name="event_time", type="datetime")
     */
    private $eventTime;

    /**
     * @ORM\PrePersist
     */
    public function timestamp()
    {
        if(is_null($this->eventTime)) {
            $this->eventTime = new \DateTime();
        }
        return $this;
    }




    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set assistanceRequestStatus
     *
     * @param string $assistanceRequestStatus
     *
     * @return AssistanceEvent
     */
    public function setAssistanceRequestStatus($assistanceRequestStatus)
    {
        $this->assistanceRequestStatus = $assistanceRequestStatus;

        return $this;
    }

    /**
     * Get assistanceRequestStatus
     *
     * @return string
     */
    public function getAssistanceRequestStatus()
    {
        return $this->assistanceRequestStatus;
    }

    /**
     * Set eventTime
     *
     * @param \DateTime $eventTime
     *
     * @return AssistanceEvent
     */
    public function setEventTime($eventTime)
    {
        $this->eventTime = $eventTime;

        return $this;
    }

    /**
     * Get eventTime
     *
     * @return \DateTime
     */
    public function getEventTime()
    {
        return $this->eventTime;
    }

    /**
     * Set assistanceRequest
     *
     * @param \NFQ\AssistanceBundle\Entity\AssistanceRequest $assistanceRequest
     *
     * @return AssistanceEvent
     */
    public function setAssistanceRequest(\NFQ\AssistanceBundle\Entity\AssistanceRequest $assistanceRequest = null)
    {
        $this->assistanceRequest = $assistanceRequest;

        return $this;
    }

    /**
     * Get assistanceRequest
     *
     * @return \NFQ\AssistanceBundle\Entity\AssistanceRequest
     */
    public function getAssistanceRequest()
    {
        return $this->assistanceRequest;
    }

    /**
     * Set user
     *
     * @param \NFQ\UserBundle\Entity\User $user
     *
     * @return AssistanceEvent
     */
    public function setUser(\NFQ\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \NFQ\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
