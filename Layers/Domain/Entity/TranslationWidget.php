<?php
namespace Sfynx\CmfBundle\Layers\Domain\Entity;


use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitDatetimeInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitEnabledInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sfynx\CmfBundle\Layers\Domain\Entity\TranslationWidget
 *
 * @ORM\Table(name="pi_widget_translation")
 * @ORM\Entity(repositoryClass="Sfynx\CmfBundle\Layers\Infrastructure\Persistence\Repository\TranslationWidgetRepository")
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
class TranslationWidget implements TraitDatetimeInterface,TraitEnabledInterface
{
    use Traits\TraitBuild;
    use Traits\TraitDatetime;
    use Traits\TraitEnabled;

    /**
     * @var bigint $id
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Sfynx\CmfBundle\Layers\Domain\Entity\Widget $widget
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\CmfBundle\Layers\Domain\Entity\Widget", inversedBy="translations", cascade={"persist"})
     * @ORM\JoinColumn(name="widget_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $widget;

    /**
     * @var \Sfynx\AuthBundle\Domain\Entity\Langue $langCode
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\AuthBundle\Domain\Entity\Langue", cascade={"persist", "refresh"})
     * @ORM\JoinColumn(name="lang_code", referencedColumnName="id", nullable=false)
     */
    protected $langCode;

    /**
     * @var text $content
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    protected $content;

    public function __construct()
    {
        $this->setEnabled(true);
    }

    public function __toString()
    {
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
    }

    /**
     * Set content
     *
     * @param text $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return text
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set widget
     *
     * @param \Sfynx\CmfBundle\Layers\Domain\Entity\Widget
     */
    public function setWidget(\Sfynx\CmfBundle\Layers\Domain\Entity\Widget $widget)
    {
        $this->widget = $widget;
    }

    /**
     * Get widget
     *
     * @return \Sfynx\CmfBundle\Layers\Domain\Entity\Widget
     */
    public function getWidget()
    {
        return $this->widget;
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
}
