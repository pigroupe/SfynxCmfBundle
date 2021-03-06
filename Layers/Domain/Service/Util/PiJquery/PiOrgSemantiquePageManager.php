<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Cmf
 * @package    Jquery
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Domain\Service\Util\PiJquery;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Sfynx\ToolBundle\Twig\Extension\PiJqueryExtension;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\ExtensionException;

/**
 * Semantique tree of all pages according to the section.
 *
 * @subpackage   Cmf
 * @package    Jquery
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiOrgSemantiquePageManager extends PiJqueryExtension
{
    /**
     * @var array
     * @static
     */
    static $menus = array('tree');

    /**
     * @var array
     * @static
     */
    static $actions = array('renderdefault');

    /**
     * Constructor.
     *
     * @param ContainerInterface $container
     * @param TranslatorInterface $translator
     */
    public function __construct(ContainerInterface $container, TranslatorInterface $translator)
    {
        parent::__construct($container, $translator);
    }

    /**
     * Sets init.
     *
     * @access protected
     * @return void
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     */
    protected function init($options = null)
    {
        // css
        //$this->container->get('sfynx.tool.twig.extension.layouthead')->addCssFile("bundles/sfynxtemplate/js/orgchart/css/treepage.css");

        // js
        //$this->container->get('sfynx.tool.twig.extension.layouthead')->addJsFile("bundles/sfynxtemplate/js/orgchart/js/jquery.jOrgChart.js");
    }

    /**
      * Render proxy.
      *
      * @param    $options    tableau d'options.
      * @access protected
      * @return void
      *
      * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
      */
    protected function render($options = null)
    {
        // Options management
        if (!isset($options['action']) || empty($options['action']) || (isset($options['action']) && !in_array(strtolower($options['action']), self::$actions)) ) {
            throw ExtensionException::optionValueNotSpecified('action', __CLASS__);
        }
        if (!isset($options['menu']) || empty($options['menu']) || (isset($options['menu']) && !in_array(strtolower($options['menu']), self::$menus)) ) {
            throw ExtensionException::optionValueNotSpecified('menu', __CLASS__);
        }
        $method = strtolower($options['menu']) . "Menu";
        $action = strtolower($options['action']) . "Action";
        if (method_exists($this, $method)) {
            $nodes = $this->$method($options);
        } else {
            throw ExtensionException::MethodUnDefined($method);
        }

        return $this->$action($nodes, $options);
    }

    /**
     * Default render
     *
     * @param array        $parameters
     * @param array        $options
     * @access private
     * @return string
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     */
    protected function renderdefaultAction($parameters, $options = null)
    {
        // Options management
        if ( !isset($options['id']) || empty($options['id']) ) {
            throw ExtensionException::optionValueNotSpecified('id', __CLASS__);
        }
        if ( !isset($options['template']) || empty($options['template']) ) {
            throw ExtensionException::optionValueNotSpecified('template', __CLASS__);
        }
        if ( !isset($options['entity']) || empty($options['entity']) ) {
            throw ExtensionException::optionValueNotSpecified('entity', __CLASS__);
        }
        if ( !isset($options['category']) ) {
            throw ExtensionException::optionValueNotSpecified('category', __CLASS__);
        }
        $parameters['path'] = null;
        if (!(null === $parameters['node'])){
            $Repository = $this->container->get('pi_app_gedmo.repository');
            $nodes = $Repository->getRepository($options['entity'])->getPath($parameters['node']);
            $i = 1;
            $parameters['path'][0]['question'] = $options['category'];
            foreach($nodes as $key => $node){
                if ($i == 1){
                    $parameters['path'][$i]['question']   = $node->getQuestion();
                    $parameters['path'][$i]['reponse']    = "";
                    $parameters['path'][$i-1]['reponse']  = $node->getTitle();
                    $i++;
                } else {
                    $parameters['path'][$i]['question']   = $node->getQuestion();
                    $parameters['path'][$i]['reponse']    = "";
                    $parameters['path'][$i-1]['reponse']  = $node->getTitle();
                    $i++;
                }
            }
        }
        $route = $this->container->get('request_stack')->getCurrentRequest()->get('_route');
        if (!in_array($route, array("admin_pagebytrans_update", "admin_blockbywidget_update"))) {
            $url  = $this->container->get('sfynx.tool.route.factory')->generate($route, array('category' => $options['category']));
        } else {
            $url = "#";
        }
        $parameters['url'] = $url;
        $template          = $options['template'];
        //
        $response          = $this->container->get('templating')->renderResponse("SfynxTemplateBundle:Template\\Organigram:$template", $parameters);
        $_content          = $response->getContent() . " \n";

        return $_content;
    }

    /**
     * Define semantique tree html FORMULAIRE.
     *
     * <code>
     *        {% set options_semantique = {
     *                'id': 'semantique',
     *                'entity':'Organigram',
     *                 'category':category,
     *                'action':'renderDefault',
     *                'menu': 'tree' }
     *        %}
     *        {{ renderJquery('MENU', 'org-tree-semantique', options_semantique )|raw }}
     * </code>
     *
     * @param    array $options
     * @access public
     * @return array
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     */
    public function treeMenu($options = null)
    {
        // Options management
        if ( !isset($options['entity']) || empty($options['entity']) ) {
            throw ExtensionException::optionValueNotSpecified('entity', __CLASS__);
        }
        if ( !isset($options['category']) ) {
            throw ExtensionException::optionValueNotSpecified('category', __CLASS__);
        }
        if ( !isset($options['locale']) ) {
            $locale     = $this->container->get('request_stack')->getCurrentRequest()->getLocale();
        } else {
            $locale = $options['locale'];
        }
        $Repository      = $this->container->get('pi_app_gedmo.repository');
        $options['node'] = $this->container->get('request_stack')->getCurrentRequest()->query->get('node');
        if (isset($options['node']) && !empty($options['node']) ) {
            $node          = $Repository->getRepository($options['entity'])->findNodeOr404($options['node'], $locale,'object');
            $query         = $Repository->getRepository($options['entity'])->childrenQuery($node, true);
            $nodes         = $Repository->getRepository($options['entity'])->findTranslationsByQuery($locale, $query, 'object');
        } else {
            $QueryBuilder = $Repository->getRepository($options['entity'])->getRootNodesQueryBuilder();
            $QueryBuilder
            ->andWhere('node.category = :category')
            ->setParameter('category', $options['category']);
            $query         = $QueryBuilder->getQuery();
            $nodes         = $Repository->getRepository($options['entity'])->findTranslationsByQuery($locale, $query, 'object');
            $node        = null;
        }

        return array(
            'category'    => $options['category'],
            'node'         => $node,
            'childs'     => $nodes,
        );
    }
}
