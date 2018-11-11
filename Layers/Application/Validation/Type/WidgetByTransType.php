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

use Sfynx\CmfBundle\Layers\Domain\Service\Twig\Extension\PiWidgetExtension;
use Sfynx\AuthBundle\Application\Validation\Type\SecurityRolesType;

/**
 * Description of the TranslationPageType form.
 *
 * @subpackage   Admin_Form
 * @package    CMS_Form
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class WidgetByTransType extends AbstractType
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
                    'data'  => true,
                    'label'    => 'pi.form.label.field.enabled',
            ))
            ->add('secure', 'checkbox', array(
                    'label'    => 'pi.page.form.secure',
                    'required' => false,
            ))
            ->add('heritage', SecurityRolesType::class, array(
                    'multiple' => true,
                    'required' => false,
                    'label'    => 'pi.page.form.heritage',
                    "attr" => array(
                            "class"=>"pi_multiselect",
                    ),
            ))
            ->add('configCssClass', 'text', array(
                    'label'     => 'Class Name / Snippet Name',
            ))
            ->add('plugin', 'choice', array(
                    'choices'   => PiWidgetExtension::getAvailableWidgetPlugins(),
                    'required'  => true,
                    'multiple'    => false,
                    'expanded'  => false,
//                     "attr" => array(
//                             "class"=>"pi_simpleselect",
//                     ),
            ))
            ->add('action')
            ->add('configXml', 'textarea', array(
                    //'data'  => PiWidgetExtension::getDefaultConfigXml(),
            ))
            ->add('position')
            ->add('translations', 'collection', array(
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype'    => true,
                    // Post update
                    'by_reference' => true,
                    'type'   => new TranslationWidgetType($this->_container),
                    'options'  => array(
                            'attr'      => array('class' => 'translation_widget')
                    ),
                    'label'    => ' '
            ))
        ;

        $builder
            ->add('cacheable', 'checkbox', array(
                    'label'     => 'pi.page.form.cacheable',
                    'required'  => false,
                    'help_block' => 'pi.page.form.field.cacheable',
                    "label_attr" => array(
                         "class"=>"widget_httpcache",
                     )
            ))
            ->add('public', 'checkbox', array(
                    'label'     => 'pi.page.form.public',
                    'required'  => false,
                    'help_block' => 'pi.page.form.field.public',
                    "label_attr" => array(
                            "class"=>"widget_httpcache",
                    )
            ))
            ->add('lifetime', 'number', array(
                    'label'     => 'pi.page.form.lifetime',
                    'required'  => false,
                    'help_block' => 'pi.page.form.field.lifetime',
                    "label_attr" => array(
                            "class"=>"widget_httpcache",
                    )
            ))
            ->add('cacheTemplating', 'choice', array(
                    'choices'   => \Sfynx\CmfBundle\Layers\Infrastructure\Persistence\Repository\WidgetRepository::getAvailableCacheTemplating(),
                    'label'    => 'pi.widget.form.cachetemplating',
                    'required'  => true,
                    'multiple'    => false,
                    'expanded' => true,
                    "label_attr" => array(
                            "class"=>"widget_behavior widget_cachetemplating",
                    ),
            ))
            ->add('sluggify', 'choice', array(
                    'choices'   => \Sfynx\CmfBundle\Layers\Infrastructure\Persistence\Repository\WidgetRepository::getAvailableSluggify(),
                    'label'    => 'pi.widget.form.sluggify',
                    'required'  => true,
                    'multiple'    => false,
                    'expanded' => true,
                    "label_attr" => array(
                            "class"=>"widget_behavior widget_sluggify",
                    ),
            ))
            ->add('ajax', 'choice', array(
                    'choices'   => \Sfynx\CmfBundle\Layers\Infrastructure\Persistence\Repository\WidgetRepository::getAvailableAjax(),
                    'label'    => 'pi.widget.form.ajax',
                    'required'  => true,
                    'multiple'    => false,
                    'expanded' => true,
                    "label_attr" => array(
                            "class"=>"widget_behavior widget_ajax",
                    ),
            ))
            ;
    }

    public function getBlockPrefix()
    {
        return 'piapp_adminbundle_widgettype';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'Sfynx\CmfBundle\Layers\Domain\Entity\Widget',
        ));
    }
}
