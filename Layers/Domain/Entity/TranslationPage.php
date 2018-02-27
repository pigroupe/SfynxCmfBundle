<?php
namespace Sfynx\CmfBundle\Layers\Domain\Entity;

use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\EntityInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitDatetimeInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitEnabledInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitSlugifyInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Sfynx\CmfBundle\Application\Validation\Validator\Constraints as SfynxAssert;
use Sfynx\CmfBundle\Layers\Domain\Entity\HistoricalStatus;
use Sfynx\CmfBundle\Layers\Domain\Entity\Page;
use Sfynx\AuthBundle\Domain\Entity\Langue;
use Sfynx\CmfBundle\Layers\Domain\Entity\Tag;
use Sfynx\CmfBundle\Layers\Domain\Entity\Comment;

/**
 * Sfynx\CmfBundle\Layers\Domain\Entity\TranslationPage
 *
 * @ORM\Table(name="pi_page_translation")
 * @ORM\Entity(repositoryClass="Sfynx\CmfBundle\Layers\Infrastructure\Persistence\Repository\TranslationPageRepository")
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
class TranslationPage implements EntityInterface,TraitDatetimeInterface,TraitEnabledInterface,TraitSlugifyInterface
{
    use Traits\TraitBuild;
    use Traits\TraitDatetime;
    use Traits\TraitEnabled;
    use Traits\TraitSlugify;

    /**
     * @var bigint $id
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Page $page
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\CmfBundle\Layers\Domain\Entity\Page", inversedBy="translations", cascade={"persist"})
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $page;

    /**
     * @var ArrayCollection $tags
     *
     * @ORM\ManyToMany(targetEntity="Sfynx\CmfBundle\Layers\Domain\Entity\Tag")
     * @ORM\JoinTable(name="pi_tag_page",
     *      joinColumns={@ORM\JoinColumn(name="page_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     */
    protected $tags;

    /**
     * @var ArrayCollection $comments
     *
     * @ORM\OneToMany(targetEntity="Sfynx\CmfBundle\Layers\Domain\Entity\Comment", mappedBy="pageTranslation", cascade={"all"})
     */
    protected $comments;

    /**
     * @var Langue $langCode
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\AuthBundle\Domain\Entity\Langue", cascade={"persist", "detach"})
     * @ORM\JoinColumn(name="lang_code", referencedColumnName="id", nullable=true)
     */
    protected $langCode;

    /**
     * @var string $langStatus
     *
     * @ORM\Column(name="lang_status", type="string", nullable=true)
     */
    protected $langStatus;

    /**
     * @var string $status
     *
     * @ORM\Column(name="status", type="string", nullable=false)
     * @Assert\NotBlank(message = "erreur.status.notblank")
     */
    protected $status;

    /**
     * @var ArrayCollection $historicalStatus
     *
     * @ORM\OneToMany(targetEntity="Sfynx\CmfBundle\Layers\Domain\Entity\HistoricalStatus", mappedBy="pageTranslation", cascade={"all"})
     */
    protected $historicalStatus;

    /**
     * @var boolean $secure
     *
     * @ORM\Column(name="is_secure", type="boolean", nullable=true)
     */
    protected $secure;

    /**
     * @var array
     * @ORM\Column(name="secure_roles", type="array", nullable=true)
     */
    protected $heritage;

    /**
     * @var boolean $indexable
     *
     * @ORM\Column(name="is_indexable", type="boolean", nullable=true)
     */
    protected $indexable;

    /**
     * @var string $breadcrumb
     *
     * @ORM\Column(name="breadcrumb", type="string", nullable=true)
     * @Assert\Length(min = 3, minMessage = "Le breadcrumb doit avoir au moins {{ limit }} caractères")
     */
    protected $breadcrumb;

    /**
     * @var string $slug
     *
     * @ORM\Column(name="slug", type="string", nullable=true)
     * @Assert\NotBlank(message = "You must enter a slug")
     * @Assert\Length(min = 3, minMessage = "erreur.slug.minlength")
     * @SfynxAssert\Unique(entity="TranslationPage", property="slug")
     */
    protected $slug;

    /**
     * @var text $meta_title
     *
     * @ORM\Column(name="meta_title", type="string", nullable=true)
     */
    protected $meta_title;

    /**
     * @var text $meta_keywords
     *
     * @ORM\Column(name="meta_keywords", type="text", nullable=true)
     */
    protected $meta_keywords;

    /**
     * @var text $meta_description
     *
     * @ORM\Column(name="meta_description", type="text", nullable=true)
     */
    protected $meta_description;

    /**
     * @var string $surtitre
     *
     * @ORM\Column(name="surtitre", type="string", nullable=true)
     * @Assert\Length(min = 3, minMessage = "Le surtitre name doit avoir au moins {{ limit }} caractères")
     */
    protected $surtitre;

    /**
     * @var string $titre
     *
     * @ORM\Column(name="titre", type="string", nullable=true)
     * @Assert\Length(min = 3, minMessage = "Le titre name doit avoir au moins {{ limit }} caractères")
     */
    protected $titre;

    /**
     * @var string $soustitre
     *
     * @ORM\Column(name="soustitre", type="string", nullable=true)
     * @Assert\Length(min = 3, minMessage = "Le soustitre name doit avoir au moins {{ limit }} caractères")
     */
    protected $soustitre;

    /**
     * @var text $descriptif
     *
     * @ORM\Column(name="descriptif", type="text", nullable=true)
     * @Assert\Length(min = 25, minMessage = "Le descriptif name doit avoir au moins {{ limit }} caractères")
     */
    protected $descriptif;

    /**
     * @var text $chapo
     *
     * @ORM\Column(name="chapo", type="text", nullable=true)
     * @Assert\Length(min = 25, minMessage = "Le chapo name doit avoir au moins {{ limit }} caractères")
     */
    protected $chapo;

    /**
     * @var text $texte
     *
     * @ORM\Column(name="texte", type="text", nullable=true)
     */
    protected $texte;

    /**
     * @var text $ps
     *
     * @ORM\Column(name="ps", type="text", nullable=true)
     */
    protected $ps;

    public function __construct()
    {
        $this->tags              = new ArrayCollection();
        $this->comments          = new ArrayCollection();
        $this->historical_status = new ArrayCollection();

        $this->setEnabled(true);
    }

    public function __toString()
    {
        $meta_title = $this->getMetaTitle();
        if (!empty($meta_title))
            $meta_title = ' ('.$meta_title.')';
        return (string) $this->getId() . '. ' . $this->getSlug() . $meta_title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $this->slugify($slug);
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
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
     * Set langStatus
     *
     * @param string $langStatus
     */
    public function setLangStatus($langStatus)
    {
        $this->langStatus = $langStatus;
    }

    /**
     * Get langStatus
     *
     * @return string
     */
    public function getLangStatus()
    {
        return $this->langStatus;
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
     * Set secure
     *
     * @param boolean $secure
     */
    public function setSecure($secure)
    {
        $this->secure = $secure;
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

    /**
     * Set Role
     *
     * @param array $heritage
     */
    public function setHeritage( array $heritage)
    {
        $this->heritage = [];
        foreach ($heritage as $role) {
            $this->addRoleInHeritage($role);
        }
    }

    /**
     * Get heritage
     *
     * @return array
     */
    public function getHeritage()
    {
        return $this->heritage;
    }

    /**
     * Adds a role heritage.
     *
     * @param string $role
     */
    public function addRoleInHeritage($role)
    {
        $role = strtoupper($role);

        if (!in_array($role, $this->heritage, true)) {
            $this->heritage[] = $role;
        }
    }

    /**
     * Set indexable
     *
     * @param boolean $indexable
     */
    public function setIndexable($indexable)
    {
        $this->indexable = $indexable;
    }

    /**
     * Get indexable
     *
     * @return boolean
     */
    public function getIndexable()
    {
        return $this->indexable;
    }

    /**
     * Set breadcrumb
     *
     * @param string $breadcrumb
     */
    public function setBreadcrumb($breadcrumb)
    {
        $this->breadcrumb = $breadcrumb;
    }

    /**
     * Get breadcrumb
     *
     * @return string
     */
    public function getBreadcrumb()
    {
        return $this->breadcrumb;
    }

    /**
     * Set meta_Title
     *
     * @param text $metaTitle
     */
    public function setMetaTitle($metaTitle)
    {
        $this->meta_title = $metaTitle;
    }

    /**
     * Get meta_Title
     *
     * @return text
     */
    public function getMetaTitle()
    {
        return $this->meta_title;
    }

    /**
     * Set meta_keywords
     *
     * @param text $metaKeywords
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->meta_keywords = $metaKeywords;
    }

    /**
     * Get meta_keywords
     *
     * @return text
     */
    public function getMetaKeywords()
    {
        return $this->meta_keywords;
    }

    /**
     * Set meta_description
     *
     * @param text $metaDescription
     */
    public function setMetaDescription($metaDescription)
    {
        $this->meta_description = $metaDescription;
    }

    /**
     * Get meta_description
     *
     * @return text
     */
    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    /**
     * Set surtitre
     *
     * @param string $surtitre
     */
    public function setSurtitre($surtitre)
    {
        $this->surtitre = $surtitre;
    }

    /**
     * Get surtitre
     *
     * @return string
     */
    public function getSurtitre()
    {
        return $this->surtitre;
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
     * Set soustitre
     *
     * @param string $soustitre
     */
    public function setSoustitre($soustitre)
    {
        $this->soustitre = $soustitre;
    }

    /**
     * Get soustitre
     *
     * @return string
     */
    public function getSoustitre()
    {
        return $this->soustitre;
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
     * Set chapo
     *
     * @param text $chapo
     */
    public function setChapo($chapo)
    {
        $this->chapo = $chapo;
    }

    /**
     * Get chapo
     *
     * @return text
     */
    public function getChapo()
    {
        return $this->chapo;
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
     * Set ps
     *
     * @param text $ps
     */
    public function setPs($ps)
    {
        $this->ps = $ps;
    }

    /**
     * Get ps
     *
     * @return text
     */
    public function getPs()
    {
        return $this->ps;
    }

    /**
     * Set page
     *
     * @param Page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * Get page
     *
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Add tag
     *
     * @param Tag
     */
    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;
    }

    public function setTags(ArrayCollection $tags)
    {
        $this->tags = $tags;
    }

    /**
     * remove tag
     *
     * @param  Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        return $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Add comments
     *
     * @param Comment
     */
    public function addComment(Comment $comments)
    {
        $this->comments[] = $comments;
    }

    /**
     * Get comments
     *
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set langCode
     *
     * @param Langue
     */
    public function setLangCode($langCode)
    {
        $this->langCode = $langCode;
    }

    /**
     * Get langCode
     *
     * @return Langue
     */
    public function getLangCode()
    {
        return $this->langCode;
    }

    /**
     * Add historical_status
     *
     * @param HistoricalStatus
     */
    public function addHistoricalStatus(HistoricalStatus $historicalStatus)
    {
        $this->historical_status[] = $historicalStatus;
    }

    /**
     * Get historical_status
     *
     * @return ArrayCollection
     */
    public function getHistoricalStatus()
    {
        return $this->historical_status;
    }
}
