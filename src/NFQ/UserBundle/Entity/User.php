<?php

namespace NFQ\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * User
 *
 * @ORM\Table(name="web_user")
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=60)
     */
    protected $first_name = "";
    /**
     * @ORM\Column(type="string", length=60)
     */
    protected $last_name = "";
    /**
     * @ORM\Column(type="string", length=30)
     */
    protected $phone = "";
    /**
     * @ORM\Column(type="date")
     */
    protected $birthday;
    /**
     * @ORM\Column(type="text")
     */
    protected $description = "";

    /**
     * @var integer
     * @ORM\Column(name="thumb_ups", type="integer")
     */
    protected $thumbUps= 0;

    /**
     * @var integer
     * @ORM\Column(name="thumb_downs", type="integer")
     */
    protected $thumbDowns = 0;

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set birthday
     *
     * @param string $birthday
     *
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return string
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return User
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function setEmail($email)
    {
        $email = is_null($email) ? '' : $email;
        parent::setEmail($email);
        $this->setUsername($email);

        return $this;
    }

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * @return int
     */
    public function getThumbUps()
    {
        return $this->thumbUps;
    }

    /**
     * @param int $thumbUps
     */
    public function setThumbUps($thumbUps)
    {
        $this->thumbUps = $thumbUps;
    }

    /**
     * @return int
     */
    public function getThumbDowns()
    {
        return $this->thumbDowns;
    }

    /**
     * @param int $thumbDowns
     */
    public function setThumbDowns($thumbDowns)
    {
        $this->thumbDowns = $thumbDowns;
    }

    public function incrementThumbUps(){
        $this->thumbUps++;
    }

    public function incrementThumbDowns(){
        $this->thumbDowns++;
    }
}
