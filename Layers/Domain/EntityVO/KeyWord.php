<?php
declare(strict_types = 1);

namespace SfynxCmfContext\Domain\Entity;

// Import for annotations.
//use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
// Import from Sfynx\DddBundle
use Doctrine\ODM\CouchDB\Mapping\Annotations as CouchDB;
use Doctrine\ORM\Mapping as ORM;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\EntityInterface;
use SfynxCmfContext\Domain\ValueObject\KeyWordVO;

/**
 * Class KeyWord
 *
 * @category SfynxCmfContext
 * @package Domain
 * @subpackage Entity
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="Pi_keyword")
 *
 * ODM\Document(collection="Pi_keyword")
 * ODM\HasLifecycleCallbacks
 *
 * @CouchDB\Document
 *
 * @copyright Copyright (c) 2016-2017, Aareon Group
 * @license http://www.pigroupe.com under a proprietary license
 * @version 1.1.1
 */
class KeyWord implements EntityInterface
{
    /**
     * @var integer|string Unique identifier of the KeyWord.
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
     * @var KeyWordVO
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Embedded(class="SfynxCmfContext\Domain\ValueObject\KeyWordVO", columnPrefix=false)
     * ODM\EmbedOne(targetDocument="SfynxCmfContext\Domain\ValueObject\KeyWordVO")
     * @CouchDB\EmbedOne(targetDocument="SfynxCmfContext\Domain\ValueObject\KeyWordVO")
     */
    protected $keyWord;

    /**
     * Builds the entity based on the value object associated.
     *
     * @param KeyWordVO $keyWord
     * @return KeyWord
     */
    public static function build(KeyWordVO $keyWord): KeyWord
    {
        return new self($keyWord);
    }

    /**
     * KeyWord constructor.
     *
     * @param KeyWordVO $keyWord
     */
    protected function __construct(KeyWordVO $keyWord)
    {
        $this->setKeyWord($keyWord);
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
     * @return KeyWord
     */
    public function setId($id): KeyWord
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Returns the keyWord.
     *
     * @return KeyWordVO
     */
    public function getKeyWord(): KeyWordVO
    {
        return $this->keyWord;
    }

    /**
     * Sets the keyWord.
     *
     * @param KeyWordVO $keyWord
     * @return KeyWord
     */
    public function setKeyWord(KeyWordVO $keyWord): KeyWord
    {
        $this->keyWord = $keyWord;
        return $this;
    }
}
