<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Admin_Form
 * @package    CMS_Form
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-02-10
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Application\Validation\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

/**
 * Description of the TranslationPageType form.
 *
 * @subpackage   Admin_Form
 * @package    CMS_Form
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class TranslationWidgetType extends AbstractType
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $_container;

    /**
     * @var string
     */
    protected $_locale;

    /**
     * Constructor.
     *
     * @param \Doctrine\ORM\EntityManager $em
     * @return void
     */
    public function __construct(ContainerInterface $container)
    {
        $this->_container     = $container;
        $this->_locale        = $container->get('request_stack')->getCurrentRequest()->getLocale();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('enabled', 'checkbox', array(
                    //'data'  => true,
                    'label'    => 'pi.form.label.field.enabled',
            ))
            ->add('langCode', 'entity', array(
                    'class' => 'SfynxAuthBundle:Langue',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('k')
                        ->select('k')
                        ->where('k.enabled = :enabled')
                        ->orderBy('k.label', 'ASC')
                        ->setParameter('enabled', 1);
                    },
                    "label"    => "pi.form.label.field.language",
                    "attr" => array(
                            "class"=>"pi_simpleselect",
                    ),
            ))
            ->add('published_at', 'date', array(
                    'widget' => 'single_text', // choice, text, single_text
                    'input' => 'datetime',
                    'format' => $this->_container->get('sfynx.tool.twig.extension.tool')->getDatePatternByLocalFunction($this->_locale),// 'dd/MM/yyyy', 'MM/dd/yyyy',
                    'empty_value' => array('year' => 'Année', 'month' => 'Mois', 'day' => 'Jour'),
                    //'pattern' => "{{ day }}/{{ month }}/{{ year }}",
                    //'data_timezone' => "Europe/Paris",
                    //'user_timezone' => "Europe/Paris",
                    "attr" => array(
                            "class"=>"pi_datepicker",
                    ),
                    'label'    => 'pi.form.label.date.publication',
            ))
            ->add('archive_at', 'date', array(
                    'widget' => 'single_text', // choice, text, single_text
                    'input' => 'datetime',
                    'format' => $this->_container->get('sfynx.tool.twig.extension.tool')->getDatePatternByLocalFunction($this->_locale),// 'dd/MM/yyyy', 'MM/dd/yyyy',
                    'empty_value' => array('year' => 'Année', 'month' => 'Mois', 'day' => 'Jour'),
                    //'pattern' => "{{ day }}/{{ month }}/{{ year }}",
                    //'data_timezone' => "Europe/Paris",
                    //'user_timezone' => "Europe/Paris",
                    "attr" => array(
                            "class"=>"pi_datepicker",
                    ),
                    'label'    => 'pi.form.label.date.archivage',
            ))
            ->add('content', 'textarea', array(
                    "attr" => array(
                            "class"    =>"pi_editor",
                    ),
            ))
        ;
    }

    public function getBlockPrefix()
    {
        return 'piapp_adminbundle_translationwidgettype';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'Sfynx\CmfBundle\Layers\Domain\Entity\TranslationWidget',
        ));
    }
}
