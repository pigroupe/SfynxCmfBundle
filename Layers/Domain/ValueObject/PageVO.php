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
 * Class PageVO
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
final class PageVO extends AbstractVO
{
    /**
     * @var bool
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="is_cacheable", type="boolean")
     * ODM\Field(name="is_cacheable", type="boolean")
     * @CouchDB\Field(type="boolean")
     */
    protected $isCacheable;

    /**
     * @var bool
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="is_public", type="boolean")
     * ODM\Field(name="is_public", type="boolean")
     * @CouchDB\Field(type="boolean")
     */
    protected $isPublic;

    /**
     * @var int
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="lifetime", type="integer")
     * ODM\Field(name="lifetime", type="integer")
     * @CouchDB\Field(type="integer")
     */
    protected $lifetime;

    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="route_name", type="string")
     * ODM\Field(name="route_name", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $routeName;

    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="url", type="string")
     * ODM\Field(name="url", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $url;

    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="meta_content_type", type="string")
     * ODM\Field(name="meta_content_type", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $metaContentType;

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
     * @var int
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="layout_id", type="integer")
     * ODM\Field(name="layout_id", type="integer")
     * @CouchDB\Field(type="integer")
     */
    protected $layout;

    /**
     * @var int
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="user_id", type="integer")
     * ODM\Field(name="user_id", type="integer")
     * @CouchDB\Field(type="integer")
     */
    protected $user;

    /**
     * Returns the is_cacheable.
     *
     * @return bool
     */
    public function getIsCacheable(): bool
    {
        return $this->isCacheable;
    }

    /**
     * Returns the is_public.
     *
     * @return bool
     */
    public function getIsPublic(): bool
    {
        return $this->isPublic;
    }

    /**
     * Returns the lifetime.
     *
     * @return int
     */
    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    /**
     * Returns the route_name.
     *
     * @return string
     */
    public function getRouteName(): string
    {
        return $this->routeName;
    }

    /**
     * Returns the url.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Returns the meta_content_type.
     *
     * @return string
     */
    public function getMetaContentType(): string
    {
        return $this->metaContentType;
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

    /**
     * Returns the layout_id.
     *
     * @return int
     */
    public function getLayout(): int
    {
        return $this->layout;
    }

    /**
     * Returns the user_id.
     *
     * @return int
     */
    public function getUser(): int
    {
        return $this->user;
    }
}
