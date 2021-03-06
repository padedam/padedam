<?php

namespace NFQ\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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


    /** @ORM\OneToMany(targetEntity="NFQ\AssistanceBundle\Entity\Tag2User", mappedBy="user") */
    protected $taglist;


    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="NFQ\ReviewsBundle\Entity\Review", mappedBy="helper")
     */
    private $gReviews;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="NFQ\ReviewsBundle\Entity\Review", mappedBy="helpGetter")
     */
    private $wReviews;

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
     * @return mixed
     */
    public function getGReviews()
    {
        return $this->gReviews;
    }

    /**
     * @param mixed $gReviews
     */
    public function setGReviews($gReviews)
    {
        $this->gReviews = $gReviews;
    }

    /**
     * @return mixed
     */
    public function getWReviews()
    {
        return $this->wReviews;
    }

    /**
     * @param mixed $wReviews
     */
    public function setWReviews($wReviews)
    {
        $this->wReviews = $wReviews;
    }

    /**
     * Add taglist
     *
     * @param \NFQ\UserBundle\Entity\Tag2User $taglist
     * @return User
     */
    public function addTaglist(/*\NFQ\UserBundle\Entity\Tag2User */
        $taglist)
    {
        $this->taglist[] = $taglist;

        return $this;
    }

    /**
     * Remove taglist
     *
     * @param \NFQ\UserBundle\Entity\Tag2User $taglist
     */
    public function removeTaglist(/*\NFQ\UserBundle\Entity\Tag2User */
        $taglist)
    {
        $this->taglist->removeElement($taglist);
    }

    /**
     * Get taglist
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTaglist()
    {
        return $this->taglist;
    }
}
