<?php

namespace NFQ\ReviewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use NFQ\UserBundle\Entity\User;
use \DateTime;

/**
 * Review
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Review
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
     * @var boolean
     *
     * @ORM\Column(name="thank", type="boolean")
     */
    private $thank;

    /**
     * @var string
     *
     * @ORM\Column(name="reviewMessage", type="text", nullable=true)
     */
    private $reviewMessage;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="NFQ\UserBundle\Entity\User", inversedBy="gReviews")
     * @ORM\JoinColumn(name="helper_id", referencedColumnName="id")
     */
    private $helper;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="NFQ\UserBundle\Entity\User", inversedBy="wReviews")
     * @ORM\JoinColumn(name="help_getter_id", referencedColumnName="id")
     */
    private $helpGetter;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

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
     * Set thank
     *
     * @param boolean $thank
     * @return Review
     */
    public function setThank($thank)
    {
        $this->thank = $thank;

        return $this;
    }

    /**
     * Get thank
     *
     * @return boolean 
     */
    public function getThank()
    {
        return $this->thank;
    }

    /**
     * Set reviewMessage
     *
     * @param string $reviewMessage
     * @return Review
     */
    public function setReviewMessage($reviewMessage)
    {
        $this->reviewMessage = $reviewMessage;

        return $this;
    }

    /**
     * Get reviewMessage
     *
     * @return string 
     */
    public function getReviewMessage()
    {
        return $this->reviewMessage;
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

    /**
     * @return User
     */
    public function getHelpGetter()
    {
        return $this->helpGetter;
    }

    /**
     * @param User $helpGetter
     */
    public function setHelpGetter($helpGetter)
    {
        $this->helpGetter = $helpGetter;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }
}
