<?php
declare(strict_types = 1);

namespace SfynxCmfContext\Domain\Entity;

// Import for annotations.
//use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
// Import from Sfynx\DddBundle
use Doctrine\ODM\CouchDB\Mapping\Annotations as CouchDB;
use Doctrine\ORM\Mapping as ORM;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\EntityInterface;
use SfynxCmfContext\Domain\ValueObject\TranslationPageVO;

/**
 * Class TranslationPage
 *
 * @category SfynxCmfContext
 * @package Domain
 * @subpackage Entity
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="Pi_page_translation")
 *
 * ODM\Document(collection="Pi_page_translation")
 * ODM\HasLifecycleCallbacks
 *
 * @CouchDB\Document
 *
 * @copyright Copyright (c) 2016-2017, Aareon Group
 * @license http://www.pigroupe.com under a proprietary license
 * @version 1.1.1
 */
class TranslationPage implements EntityInterface
{
    /**
     * @var integer|string Unique identifier of the TranslationPage.
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
     * @var TranslationPageVO
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Embedded(class="SfynxCmfContext\Domain\ValueObject\TranslationPageVO", columnPrefix=false)
     * ODM\EmbedOne(targetDocument="SfynxCmfContext\Domain\ValueObject\TranslationPageVO")
     * @CouchDB\EmbedOne(targetDocument="SfynxCmfContext\Domain\ValueObject\TranslationPageVO")
     */
    protected $translationPage;

    /**
     * Builds the entity based on the value object associated.
     *
     * @param Page $page_id
     * @param TranslationPageVO $translationPage
     * @return TranslationPage
     */
    public static function build(Page $page_id, TranslationPageVO $translationPage): TranslationPage
    {
        return new self($page_id, $translationPage);
    }

    /**
     * TranslationPage constructor.
     *
     * @param Page $page_id
     * @param TranslationPageVO $translationPage
     */
    protected function __construct(Page $page_id, TranslationPageVO $translationPage)
    {
        $this->setPage_id($page_id);
        $this->setTranslationPage($translationPage);
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
     * @return TranslationPage
     */
    public function setId($id): TranslationPage
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
     * @return TranslationPage
     */
    public function setPage_id(Page $page_id): TranslationPage
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
     * Returns the translationPage.
     *
     * @return TranslationPageVO
     */
    public function getTranslationPage(): TranslationPageVO
    {
        return $this->translationPage;
    }

    /**
     * Sets the translationPage.
     *
     * @param TranslationPageVO $translationPage
     * @return TranslationPage
     */
    public function setTranslationPage(TranslationPageVO $translationPage): TranslationPage
    {
        $this->translationPage = $translationPage;
        return $this;
    }
}
