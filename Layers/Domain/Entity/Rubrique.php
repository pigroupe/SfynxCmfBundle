<?php
namespace Sfynx\CmfBundle\Layers\Domain\Entity;

use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\EntityInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitDatetimeInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitEnabledInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitPositionInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitTreeInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Sfynx\PositionBundle\Annotation as PI;

/**
 * Sfynx\CmfBundle\Layers\Domain\Entity\Rubrique
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="pi_rubrique")
 * @ORM\Entity(repositoryClass="Sfynx\CmfBundle\Layers\Infrastructure\Persistence\Repository\RubriqueRepository")
 * @ORM\HasLifecycleCallbacks()
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
class Rubrique implements EntityInterface,TraitDatetimeInterface,TraitEnabledInterface,TraitTreeInterface,TraitPositionInterface
{
    use Traits\TraitBuild;
    use Traits\TraitDatetime;
    use Traits\TraitEnabled;
    use Traits\TraitTree;
    use Traits\TraitPosition;

    /**
     * @var integer $parent
     *
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Rubrique", inversedBy="childrens", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    protected $parent;

    /**
     * @var array $childrens
     *
     * @ORM\OneToMany(targetEntity="Rubrique", mappedBy="parent", cascade={"all"})
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    protected $childrens;

    /**
     * @var bigint $id
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $keywords
     *
     * @ORM\ManyToMany(targetEntity="KeyWord")
     * @ORM\JoinTable(name="pi_keyword_rubrique",
     *      joinColumns={@ORM\JoinColumn(name="rubrique_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="keyword_id", referencedColumnName="id")}
     *      )
     * @Assert\Valid()
     */
    protected $keywords;

    /**
     * @var string $titre
     *
     * @ORM\Column(name="titre", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message = "You must enter a title")
     * @Assert\Length(min = 3, minMessage = "Le titre name doit avoir au moins {{ limit }} caractères")
     */
    protected $titre;

    /**
     * @var text $descriptif
     *
     * @ORM\Column(name="descriptif", type="text", nullable=true)
     * @Assert\Length(min = 3, minMessage = "Le descriptif name doit avoir au moins {{ limit }} caractères")
     */
    protected $descriptif;

    /**
     * @var text $texte
     *
     * @ORM\Column(name="texte", type="text", nullable=true)
     */
    protected $texte;

    /**
     * @ORM\Column(name="position", type="integer",  nullable=true)
     * @PI\Positioned(SortableOrders = {"type":"relationship","field":"category","columnName":"category"})
     */
    protected $position;

    public function __construct()
    {
        $this->childrens = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setEnabled(true);
    }

    public function __toString()
    {
        return (string) $this->getTitre();
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
     * Set titre
     *
     * @param string $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set descriptif
     *
     * @param text $descriptif
     */
    public function setDescriptif ($descriptif)
    {
        $this->descriptif = $descriptif;
    }

    /**
     * Get descriptif
     *
     * @return text
     */
    public function getDescriptif ()
    {
        return $this->descriptif;
    }

    /**
     * Set texte
     *
     * @param text $texte
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;
    }

    /**
     * Get texte
     *
     * @return text
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * Add childrens
     *
     * @param \Sfynx\CmfBundle\Layers\Domain\Entity\Rubrique
     */
    public function addRubrique(\Sfynx\CmfBundle\Layers\Domain\Entity\Rubrique $childrens)
    {
        $this->childrens[] = $childrens;
    }

    /**
     * Add keywords
     *
     * @param \Sfynx\CmfBundle\Layers\Domain\Entity\KeyWord    $keywords
     */
    public function addKeyWord(\Sfynx\CmfBundle\Layers\Domain\Entity\KeyWord $keywords)
    {
        $this->keywords[] = $keywords;
    }

    public function setKeyWords($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * Get keywords
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getKeywords()
    {
        return $this->keywords;
    }
}
