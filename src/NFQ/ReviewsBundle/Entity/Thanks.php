<?php

namespace NFQ\ReviewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use NFQ\UserBundle\Entity\User;

/**
 * Thanks
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Thanks
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
     * @var integer
     *
     * @ORM\Column(name="number", type="integer")
     */
    private $number=0;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="NFQ\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="helper_id", referencedColumnName="id")
     */
    private $helper;


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
     * Set number
     *
     * @param integer $number
     * @return Thanks
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    public function  incrementNumber(){
        $this->number++;
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
