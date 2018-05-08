<?php
declare(strict_types = 1);

namespace SfynxCmfContext\Domain\Entity;

// Import for annotations.
//use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
// Import from Sfynx\DddBundle
use Doctrine\ODM\CouchDB\Mapping\Annotations as CouchDB;
use Doctrine\ORM\Mapping as ORM;

use SfynxCmfContext\Domain\ValueObject\CommentVO;

/**
 * Class Comment
 *
 * @category SfynxCmfContext
 * @package Domain
 * @subpackage Entity
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="Pi_comment")
 *
 * ODM\Document(collection="Pi_comment")
 * ODM\HasLifecycleCallbacks
 *
 * @CouchDB\Document
 *
 * @copyright Copyright (c) 2016-2017, Aareon Group
 * @license http://www.pigroupe.com under a proprietary license
 * @version 1.1.1
 */
class Comment  
{
    /**
     * @var integer|string Unique identifier of the Comment.
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
     * @var CommentVO
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Embedded(class="SfynxCmfContext\Domain\ValueObject\CommentVO", columnPrefix=false)
     * ODM\EmbedOne(targetDocument="SfynxCmfContext\Domain\ValueObject\CommentVO")
     * @CouchDB\EmbedOne(targetDocument="SfynxCmfContext\Domain\ValueObject\CommentVO")
     */
    protected $comment;

    /**
     * Builds the entity based on the value object associated.
     *
     * @param TranslationPage $pagetrans_id
     * @param CommentVO $comment
     * @return Comment
     */
    public static function build(TranslationPage $pagetrans_id, CommentVO $comment): Comment
    {
        return new self($pagetrans_id, $comment);
    }

    /**
     * Comment constructor.
     *
     * @param TranslationPage $pagetrans_id
     * @param CommentVO $comment
     */
    protected function __construct(TranslationPage $pagetrans_id, CommentVO $comment)
    {
        $this->setPagetrans_id($pagetrans_id);
        $this->setComment($comment);
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
     * @return Comment
     */
    public function setId($id): Comment
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
     * @return Comment
     */
    public function setPagetrans_id(TranslationPage $pagetrans_id): Comment
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
     * Returns the comment.
     *
     * @return CommentVO
     */
    public function getComment(): CommentVO
    {
        return $this->comment;
    }

    /**
     * Sets the comment.
     *
     * @param CommentVO $comment
     * @return Comment
     */
    public function setComment(CommentVO $comment): Comment
    {
        $this->comment = $comment;
        return $this;
    }
}
