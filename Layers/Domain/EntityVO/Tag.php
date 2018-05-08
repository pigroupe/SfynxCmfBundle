<?php
declare(strict_types = 1);

namespace SfynxCmfContext\Domain\Entity;

// Import for annotations.
//use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
// Import from Sfynx\DddBundle
use Doctrine\ODM\CouchDB\Mapping\Annotations as CouchDB;
use Doctrine\ORM\Mapping as ORM;

use SfynxCmfContext\Domain\ValueObject\TagVO;

/**
 * Class Tag
 *
 * @category SfynxCmfContext
 * @package Domain
 * @subpackage Entity
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="Pi_tag")
 *
 * ODM\Document(collection="Pi_tag")
 * ODM\HasLifecycleCallbacks
 *
 * @CouchDB\Document
 *
 * @copyright Copyright (c) 2016-2017, Aareon Group
 * @license http://www.pigroupe.com under a proprietary license
 * @version 1.1.1
 */
class Tag  
{
    /**
     * @var integer|string Unique identifier of the Tag.
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(type="integer", name="ID")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * ODM\Id(strategy="AUTO", type="string", name="ID")
     * @CouchDB\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @var TagVO
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Embedded(class="SfynxCmfContext\Domain\ValueObject\TagVO", columnPrefix=false)
     * ODM\EmbedOne(targetDocument="SfynxCmfContext\Domain\ValueObject\TagVO")
     * @CouchDB\EmbedOne(targetDocument="SfynxCmfContext\Domain\ValueObject\TagVO")
     */
    protected $tag;

    /**
     * Builds the entity based on the value object associated.
     *
     * @param TagVO $tag
     * @return Tag
     */
    public static function build(TagVO $tag): Tag
    {
        return new self($tag);
    }

    /**
     * Tag constructor.
     *
     * @param TagVO $tag
     */
    protected function __construct(TagVO $tag)
    {
        $this->setTag($tag);
    }

    /**
     * Returns the id.
     *
     * @return int|string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id.
     *
     * @param int|string $id
     * @return Tag
     */
    public function setId($id): Tag
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Returns the tag.
     *
     * @return TagVO
     */
    public function getTag(): TagVO
    {
        return $this->tag;
    }

    /**
     * Sets the tag.
     *
     * @param TagVO $tag
     * @return Tag
     */
    public function setTag(TagVO $tag): Tag
    {
        $this->tag = $tag;
        return $this;
    }
}
