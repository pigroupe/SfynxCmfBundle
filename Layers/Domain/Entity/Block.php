<?php
namespace Sfynx\CmfBundle\Layers\Domain\Entity;


use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitDatetimeInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitEnabledInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitPositionInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Sfynx\PositionBundle\Annotation as PI;

/**
 * Sfynx\CmfBundle\Layers\Domain\Entity\Block
 *
 * @ORM\Table(name="pi_block")
 * @ORM\Entity(repositoryClass="Sfynx\CmfBundle\Layers\Infrastructure\Persistence\Repository\BlockRepository")
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
class Block implements TraitDatetimeInterface,TraitEnabledInterface,TraitPositionInterface
{
    use Traits\TraitBuild;
    use Traits\TraitDatetime;
    use Traits\TraitEnabled;
    use Traits\TraitPosition;

    /**
     * @var bigint $id
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Sfynx\CmfBundle\Layers\Domain\Entity\Page $page
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\CmfBundle\Layers\Domain\Entity\Page", inversedBy="blocks", cascade={"persist"})
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $page;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $widgets
     *
     * @ORM\OneToMany(targetEntity="Sfynx\CmfBundle\Layers\Domain\Entity\Widget", mappedBy="block", cascade={"all"})
     * @Assert\Valid
     */
    protected $widgets;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var string $configCssClass
     *
     * @ORM\Column(name="config_css_class", type="string", nullable=false)
     * @Assert\NotBlank(message = "You must enter a css class name")
     */
    protected $configCssClass;

    /**
     * @var text $configXml
     *
     * @ORM\Column(name="config_xml", type="text", nullable=true)
     */
    protected $configXml;

    /**
     * @ORM\Column(name="position", type="integer",  nullable=true)
     * @PI\Positioned(SortableOrders = {"type":"relationship","field":"page","columnName":"page_id"})
     */
    protected $position;

    public function __construct()
    {
        $this->widgets = new \Doctrine\Common\Collections\ArrayCollection();

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
     * Get id
     *
     * @return bigint
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @return integer
     */
    public function setId($id)
    {
    	$this->id = (int) $id;
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
     * Set configCssClass
     *
     * @param string $configCssClass
     */
    public function setConfigCssClass($configCssClass)
    {
        $this->configCssClass = $configCssClass;
    }

    /**
     * Get configCssClass
     *
     * @return string
     */
    public function getConfigCssClass()
    {
        return $this->configCssClass;
    }

    /**
     * Set configXml
     *
     * @param text $configXml
     */
    public function setConfigXml($configXml)
    {
        $this->configXml = $configXml;
    }

    /**
     * Get configXml
     *
     * @return text
     */
    public function getConfigXml()
    {
        return $this->configXml;
    }

    /**
     * Set page
     *
     * @param \Sfynx\CmfBundle\Layers\Domain\Entity\Page
     */
    public function setPage(\Sfynx\CmfBundle\Layers\Domain\Entity\Page $page)
    {
        $this->page = $page;
    }

    /**
     * Get page
     *
     * @return \Sfynx\CmfBundle\Layers\Domain\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Add widgets
     *
     * @param \Sfynx\CmfBundle\Layers\Domain\Entity\Widget
     *
     */
    public function addWidget(\Sfynx\CmfBundle\Layers\Domain\Entity\Widget $widgets)
    {
        $this->widgets->add($widgets);
        $widgets->setBlock($this);
    }

    /**
     * Get widgets
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getWidgets()
    {
    	// we order by position value.
    	$iterator = $this->widgets->getIterator();
    	$iterator->uasort(function ($first, $second) {
    		if ($first === $second) {
    			return 0;
    		}

    		return (int) $first->getPosition() < (int) $second->getPosition() ? -1 : 1;
    	});
    	$this->widgets = new \Doctrine\Common\Collections\ArrayCollection(iterator_to_array($iterator));

        return $this->widgets;
    }
}
