<?php

namespace NFQ\AssistanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AssistanceRequestForm
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class AssistanceRequest
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
     * @ORM\Column(name="assistanceField", type="integer")
     *
     * @Assert\NotBlank()
     */
    private $assistanceField;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set assistanceField
     *
     * @param integer $assistanceField
     * @return AssistanceRequest
     */
    public function setAssistanceField($assistanceField)
    {
        $this->assistanceField = $assistanceField;

        return $this;
    }

    /**
     * Get assistanceField
     *
     * @return string 
     */
    public function getAssistanceField()
    {
        return $this->assistanceField;
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
}
