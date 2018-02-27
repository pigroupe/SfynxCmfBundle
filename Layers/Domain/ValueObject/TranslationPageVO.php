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
 * Class TranslationPageVO
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
final class TranslationPageVO extends AbstractVO
{
    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="lang_status", type="string")
     * ODM\Field(name="lang_status", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $langStatus;

    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="status", type="string")
     * ODM\Field(name="status", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $status;

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
     * @var bool
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="is_indexable", type="boolean")
     * ODM\Field(name="is_indexable", type="boolean")
     * @CouchDB\Field(type="boolean")
     */
    protected $isIndexable;

    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="breadcrumb", type="string")
     * ODM\Field(name="breadcrumb", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $breadcrumb;

    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="slug", type="string")
     * ODM\Field(name="slug", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $slug;

    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="meta_title", type="string")
     * ODM\Field(name="meta_title", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $metaTitle;

    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="meta_keywords", type="string")
     * ODM\Field(name="meta_keywords", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $metaKeywords;

    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="meta_description", type="string")
     * ODM\Field(name="meta_description", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $metaDescription;

    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="surtitre", type="string")
     * ODM\Field(name="surtitre", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $surtitre;

    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="titre", type="string")
     * ODM\Field(name="titre", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $titre;

    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="soustitre", type="string")
     * ODM\Field(name="soustitre", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $soustitre;

    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="descriptif", type="string")
     * ODM\Field(name="descriptif", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $descriptif;

    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="chapo", type="string")
     * ODM\Field(name="chapo", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $chapo;

    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="texte", type="string")
     * ODM\Field(name="texte", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $texte;

    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="ps", type="string")
     * ODM\Field(name="ps", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $ps;

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
     * @ORM\Column(name="lang_code", type="integer")
     * ODM\Field(name="lang_code", type="integer")
     * @CouchDB\Field(type="integer")
     */
    protected $langCode;

    /**
     * Returns the lang_status.
     *
     * @return string
     */
    public function getLangStatus(): string
    {
        return $this->langStatus;
    }

    /**
     * Returns the status.
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
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
     * Returns the is_indexable.
     *
     * @return bool
     */
    public function getIsIndexable(): bool
    {
        return $this->isIndexable;
    }

    /**
     * Returns the breadcrumb.
     *
     * @return string
     */
    public function getBreadcrumb(): string
    {
        return $this->breadcrumb;
    }

    /**
     * Returns the slug.
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Returns the meta_title.
     *
     * @return string
     */
    public function getMetaTitle(): string
    {
        return $this->metaTitle;
    }

    /**
     * Returns the meta_keywords.
     *
     * @return string
     */
    public function getMetaKeywords(): string
    {
        return $this->metaKeywords;
    }

    /**
     * Returns the meta_description.
     *
     * @return string
     */
    public function getMetaDescription(): string
    {
        return $this->metaDescription;
    }

    /**
     * Returns the surtitre.
     *
     * @return string
     */
    public function getSurtitre(): string
    {
        return $this->surtitre;
    }

    /**
     * Returns the titre.
     *
     * @return string
     */
    public function getTitre(): string
    {
        return $this->titre;
    }

    /**
     * Returns the soustitre.
     *
     * @return string
     */
    public function getSoustitre(): string
    {
        return $this->soustitre;
    }

    /**
     * Returns the descriptif.
     *
     * @return string
     */
    public function getDescriptif(): string
    {
        return $this->descriptif;
    }

    /**
     * Returns the chapo.
     *
     * @return string
     */
    public function getChapo(): string
    {
        return $this->chapo;
    }

    /**
     * Returns the texte.
     *
     * @return string
     */
    public function getTexte(): string
    {
        return $this->texte;
    }

    /**
     * Returns the ps.
     *
     * @return string
     */
    public function getPs(): string
    {
        return $this->ps;
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
     * Returns the lang_code.
     *
     * @return int
     */
    public function getLangCode(): int
    {
        return $this->langCode;
    }
}
