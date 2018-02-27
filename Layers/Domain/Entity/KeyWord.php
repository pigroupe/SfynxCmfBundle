<?php
namespace Sfynx\CmfBundle\Layers\Domain\Entity;

use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\EntityInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitDatetimeInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitEnabledInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sfynx\CmfBundle\Layers\Domain\Entity\KeyWord
 *
 * @ORM\Table(name="pi_keyword")
 * @ORM\Entity(repositoryClass="Sfynx\CmfBundle\Layers\Infrastructure\Persistence\Repository\KeyWordRepository")
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
class KeyWord implements EntityInterface,TraitDatetimeInterface,TraitEnabledInterface
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
     * @var string $groupname
     *
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min = 2, minMessage = "Le nom doit avoir au moins {{ limit }} caractères")
     */
    protected $name;

    /**
     * Constructor
     */
    public function __construct()
    {
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
        return (string) $this->getGroupname() . ' :: '. $this->getName();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdatedValue()
    {
        $other  = $this->getGroupnameother();
        if (!empty($other)){
            $this->setGroupname($other);
            $this->setGroupnameother('');
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
}
