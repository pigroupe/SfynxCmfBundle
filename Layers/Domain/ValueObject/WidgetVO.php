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
 * Class WidgetVO
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
final class WidgetVO extends AbstractVO
{
    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="plugin", type="string")
     * ODM\Field(name="plugin", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $plugin;

    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="action", type="string")
     * ODM\Field(name="action", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $action;

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
     * @var int
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="is_templating_cache", type="integer")
     * ODM\Field(name="is_templating_cache", type="integer")
     * @CouchDB\Field(type="integer")
     */
    protected $isTemplatingCache;

    /**
     * @var int
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="is_ajax", type="integer")
     * ODM\Field(name="is_ajax", type="integer")
     * @CouchDB\Field(type="integer")
     */
    protected $isAjax;

    /**
     * @var int
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="is_sluggify", type="integer")
     * ODM\Field(name="is_sluggify", type="integer")
     * @CouchDB\Field(type="integer")
     */
    protected $isSluggify;

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
     * @var bool
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="is_secure", type="boolean")
     * ODM\Field(name="is_secure", type="boolean")
     * @CouchDB\Field(type="boolean")
     */
    protected $isSecure;

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
     * Returns the plugin.
     *
     * @return string
     */
    public function getPlugin(): string
    {
        return $this->plugin;
    }

    /**
     * Returns the action.
     *
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

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
     * Returns the is_templating_cache.
     *
     * @return int
     */
    public function getIsTemplatingCache(): int
    {
        return $this->isTemplatingCache;
    }

    /**
     * Returns the is_ajax.
     *
     * @return int
     */
    public function getIsAjax(): int
    {
        return $this->isAjax;
    }

    /**
     * Returns the is_sluggify.
     *
     * @return int
     */
    public function getIsSluggify(): int
    {
        return $this->isSluggify;
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
     * Returns the is_secure.
     *
     * @return bool
     */
    public function getIsSecure(): bool
    {
        return $this->isSecure;
    }

    /**
     * Returns the secure_roles.
     *
     * @return array
     */
    public function getSecureRoles(): array
    {
        return $this->secureRoles;
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
