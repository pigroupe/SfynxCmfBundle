<?php
declare(strict_types = 1);

namespace SfynxCmfContext\Domain\Entity;

// Import for annotations.
//use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
// Import from Sfynx\DddBundle
use Doctrine\ODM\CouchDB\Mapping\Annotations as CouchDB;
use Doctrine\ORM\Mapping as ORM;

use SfynxCmfContext\Domain\ValueObject\BlockVO;

/**
 * Class Block
 *
 * @category SfynxCmfContext
 * @package Domain
 * @subpackage Entity
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="Pi_block")
 *
 * ODM\Document(collection="Pi_block")
 * ODM\HasLifecycleCallbacks
 *
 * @CouchDB\Document
 *
 * @copyright Copyright (c) 2016-2017, Aareon Group
 * @license http://www.pigroupe.com under a proprietary license
 * @version 1.1.1
 */
class Block  
{
    /**
     * @var integer|string Unique identifier of the Block.
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
     * @var Page
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @JMS\Serializer\Annotation\Exclude
     * @ORM\ManyToOne(targetEntity="Page")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    protected $page_id;

    /**
     * @var BlockVO
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Embedded(class="SfynxCmfContext\Domain\ValueObject\BlockVO", columnPrefix=false)
     * ODM\EmbedOne(targetDocument="SfynxCmfContext\Domain\ValueObject\BlockVO")
     * @CouchDB\EmbedOne(targetDocument="SfynxCmfContext\Domain\ValueObject\BlockVO")
     */
    protected $block;

    /**
     * Builds the entity based on the value object associated.
     *
     * @param Page $page_id
     * @param BlockVO $block
     * @return Block
     */
    public static function build(Page $page_id, BlockVO $block): Block
    {
        return new self($page_id, $block);
    }

    /**
     * Block constructor.
     *
     * @param Page $page_id
     * @param BlockVO $block
     */
    protected function __construct(Page $page_id, BlockVO $block)
    {
        $this->setPage_id($page_id);
        $this->setBlock($block);
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
     * @return Block
     */
    public function setId($id): Block
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Returns the page_id.
     *
     * @return Page
     */
    public function getPage_id(): Page
    {
        return $this->page_id;
    }

    /**
     * Sets the page_id.
     *
     * @param Page $page_id
     * @return Block
     */
    public function setPage_id(Page $page_id): Block
    {
        $this->page_id = $page_id;
        return $this;
    }

    /**
     * Gets the page id.
     *
     * @JMS\Serializer\Annotation\VirtualProperty
     * @JMS\Serializer\Annotation\SerializedName("page_idId")
     * @return mixed
     */
    public function getPage_idId()
    {
        return $this->page_id->getId();
    }

    /**
     * Returns the block.
     *
     * @return BlockVO
     */
    public function getBlock(): BlockVO
    {
        return $this->block;
    }

    /**
     * Sets the block.
     *
     * @param BlockVO $block
     * @return Block
     */
    public function setBlock(BlockVO $block): Block
    {
        $this->block = $block;
        return $this;
    }
}
