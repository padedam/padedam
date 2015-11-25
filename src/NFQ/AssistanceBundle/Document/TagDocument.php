<?php
namespace NFQ\AssistanceBundle\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;
use ONGR\ElasticsearchBundle\Document\AbstractDocument;

/**
 * @ES\Document
 */
class TagDocument extends AbstractDocument
{
    /**
     * @var string
     *
     * @ES\Property(name="name", type="string")
     */
    private $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}

