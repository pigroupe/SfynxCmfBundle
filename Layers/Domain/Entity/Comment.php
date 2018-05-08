<?php
namespace Sfynx\CmfBundle\Layers\Domain\Entity;


use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitDatetimeInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitEnabledInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitPositionInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sfynx\CmfBundle\Layers\Domain\Entity\Comment
 *
 * @ORM\Table(name="pi_comment")
 * @ORM\Entity(repositoryClass="Sfynx\CmfBundle\Layers\Infrastructure\Persistence\Repository\CommentRepository")
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
class Comment implements TraitDatetimeInterface, TraitEnabledInterface, TraitPositionInterface
{
    use Traits\TraitBuild;
    use Traits\TraitDatetime;
    use Traits\TraitEnabled;
    use Traits\TraitPosition;

    /**
     * @var bigint $id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $user
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message = "You must enter your name")
     */
    protected $user;

    /**
     * @var \Sfynx\CmfBundle\Layers\Domain\Entity\TranslationPage $pageTranslation
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\CmfBundle\Layers\Domain\Entity\TranslationPage", inversedBy="comments", cascade={"persist"})
     * @ORM\JoinColumn(name="pagetrans_id", referencedColumnName="id")
     */
    protected $pageTranslation;

    /**
     * @var text $comment
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message = "You must enter a comment")
     * @Assert\Length(min = 25, minMessage = "Le commentaire doit avoir au moins {{ limit }} caractères")
     */
    protected $comment;

    /**
     * @var text $email
     *
     * @ORM\Column(name="email", type="text",  nullable=true)
     * @Assert\Email()
     */
    protected $email;

    /**
     * @var boolean $approved
     *
     * @ORM\Column(name="is_approved", type="boolean",  nullable=true)
     */
    protected $approved;

    /**
     * @ORM\Column(name="position", type="integer",  nullable=true)
     */
    protected $position;

    public function __construct()
    {
        $this->setApproved(true);
        $this->setEnabled(true);
    }

    public function __toString()
    {
        return (string) $this->getUser();
    }

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
     * Set user
     *
     * @param string $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
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
     * Set email
     *
     * @param text $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return text
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set approved
     *
     * @param boolean $approved
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;
    }

    /**
     * Get approved
     *
     * @return boolean
     */
    public function getApproved()
    {
        return $this->approved;
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
