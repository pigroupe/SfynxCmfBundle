<?php
declare(strict_types = 1);

namespace SfynxCmfContext\Domain\ValueObject;

// Import for annotations.
//use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
// Import from Sfynx\DddBundle
use DateTime;
use Doctrine\ODM\CouchDB\Mapping\Annotations as CouchDB;
use Doctrine\ORM\Mapping as ORM;
use Sfynx\CoreBundle\Layers\Domain\ValueObject\Generalisation\AbstractVO;

/**
 * Class BlockVO
 *
 * @category SfynxCmfContext
 * @package Domain
 * @subpackage ValueObject
 * @final
 *
 * @ORM\Embeddable
 * ODM\EmbeddedDocument
 * @CouchDB\EmbeddedDocument
 *
 * @copyright Copyright (c) 2016-2017, Aareon Group
 * @license http://www.pigroupe.com under a proprietary license
 * @version 1.1.1
 */
final class BlockVO extends AbstractVO
{
    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="name", type="string")
     * ODM\Field(name="name", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $name;

    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="config_css_class", type="string")
     * ODM\Field(name="config_css_class", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $configCssClass;

    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="config_xml", type="string")
     * ODM\Field(name="config_xml", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $configXml;

    /**
     * @var int
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="position", type="integer")
     * ODM\Field(name="position", type="integer")
     * @CouchDB\Field(type="integer")
     */
    protected $position;

    /**
     * @var DateTime
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @JMS\Serializer\Annotation\Type("DateTime<'Y-m-d H:i:s'>")
     * @ORM\Column(name="created_at", type="datetime")
     * ODM\Field(name="created_at", type="datetime")
     * @CouchDB\Field(type="datetime")
     */
    protected $createdAt;

    /**
     * @var DateTime
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @JMS\Serializer\Annotation\Type("DateTime<'Y-m-d H:i:s'>")
     * @ORM\Column(name="updated_at", type="datetime")
     * ODM\Field(name="updated_at", type="datetime")
     * @CouchDB\Field(type="datetime")
     */
    protected $updatedAt;

    /**
     * @var DateTime
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @JMS\Serializer\Annotation\Type("DateTime<'Y-m-d H:i:s'>")
     * @ORM\Column(name="published_at", type="datetime")
     * ODM\Field(name="published_at", type="datetime")
     * @CouchDB\Field(type="datetime")
     */
    protected $publishedAt;

    /**
     * @var DateTime
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @JMS\Serializer\Annotation\Type("DateTime<'Y-m-d H:i:s'>")
     * @ORM\Column(name="archive_at", type="datetime")
     * ODM\Field(name="archive_at", type="datetime")
     * @CouchDB\Field(type="datetime")
     */
    protected $archiveAt;

    /**
     * @var bool
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="archived", type="boolean")
     * ODM\Field(name="archived", type="boolean")
     * @CouchDB\Field(type="boolean")
     */
    protected $archived;

    /**
     * @var bool
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="enabled", type="boolean")
     * ODM\Field(name="enabled", type="boolean")
     * @CouchDB\Field(type="boolean")
     */
    protected $enabled;

    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the config_css_class.
     *
     * @return string
     */
    public function getConfigCssClass(): string
    {
        return $this->configCssClass;
    }

    /**
     * Returns the config_xml.
     *
     * @return string
     */
    public function getConfigXml(): string
    {
        return $this->configXml;
    }

    /**
     * Returns the position.
     *
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * Returns the created_at.
     *
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * Returns the updated_at.
     *
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Returns the published_at.
     *
     * @return DateTime
     */
    public function getPublishedAt(): DateTime
    {
        return $this->publishedAt;
    }

    /**
     * Returns the archive_at.
     *
     * @return DateTime
     */
    public function getArchiveAt(): DateTime
    {
        return $this->archiveAt;
    }

    /**
     * Returns the archived.
     *
     * @return bool
     */
    public function getArchived(): bool
    {
        return $this->archived;
    }

    /**
     * Returns the enabled.
     *
     * @return bool
     */
    public function getEnabled(): bool
    {
        return $this->enabled;
    }
}
