<?php

namespace NFQ\AssistanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tag2User
 *
 * @ORM\Table(name="tag2user")
 * @ORM\Entity
 */
class Tag2User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\ManyToOne(targetEntity="NFQ\UserBundle\Entity\User", inversedBy="taglist") */
    protected $user;

    /** @ORM\ManyToOne(targetEntity="Tags", inversedBy="usersWithTag") */
    protected $tag;

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
     * Set user
     *
     * @param \NFQ\UserBundle\Entity\User $user
     * @return Tag2User
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

    /**
     * Set tag
     *
     * @param \NFQ\AssistanceBundle\Entity\Tags $tag
     * @return Tag2User
     */
    public function setTag(\NFQ\AssistanceBundle\Entity\Tags $tag = null)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return \NFQ\AssistanceBundle\Entity\Tags
     */
    public function getTag()
    {
        return $this->tag;
    }
}
