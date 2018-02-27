<?php
namespace Sfynx\CmfBundle\Layers\Domain\Entity;

use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\EntityInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitDatetimeInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitEnabledInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sfynx\CmfBundle\Layers\Domain\Entity\HistoricalStatus
 *
 * @ORM\Table(name="pi_page_historical_status")
 * @ORM\Entity(repositoryClass="Sfynx\CmfBundle\Layers\Infrastructure\Persistence\Repository\HistoricalStatusRepository")
 * @ORM\HasLifecycleCallbacks
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
class HistoricalStatus implements EntityInterface,TraitDatetimeInterface,TraitEnabledInterface
{
    use Traits\TraitBuild;
    use Traits\TraitDatetime;
    use Traits\TraitEnabled;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Sfynx\CmfBundle\Layers\Domain\Entity\TranslationPage $order
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\CmfBundle\Layers\Domain\Entity\TranslationPage", inversedBy="historicalStatus", cascade={"all"})
     * @ORM\JoinColumn(name="pagetrans_id", referencedColumnName="id")
     */
    protected $pageTranslation;


    /**
     * @var string $status
     *
     * @ORM\Column(name="status", type="string", nullable=false)
     */
    protected $status;

    /**
     * @var text $comment
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    protected $comment;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set comment
     *
     * @param text $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get comment
     *
     * @return text
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set pageTranslation
     *
     * @param \Sfynx\CmfBundle\Layers\Domain\Entity\TranslationPage
     */
    public function setPageTranslation(\Sfynx\CmfBundle\Layers\Domain\Entity\TranslationPage $pageTranslation)
    {
        $this->pageTranslation = $pageTranslation;
    }

    /**
     * Get pageTranslation
     *
     * @return \Sfynx\CmfBundle\Layers\Domain\Entity\TranslationPage
     */
    public function getPageTranslation()
    {
        return $this->pageTranslation;
    }
}
