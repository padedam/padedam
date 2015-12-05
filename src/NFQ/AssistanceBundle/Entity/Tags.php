<?php

namespace NFQ\AssistanceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Tags
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="tags")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="NFQ\AssistanceBundle\Repository\TagsRepository")
 */
class Tags
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $title;


    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Tags", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Tags", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_enabled", type="boolean")
     */
    private $isEnabled = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="added_at", type="datetime")
     */
    private $addedAt;

    /** @ORM\OneToMany(targetEntity="Tag2User", mappedBy="tag") */
    protected $usersWithTag;


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
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Tags
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set lft
     *
     * @param integer $lft
     * @return Tags
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer 
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     * @return Tags
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer 
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     * @return Tags
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer 
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set root
     *
     * @param integer $root
     * @return Tags
     */
    public function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root
     *
     * @return integer 
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set isEnabled
     *
     * @param boolean $isEnabled
     * @return Tags
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * Get isEnabled
     *
     * @return boolean 
     */
    public function getIsEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Tags
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set addedAt
     *
     * @param \DateTime $addedAt
     * @return Tags
     */
    public function setAddedAt($addedAt)
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    /**
     * Get addedAt
     *
     * @return \DateTime 
     */
    public function getAddedAt()
    {
        return $this->addedAt;
    }

    /**
     * Set parent
     *
     * @param \NFQ\AssistanceBundle\Entity\Tags $parent
     * @return Tags
     */
    public function setParent(\NFQ\AssistanceBundle\Entity\Tags $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \NFQ\AssistanceBundle\Entity\Tags
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \NFQ\AssistanceBundle\Entity\Tags $children
     * @return Tags
     */
    public function addChild(\NFQ\AssistanceBundle\Entity\Tags $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \NFQ\AssistanceBundle\Entity\Tags $children
     */
    public function removeChild(\NFQ\AssistanceBundle\Entity\Tags $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getAddedAt() == null)
        {
            $this->setAddedAt(new \DateTime(date('Y-m-d H:i:s')));
        }
    }

    /**
     * Add usersWithTag
     *
     * @param \NFQ\AssistanceBundle\Entity\Tag2User $usersWithTag
     * @return Tags
     */
    public function addUsersWithTag(\NFQ\AssistanceBundle\Entity\Tag2User $usersWithTag)
    {
        $this->usersWithTag[] = $usersWithTag;

        return $this;
    }

    /**
     * Remove usersWithTag
     *
     * @param \NFQ\AssistanceBundle\Entity\Tag2User $usersWithTag
     */
    public function removeUsersWithTag(\NFQ\AssistanceBundle\Entity\Tag2User $usersWithTag)
    {
        $this->usersWithTag->removeElement($usersWithTag);
    }

    /**
     * Get usersWithTag
     *
     * @return ArrayCollection
     */
    public function getUsersWithTag()
    {
        return $this->usersWithTag;
    }

    public function __toString()
    {
        return $this->title;
    }
}
