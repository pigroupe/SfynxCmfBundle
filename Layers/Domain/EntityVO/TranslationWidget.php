<?php
declare(strict_types = 1);

namespace SfynxCmfContext\Domain\Entity;

// Import for annotations.
//use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
// Import from Sfynx\DddBundle
use Doctrine\ODM\CouchDB\Mapping\Annotations as CouchDB;
use Doctrine\ORM\Mapping as ORM;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\EntityInterface;
use SfynxCmfContext\Domain\ValueObject\TranslationWidgetVO;

/**
 * Class TranslationWidget
 *
 * @category SfynxCmfContext
 * @package Domain
 * @subpackage Entity
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="Pi_widget_translation")
 *
 * ODM\Document(collection="Pi_widget_translation")
 * ODM\HasLifecycleCallbacks
 *
 * @CouchDB\Document
 *
 * @copyright Copyright (c) 2016-2017, Aareon Group
 * @license http://www.pigroupe.com under a proprietary license
 * @version 1.1.1
 */
class TranslationWidget implements EntityInterface
{
    /**
     * @var integer|string Unique identifier of the TranslationWidget.
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
     * @var Widget
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @JMS\Serializer\Annotation\Exclude
     * @ORM\ManyToOne(targetEntity="Widget")
     * @ORM\JoinColumn(name="widget_id", referencedColumnName="id")
     */
    protected $widget_id;

    /**
     * @var TranslationWidgetVO
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Embedded(class="SfynxCmfContext\Domain\ValueObject\TranslationWidgetVO", columnPrefix=false)
     * ODM\EmbedOne(targetDocument="SfynxCmfContext\Domain\ValueObject\TranslationWidgetVO")
     * @CouchDB\EmbedOne(targetDocument="SfynxCmfContext\Domain\ValueObject\TranslationWidgetVO")
     */
    protected $translationWidget;

    /**
     * Builds the entity based on the value object associated.
     *
     * @param Widget $widget_id
     * @param TranslationWidgetVO $translationWidget
     * @return TranslationWidget
     */
    public static function build(Widget $widget_id, TranslationWidgetVO $translationWidget): TranslationWidget
    {
        return new self($widget_id, $translationWidget);
    }

    /**
     * TranslationWidget constructor.
     *
     * @param Widget $widget_id
     * @param TranslationWidgetVO $translationWidget
     */
    protected function __construct(Widget $widget_id, TranslationWidgetVO $translationWidget)
    {
        $this->setWidget_id($widget_id);
        $this->setTranslationWidget($translationWidget);
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
     * @return TranslationWidget
     */
    public function setId($id): TranslationWidget
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Returns the widget_id.
     *
     * @return Widget
     */
    public function getWidget_id(): Widget
    {
        return $this->widget_id;
    }

    /**
     * Sets the widget_id.
     *
     * @param Widget $widget_id
     * @return TranslationWidget
     */
    public function setWidget_id(Widget $widget_id): TranslationWidget
    {
        $this->widget_id = $widget_id;
        return $this;
    }

    /**
     * Gets the widget id.
     *
     * @JMS\Serializer\Annotation\VirtualProperty
     * @JMS\Serializer\Annotation\SerializedName("widget_idId")
     * @return mixed
     */
    public function getWidget_idId()
    {
        return $this->widget_id->getId();
    }

    /**
     * Returns the translationWidget.
     *
     * @return TranslationWidgetVO
     */
    public function getTranslationWidget(): TranslationWidgetVO
    {
        return $this->translationWidget;
    }

    /**
     * Sets the translationWidget.
     *
     * @param TranslationWidgetVO $translationWidget
     * @return TranslationWidget
     */
    public function setTranslationWidget(TranslationWidgetVO $translationWidget): TranslationWidget
    {
        $this->translationWidget = $translationWidget;
        return $this;
    }
}
