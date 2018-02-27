<?php
namespace Sfynx\CmfBundle\Layers\Domain\Entity;

use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\EntityInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitDatetimeInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitEnabledInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitPositionInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Sfynx\CmfBundle\Layers\Domain\Service\Twig\Extension\PiWidgetExtension;
use Sfynx\PositionBundle\Annotation as PI;

/**
 * Sfynx\CmfBundle\Layers\Domain\Entity\Widget
 *
 * @ORM\Table(name="pi_widget")
 * @ORM\Entity(repositoryClass="Sfynx\CmfBundle\Layers\Infrastructure\Persistence\Repository\WidgetRepository")
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
class Widget implements EntityInterface,TraitDatetimeInterface,TraitEnabledInterface,TraitPositionInterface
{
    use Traits\TraitBuild;
    use Traits\TraitDatetime;
    use Traits\TraitEnabled;
    use Traits\TraitPosition;
    use Traits\TraitHeritage;

    /**
     * @var bigint $id
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Sfynx\CmfBundle\Layers\Domain\Entity\Block $block
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\CmfBundle\Layers\Domain\Entity\Block", inversedBy="widgets", cascade={"persist"})
     * @ORM\JoinColumn(name="block_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $block;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $translations
     *
     * @ORM\OneToMany(targetEntity="Sfynx\CmfBundle\Layers\Domain\Entity\TranslationWidget", mappedBy="widget", cascade={"all"})
     */
    protected $translations;

    /**
     * @var string $plugin
     *
     * @ORM\Column(name="plugin", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    protected $plugin;

    /**
     * @var string $action
     *
     * @ORM\Column(name="action", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    protected $action;

    /**
     * @var boolean $cacheable
     *
     * @ORM\Column(name="is_cacheable", type="boolean", nullable=true)
     */
    protected $cacheable = false;

    /**
     * @var boolean $public
     *
     * @ORM\Column(name="is_public", type="boolean", nullable=true)
     */
    protected $public = false;

    /**
     * @var integer $lifetime
     *
     * @ORM\Column(name="lifetime", type="integer", nullable=true)
     */
    protected $lifetime = 3;

    /**
     * @var boolean $public
     *
     * @ORM\Column(name="is_templating_cache", type="integer", nullable=true)
     */
    protected $cacheTemplating = 0;

    /**
     * @var boolean $public
     *
     * @ORM\Column(name="is_ajax", type="integer", nullable=true)
     */
    protected $ajax = 0;

    /**
     * @var boolean $sluggify
     *
     * @ORM\Column(name="is_sluggify", type="integer", nullable=true)
     */
    protected $sluggify = 0;

    /**
     * @var string $configCssClass
     *
     * @ORM\Column(name="config_css_class", type="string", nullable=true)
     */
    protected $configCssClass;

    /**
     * @var boolean $secure
     *
     * @ORM\Column(name="is_secure", type="boolean", nullable=true)
     */
    protected $secure;

    /**
     * @var text $configXml
     *
     * @ORM\Column(name="config_xml", type="text", nullable=true)
     */
    protected $configXml;

    /**
     * @ORM\Column(name="position", type="integer",  nullable=true)
     * @PI\Positioned(SortableOrders = {"type":"relationship","field":"block","columnName":"block_id"})
     */
    protected $position;


    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();

        $this->setEnabled(true);
        $this->setConfigXml(PiWidgetExtension::getDefaultConfigXml());
        $this->setLifetime('0');
    }

    /**
     *
     * This method is used when you want to convert to string an object of
     * this Entity
     * ex: $value = (string)$entity;
     *
     */
    public function __toString() {
        return (string) $this->id;
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
        return $this;
    }

    /**
     * Set plugin
     *
     * @param string $plugin
     */
    public function setPlugin($plugin)
    {
        $this->plugin = $plugin;
        return $this;
    }

    /**
     * Get plugin
     *
     * @return string
     */
    public function getPlugin()
    {
        return $this->plugin;
    }

    /**
     * Set action
     *
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set configCssClass
     *
     * @param string $configCssClass
     */
    public function setConfigCssClass($configCssClass)
    {
        $this->configCssClass = $configCssClass;
        return $this;
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
        return $this;
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
     * Set cacheable
     *
     * @param boolean $cacheable
     */
    public function setCacheable($cacheable)
    {
        $this->cacheable = $cacheable;
        return $this;
    }

    /**
     * Get cacheable
     *
     * @return boolean
     */
    public function getCacheable()
    {
        return $this->cacheable;
    }

    /**
     * Set cacheTemplating
     *
     * @param boolean $cacheTemplating
     */
    public function setCacheTemplating($cacheTemplating)
    {
    	$this->cacheTemplating = $cacheTemplating;
        return $this;
    }

    /**
     * Get cacheTemplating
     *
     * @return boolean
     */
    public function getCacheTemplating()
    {
    	return $this->cacheTemplating;
    }

    /**
     * Set ajax
     *
     * @param boolean $ajax
     */
    public function setAjax($ajax)
    {
    	$this->ajax = $ajax;
        return $this;
    }

    /**
     * Get ajax
     *
     * @return boolean
     */
    public function getAjax()
    {
    	return $this->ajax;
    }

    /**
     * Set sluggify
     *
     * @param boolean $sluggify
     */
    public function setSluggify($sluggify)
    {
    	$this->sluggify = $sluggify;
        return $this;
    }

    /**
     * Get sluggify
     *
     * @return boolean
     */
    public function getSluggify()
    {
    	return $this->sluggify;
    }

    /**
     * Set public
     *
     * @param boolean $public
     */
    public function setPublic($public)
    {
        $this->public = $public;
        return $this;
    }

    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set lifetime
     *
     * @param integer $lifetime
     */
    public function setLifetime($lifetime)
    {
        $this->lifetime = $lifetime;
        return $this;
    }

    /**
     * Get lifetime
     *
     * @return integer
     */
    public function getLifetime()
    {
        return $this->lifetime;
    }

    /**
     * Set block
     *
     * @param \Sfynx\CmfBundle\Layers\Domain\Entity\Block $block
     */
    public function setBlock($block)
    {
        $this->block = $block;
        return $this;
    }

    /**
     * Get block
     *
     * @return \Sfynx\CmfBundle\Layers\Domain\Entity\Block
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * Set the collection of related translations
     *
     * @param \Doctrine\Common\Collections\ArrayCollection
     */
    public function setTranslations(\Doctrine\Common\Collections\ArrayCollection $translations)
    {
        $this->translations = $translations;
        return $this;
    }

    /**
     * Add translations
     *
     * @param \Sfynx\CmfBundle\Layers\Domain\Entity\TranslationWidget
     */
    public function addTranslation(\Sfynx\CmfBundle\Layers\Domain\Entity\TranslationWidget $translation)
    {
        $this->translations->add($translation);
        $translation->setWidget($this);
        return $this;
    }

    /**
     * Get translations
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * Set secure
     *
     * @param boolean $secure
     */
    public function setSecure($secure)
    {
    	$this->secure = $secure;
        return $this;
    }

    /**
     * Get secure
     *
     * @return boolean
     */
    public function getSecure()
    {
    	return $this->secure;
    }
}
