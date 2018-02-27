<?php
declare(strict_types = 1);

namespace SfynxCmfContext\Domain\Entity;

// Import for annotations.
//use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
// Import from Sfynx\DddBundle
use Doctrine\ODM\CouchDB\Mapping\Annotations as CouchDB;
use Doctrine\ORM\Mapping as ORM;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\EntityInterface;
use SfynxCmfContext\Domain\ValueObject\HistoricalStatusVO;

/**
 * Class HistoricalStatus
 *
 * @category SfynxCmfContext
 * @package Domain
 * @subpackage Entity
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="Pi_page_historical_status")
 *
 * ODM\Document(collection="Pi_page_historical_status")
 * ODM\HasLifecycleCallbacks
 *
 * @CouchDB\Document
 *
 * @copyright Copyright (c) 2016-2017, Aareon Group
 * @license http://www.pigroupe.com under a proprietary license
 * @version 1.1.1
 */
class HistoricalStatus implements EntityInterface
{
    /**
     * @var integer|string Unique identifier of the HistoricalStatus.
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
     * @var TranslationPage
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @JMS\Serializer\Annotation\Exclude
     * @ORM\ManyToOne(targetEntity="TranslationPage")
     * @ORM\JoinColumn(name="pagetrans_id", referencedColumnName="id")
     */
    protected $pagetrans_id;

    /**
     * @var HistoricalStatusVO
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Embedded(class="SfynxCmfContext\Domain\ValueObject\HistoricalStatusVO", columnPrefix=false)
     * ODM\EmbedOne(targetDocument="SfynxCmfContext\Domain\ValueObject\HistoricalStatusVO")
     * @CouchDB\EmbedOne(targetDocument="SfynxCmfContext\Domain\ValueObject\HistoricalStatusVO")
     */
    protected $historicalStatus;

    /**
     * Builds the entity based on the value object associated.
     *
     * @param TranslationPage $pagetrans_id
     * @param HistoricalStatusVO $historicalStatus
     * @return HistoricalStatus
     */
    public static function build(TranslationPage $pagetrans_id, HistoricalStatusVO $historicalStatus): HistoricalStatus
    {
        return new self($pagetrans_id, $historicalStatus);
    }

    /**
     * HistoricalStatus constructor.
     *
     * @param TranslationPage $pagetrans_id
     * @param HistoricalStatusVO $historicalStatus
     */
    protected function __construct(TranslationPage $pagetrans_id, HistoricalStatusVO $historicalStatus)
    {
        $this->setPagetrans_id($pagetrans_id);
        $this->setHistoricalStatus($historicalStatus);
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
     * @return HistoricalStatus
     */
    public function setId($id): HistoricalStatus
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Returns the pagetrans_id.
     *
     * @return TranslationPage
     */
    public function getPagetrans_id(): TranslationPage
    {
        return $this->pagetrans_id;
    }

    /**
     * Sets the pagetrans_id.
     *
     * @param TranslationPage $pagetrans_id
     * @return HistoricalStatus
     */
    public function setPagetrans_id(TranslationPage $pagetrans_id): HistoricalStatus
    {
        $this->pagetrans_id = $pagetrans_id;
        return $this;
    }

    /**
     * Gets the translationPage id.
     *
     * @JMS\Serializer\Annotation\VirtualProperty
     * @JMS\Serializer\Annotation\SerializedName("pagetrans_idId")
     * @return mixed
     */
    public function getPagetrans_idId()
    {
        return $this->pagetrans_id->getId();
    }

    /**
     * Returns the historicalStatus.
     *
     * @return HistoricalStatusVO
     */
    public function getHistoricalStatus(): HistoricalStatusVO
    {
        return $this->historicalStatus;
    }

    /**
     * Sets the historicalStatus.
     *
     * @param HistoricalStatusVO $historicalStatus
     * @return HistoricalStatus
     */
    public function setHistoricalStatus(HistoricalStatusVO $historicalStatus): HistoricalStatus
    {
        $this->historicalStatus = $historicalStatus;
        return $this;
    }
}
