<?php

namespace NFQ\ReviewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use NFQ\UserBundle\Entity\User;

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
     * @ORM\Column(name="thumbUp", type="boolean")
     */
    private $thumbUp;

    /**
     * @var string
     *
     * @ORM\Column(name="reviewMessage", type="text")
     */
    private $reviewMessage;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="NFQ\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="helper_id", referencedColumnName="id")
     */
    private $helper;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="NFQ\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="help_getter_id", referencedColumnName="id")
     */
    private $helpGetter;

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
     * Set thumbUp
     *
     * @param boolean $thumbUp
     * @return Review
     */
    public function setThumbUp($thumbUp)
    {
        $this->thumbUp = $thumbUp;

        return $this;
    }

    /**
     * Get thumbUp
     *
     * @return boolean 
     */
    public function getThumbUp()
    {
        return $this->thumbUp;
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
}
