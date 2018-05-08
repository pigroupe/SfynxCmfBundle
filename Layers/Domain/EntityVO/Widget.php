<?php
declare(strict_types = 1);

namespace SfynxCmfContext\Domain\Entity;

// Import for annotations.
//use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
// Import from Sfynx\DddBundle
use Doctrine\ODM\CouchDB\Mapping\Annotations as CouchDB;
use Doctrine\ORM\Mapping as ORM;

use SfynxCmfContext\Domain\ValueObject\WidgetVO;

/**
 * Class Widget
 *
 * @category SfynxCmfContext
 * @package Domain
 * @subpackage Entity
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="Pi_widget")
 *
 * ODM\Document(collection="Pi_widget")
 * ODM\HasLifecycleCallbacks
 *
 * @CouchDB\Document
 *
 * @copyright Copyright (c) 2016-2017, Aareon Group
 * @license http://www.pigroupe.com under a proprietary license
 * @version 1.1.1
 */
class Widget  
{
    /**
     * @var integer|string Unique identifier of the Widget.
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
     * @var Block
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @JMS\Serializer\Annotation\Exclude
     * @ORM\ManyToOne(targetEntity="Block")
     * @ORM\JoinColumn(name="block_id", referencedColumnName="id")
     */
    protected $block_id;

    /**
     * @var WidgetVO
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Embedded(class="SfynxCmfContext\Domain\ValueObject\WidgetVO", columnPrefix=false)
     * ODM\EmbedOne(targetDocument="SfynxCmfContext\Domain\ValueObject\WidgetVO")
     * @CouchDB\EmbedOne(targetDocument="SfynxCmfContext\Domain\ValueObject\WidgetVO")
     */
    protected $widget;

    /**
     * Builds the entity based on the value object associated.
     *
     * @param Block $block_id
     * @param WidgetVO $widget
     * @return Widget
     */
    public static function build(Block $block_id, WidgetVO $widget): Widget
    {
        return new self($block_id, $widget);
    }

    /**
     * Widget constructor.
     *
     * @param Block $block_id
     * @param WidgetVO $widget
     */
    protected function __construct(Block $block_id, WidgetVO $widget)
    {
        $this->setBlock_id($block_id);
        $this->setWidget($widget);
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
     * @return Widget
     */
    public function setId($id): Widget
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Returns the block_id.
     *
     * @return Block
     */
    public function getBlock_id(): Block
    {
        return $this->block_id;
    }

    /**
     * Sets the block_id.
     *
     * @param Block $block_id
     * @return Widget
     */
    public function setBlock_id(Block $block_id): Widget
    {
        $this->block_id = $block_id;
        return $this;
    }

    /**
     * Gets the block id.
     *
     * @JMS\Serializer\Annotation\VirtualProperty
     * @JMS\Serializer\Annotation\SerializedName("block_idId")
     * @return mixed
     */
    public function getBlock_idId()
    {
        return $this->block_id->getId();
    }

    /**
     * Returns the widget.
     *
     * @return WidgetVO
     */
    public function getWidget(): WidgetVO
    {
        return $this->widget;
    }

    /**
     * Sets the widget.
     *
     * @param WidgetVO $widget
     * @return Widget
     */
    public function setWidget(WidgetVO $widget): Widget
    {
        $this->widget = $widget;
        return $this;
    }
}
