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
 * Class CommentVO
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
final class CommentVO extends AbstractVO
{
    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="user", type="string")
     * ODM\Field(name="user", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $user;

    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="comment", type="string")
     * ODM\Field(name="comment", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $comment;

    /**
     * @var string
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="email", type="string")
     * ODM\Field(name="email", type="string")
     * @CouchDB\Field(type="string")
     */
    protected $email;

    /**
     * @var bool
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(name="is_approved", type="boolean")
     * ODM\Field(name="is_approved", type="boolean")
     * @CouchDB\Field(type="boolean")
     */
    protected $isApproved;

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
     * Returns the user.
     *
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * Returns the comment.
     *
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * Returns the email.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Returns the is_approved.
     *
     * @return bool
     */
    public function getIsApproved(): bool
    {
        return $this->isApproved;
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
