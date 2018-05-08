<?php
declare(strict_types = 1);

namespace SfynxCmfContext\Domain\Entity;

// Import for annotations.
//use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
// Import from Sfynx\DddBundle
use Doctrine\ODM\CouchDB\Mapping\Annotations as CouchDB;
use Doctrine\ORM\Mapping as ORM;

use SfynxCmfContext\Domain\ValueObject\RubriqueVO;

/**
 * Class Rubrique
 *
 * @category SfynxCmfContext
 * @package Domain
 * @subpackage Entity
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="Pi_rubrique")
 *
 * ODM\Document(collection="Pi_rubrique")
 * ODM\HasLifecycleCallbacks
 *
 * @CouchDB\Document
 *
 * @copyright Copyright (c) 2016-2017, Aareon Group
 * @license http://www.pigroupe.com under a proprietary license
 * @version 1.1.1
 */
class Rubrique  
{
    /**
     * @var integer|string Unique identifier of the Rubrique.
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
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent_id;

    /**
     * @var RubriqueVO
     *
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Embedded(class="SfynxCmfContext\Domain\ValueObject\RubriqueVO", columnPrefix=false)
     * ODM\EmbedOne(targetDocument="SfynxCmfContext\Domain\ValueObject\RubriqueVO")
     * @CouchDB\EmbedOne(targetDocument="SfynxCmfContext\Domain\ValueObject\RubriqueVO")
     */
    protected $rubrique;

    /**
     * Builds the entity based on the value object associated.
     *
     * @param Rubrique $parent_id
     * @param RubriqueVO $rubrique
     * @return Rubrique
     */
    public static function build(Rubrique $parent_id, RubriqueVO $rubrique): Rubrique
    {
        return new self($parent_id, $rubrique);
    }

    /**
     * Rubrique constructor.
     *
     * @param Rubrique $parent_id
     * @param RubriqueVO $rubrique
     */
    protected function __construct(Rubrique $parent_id, RubriqueVO $rubrique)
    {
        $this->setParent_id($parent_id);
        $this->setRubrique($rubrique);
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
     * @return Rubrique
     */
    public function setId($id): Rubrique
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Returns the parent_id.
     *
     * @return Rubrique
     */
    public function getParent_id(): Rubrique
    {
        return $this->parent_id;
    }

    /**
     * Sets the parent_id.
     *
     * @param Rubrique $parent_id
     * @return Rubrique
     */
    public function setParent_id(Rubrique $parent_id): Rubrique
    {
        $this->parent_id = $parent_id;
        return $this;
    }

    /**
     * Gets the rubrique id.
     *
     * @JMS\Serializer\Annotation\VirtualProperty
     * @JMS\Serializer\Annotation\SerializedName("parent_idId")
     * @return mixed
     */
    public function getParent_idId()
    {
        return $this->parent_id->getId();
    }

    /**
     * Returns the rubrique.
     *
     * @return RubriqueVO
     */
    public function getRubrique(): RubriqueVO
    {
        return $this->rubrique;
    }

    /**
     * Sets the rubrique.
     *
     * @param RubriqueVO $rubrique
     * @return Rubrique
     */
    public function setRubrique(RubriqueVO $rubrique): Rubrique
    {
        $this->rubrique = $rubrique;
        return $this;
    }
}
