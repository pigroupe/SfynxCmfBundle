<?php
declare(strict_types = 1);

namespace SfynxCmfContext\Domain\Entity;

// Import for annotations.
//use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
// Import from Sfynx\DddBundle
use Doctrine\ODM\CouchDB\Mapping\Annotations as CouchDB;
use Doctrine\ORM\Mapping as ORM;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\EntityInterface;
use SfynxCmfContext\Domain\ValueObject\PageVO;

/**
 * Class Page
 *
 * @category SfynxCmfContext
 * @package Domain
 * @subpackage Entity
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="Pi_page")
 *
 * ODM\Document(collection="Pi_page")
 * ODM\HasLifecycleCallbacks
 *
 * @CouchDB\Document
 *
 * @copyright Copyright (c) 2016-2017, Aareon Group
 * @license http://www.pigroupe.com under a proprietary license
 * @version 1.1.1
 */
class Page implements EntityInterface
{
    /**
     * @var integer|string Unique identifier of the Page.
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
     * @var Rubrique
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @JMS\Serializer\Annotation\Exclude
     * @ORM\ManyToOne(targetEntity="Rubrique")
     * @ORM\JoinColumn(name="rubrique_id", referencedColumnName="id")
     */
    protected $rubrique_id;

    /**
     * @var PageVO
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Embedded(class="SfynxCmfContext\Domain\ValueObject\PageVO", columnPrefix=false)
     * ODM\EmbedOne(targetDocument="SfynxCmfContext\Domain\ValueObject\PageVO")
     * @CouchDB\EmbedOne(targetDocument="SfynxCmfContext\Domain\ValueObject\PageVO")
     */
    protected $page;

    /**
     * Builds the entity based on the value object associated.
     *
     * @param Rubrique $rubrique_id
     * @param PageVO $page
     * @return Page
     */
    public static function build(Rubrique $rubrique_id, PageVO $page): Page
    {
        return new self($rubrique_id, $page);
    }

    /**
     * Page constructor.
     *
     * @param Rubrique $rubrique_id
     * @param PageVO $page
     */
    protected function __construct(Rubrique $rubrique_id, PageVO $page)
    {
        $this->setRubrique_id($rubrique_id);
        $this->setPage($page);
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
     * @return Page
     */
    public function setId($id): Page
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Returns the rubrique_id.
     *
     * @return Rubrique
     */
    public function getRubrique_id(): Rubrique
    {
        return $this->rubrique_id;
    }

    /**
     * Sets the rubrique_id.
     *
     * @param Rubrique $rubrique_id
     * @return Page
     */
    public function setRubrique_id(Rubrique $rubrique_id): Page
    {
        $this->rubrique_id = $rubrique_id;
        return $this;
    }

    /**
     * Gets the rubrique id.
     *
     * @JMS\Serializer\Annotation\VirtualProperty
     * @JMS\Serializer\Annotation\SerializedName("rubrique_idId")
     * @return mixed
     */
    public function getRubrique_idId()
    {
        return $this->rubrique_id->getId();
    }

    /**
     * Returns the page.
     *
     * @return PageVO
     */
    public function getPage(): PageVO
    {
        return $this->page;
    }

    /**
     * Sets the page.
     *
     * @param PageVO $page
     * @return Page
     */
    public function setPage(PageVO $page): Page
    {
        $this->page = $page;
        return $this;
    }
}
