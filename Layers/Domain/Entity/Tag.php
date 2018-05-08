<?php
namespace Sfynx\CmfBundle\Layers\Domain\Entity;


use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitDatetimeInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitEnabledInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Sfynx\CoreBundle\Layers\Domain\Model\AbstractTranslation;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Sfynx\CmfBundle\Layers\Domain\Entity\Tag
 *
 * @ORM\Table(name="pi_tag")
 * @ORM\Entity(repositoryClass="Sfynx\CmfBundle\Layers\Infrastructure\Persistence\Repository\TagRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\TranslationEntity(class="Sfynx\CmfBundle\Layers\Domain\Entity\Translation\TagTranslation")
 * @UniqueEntity("name")
 *
 * @category   Sfynx\CmfBundle\Layers
 * @package    Domain
 * @subpackage Entity
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class Tag extends AbstractTranslation implements TraitDatetimeInterface,TraitEnabledInterface
{
    use Traits\TraitBuild;
    use Traits\TraitDatetime;
    use Traits\TraitEnabled;

    /**
     * List of al translatable fields
     *
     * @var array
     * @access  protected
     */
    protected $_fields    = array('groupname', 'name');

    /**
     * Name of the Translation Entity
     *
     * @var array
     * @access  protected
     */
    protected $_translationClass = 'Sfynx\CmfBundle\Layers\Domain\Entity\Translation\TagTranslation';

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Sfynx\CmfBundle\Layers\Domain\Entity\Translation\TagTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;

    /**
     * @var bigint $id
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $groupname
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="groupname", type="string", length=255, nullable=true)
     * @Assert\Length(min = 2, minMessage = "Le nom doit avoir au moins {{ limit }} caractères")
     */
    protected $groupname;

    /**
     * @var string
     *
     * @ORM\Column(name="groupnameother", type="string", length=128, nullable=true)
     */
    protected $groupnameother;

    /**
     * @var string $name
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="name", type="string", length=255, nullable=true, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min = 2, minMessage = "Le nom doit avoir au moins {{ limit }} caractères")
     */
    protected $name;

    /**
     * @var string $color
     *
     * @ORM\Column(name="color", type="string", length=7, nullable=true)
     * @Assert\Length(min = 7, max = 7, minMessage = "La couleur doit avoir {{ limit }} caractères", maxMessage = "La couleur doit avoir {{ limit }} caractères")
     */
    protected $color;

    /**
     * @var string $Hicolor
     *
     * @ORM\Column(name="Hicolor", type="string", length=7, nullable=true)
     * @Assert\Length(min = 7, max = 7, minMessage = "La couleur doit avoir {{ limit }} caractères", maxMessage = "La couleur doit avoir {{ limit }} caractères")
     */
    protected $Hicolor;

    /**
     * @var array
     * @ORM\Column(name="weight", type="array", nullable=true)
     */
    protected $weight;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->setEnabled(true);
    }

    /**
     *
     * This method is used when you want to convert to string an object of
     * this Entity
     * ex: $value = (string)$entity;
     *
     */
    public function __toString() {
        return (string) $this->name;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdatedValue()
    {
        $other  = $this->getGroupnameother();
        //print_r($other);exit;
        if (!empty($other)){
            $this->setGroupname($other);
            $this->setGroupnameother('');
            $this->translate($this->locale)->setGroupname($other);
            $this->translate($this->locale)->setGroupnameother('');
        }
    }

    /**
     * Get id
     *
     * @return bigint
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set groupname
     *
     * @param string $groupname
     */
    public function setGroupname($groupname)
    {
        $this->groupname = $groupname;
    }

    /**
     * Get groupname
     *
     * @return string
     */
    public function getGroupname()
    {
        return $this->groupname;
    }

    /**
     * Set groupname other
     *
     * @param string $groupnameother
     */
    public function setGroupnameother($groupnameother)
    {
        $this->groupnameother = $groupnameother;
    }

    /**
     * Get groupname other
     *
     * @return string
     */
    public function getGroupnameother()
    {
        return $this->groupnameother;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set color
     *
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set Hicolor
     *
     * @param string $hicolor
     */
    public function setHicolor($hicolor)
    {
        $this->Hicolor = $hicolor;
    }

    /**
     * Get Hicolor
     *
     * @return string
     */
    public function getHicolor()
    {
        return $this->Hicolor;
    }

    /**
     * Set langCode
     *
     * @param \Sfynx\AuthBundle\Domain\Entity\Langue
     */
    public function setLangCode(\Sfynx\AuthBundle\Domain\Entity\Langue $langCode)
    {
        $this->langCode = $langCode;
    }

    /**
     * Get langCode
     *
     * @return \Sfynx\AuthBundle\Domain\Entity\Langue
     */
    public function getLangCode()
    {
        return $this->langCode;
    }

    /**
     * Set weight
     *
     * @param array $weights
     */
    public function setWeight( array $weights)
    {
        $this->weight = array();

        foreach ($weights as $key => $value) {
            $this->addWeight($key, $value);
        }
    }

    /**
     * Get weight
     *
     * @return array
     */
    public function getWeight($key = "")
    {
        if (!empty($key)){
            if ($this->weight && array_key_exists($key, $this->weight))
                return $this->weight[ $key ];
            else
                return 0;
        }else
            return $this->weight;
    }

    /**
     * Adds a weight.
     *
     * @param string $weight
     */
    public function addWeight($key, $value)
    {
           $this->weight[ $key ] = $value;
    }

    /**
     * increment a weight.
     *
     * @param string $weight
     */
    public function incrementWeight($key)
    {
        if (!$this->weight) {
            $this->addWeight($key, 1);
        }
        else {
            if (array_key_exists($key, $this->weight)) {
                $this->addWeight($key, $this->weight[$key] + 1);
            }else
                $this->addWeight($key, 1);
        }
    }

    /**
     * decrement a weight.
     *
     * @param string $weight
     */
    public function decrementWeight($key)
    {
           if ($this->weight && array_key_exists($key, $this->weight)) {
               if ($this->weight[$key] > 1)
                   $this->addWeight($key, $this->weight[$key] - 1);
               else
                   unset($this->weight[$key]);
           }
    }

}
