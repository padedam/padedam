<?php

namespace NFQ\AssistanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use NFQ\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AssistanceRequest
 *
 * @ORM\Table(name="assistance_request")
 * @ORM\Entity
 */
class AssistanceRequest
{
    const STATUS_WAITING = 'WAITING';
    const STATUS_TAKEN = 'TAKEN';
    const STATUS_DONE = 'DONE';
    const STATUS_CANCELED = 'CANCELED';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="shortDescription", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $shortDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="longDescription", type="text")
     *
     * @Assert\NotBlank()
     */
    private $longDescription;


    /**
     * @ORM\Column(name="date", type="datetime", nullable=false)
     * @ORM\Version
     * @var \DateTime
     */
    private $date;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="NFQ\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", columnDefinition="enum('WAITING', 'TAKEN', 'DONE', 'CANCELED')")
     */
    private $status;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="NFQ\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="helper_id", referencedColumnName="id")
     */
    private $helper=NULL;

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
     * Set shortDescription
     *
     * @param string $shortDescription
     * @return AssistanceRequest
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * Get shortDescription
     *
     * @return string 
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set longDescription
     *
     * @param string $longDescription
     * @return AssistanceRequest
     */
    public function setLongDescription($longDescription)
    {
        $this->longDescription = $longDescription;

        return $this;
    }

    /**
     * Get longDescription
     *
     * @return string 
     */
    public function getLongDescription()
    {
        return $this->longDescription;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return AssistanceRequest
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param User $user
     */
    public function setOwner(User $user)
    {
        $this->owner = $user;
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return User
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * @param User $helper
     */
    public function setHelper($helper)
    {
        $this->helper = $helper;
    }
}
