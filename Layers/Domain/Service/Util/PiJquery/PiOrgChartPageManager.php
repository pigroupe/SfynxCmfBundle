<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage Cmf
 * @package    Jquery
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-01-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Domain\Service\Util\PiJquery;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Sfynx\ToolBundle\Twig\Extension\PiJqueryExtension;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\ExtensionException;
use Sfynx\CmfBundle\Layers\Domain\Service\Manager\PiPageManager;
use Sfynx\CmfBundle\Layers\Domain\Service\Manager\PiTreeManager;

/**
 * Organigramm of all pages according to the section with Org Chart Jquery plugin.
 *
 * @subpackage Cmf
 * @package    Jquery
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiOrgChartPageManager extends PiJqueryExtension
{
    /**
     * @var array
     * @static
     */
    static $menus = array('page', 'organigram');

    /**
     * @var array
     * @static
     */
    static $actions = array('renderbyclick', 'renderdefault');

    /**
     * Constructor.
     *
     * @param ContainerInterface $container The service container
     * @param TranslatorInterface $translator The service translator
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
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     */
    protected function init($options = null)
    {
        // css
        $this->container->get('sfynx.tool.twig.extension.layouthead')->addCssFile("bundles/sfynxtemplate/js/orgchart/css/treepage.css");
        $this->container->get('sfynx.tool.twig.extension.layouthead')->addCssFile("bundles/sfynxtemplate/js/orgchart/css/custom.css");
        $this->container->get('sfynx.tool.twig.extension.layouthead')->addCssFile("bundles/sfynxtemplate/js/orgchart/css/jquery.jOrgChart.css");

        // js
        //$this->container->get('sfynx.tool.twig.extension.layouthead')->addJsFile("bundles/sfynxtemplate/js/orgchart/js/prettify.js");
        $this->container->get('sfynx.tool.twig.extension.layouthead')->addJsFile("bundles/sfynxtemplate/js/orgchart/js/jquery.jOrgChart.js");
    }

    /**
      * Set progress text for Progress flash dialog.
      *
      * @param $options tableau d'options.
     *
      * @access protected
      * @return void
      * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
      */
    protected function render($options = null)
    {
        // Options management
        if (!isset($options['action']) || empty($options['action']) || (isset($options['action']) && !in_array(strtolower($options['action']), self::$actions))) {
            throw ExtensionException::optionValueNotSpecified('action', __CLASS__);
        }
        if (!isset($options['menu']) || empty($options['menu']) || (isset($options['menu']) && !in_array(strtolower($options['menu']), self::$menus))) {
            throw ExtensionException::optionValueNotSpecified('menu', __CLASS__);
        }
        // set names
        $method = strtolower($options['menu']) . "Menu";
        $action = strtolower($options['action']) . "Action";
        //
        if (method_exists($this, $method)) {
            $htmlTree = $this->$method($options);
        } else {
            throw ExtensionException::MethodUnDefined($method);
        }

        return $this->$action($htmlTree, $options);
    }

    /**
     *render chart with a click event.
     *
     * @param string $htmlTree
     * @param array  $options
     *
     * @access private
     * @return string
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     */
    protected function renderdefaultAction($htmlTree, $options = null)
    {
        // Options management
        if (!isset($options['id']) || empty($options['id'])) {
            throw ExtensionException::optionValueNotSpecified('id', __CLASS__);
        }

        // We open the buffer.
        ob_start ();
        ?>
            jQuery(document).ready(function() {
                $("[data-drag^='dragmap_']").draggable();
                $("#<?php echo $options['id']; ?>").jOrgChart({
                    chartElement : '#chart_<?php echo $options['id']; ?>',
                    chartClass : 'jOrgChart',
                    dragAndDrop  : true
                });
            });
        <?php
        // We retrieve the contents of the buffer.
        $_content_js = ob_get_contents ();
        // We clean the buffer.
        ob_clean ();
        // We close the buffer.
        ob_end_flush ();


        // We open the buffer.
        ob_start ();
        ?>
            <ul id="<?php echo $options['id']; ?>" style="display:none" >
                <li>
                All Roots
                <?php echo $htmlTree; ?>
                </li>
            </ul>
            <div class="pi_default_tree"  data-drag="dragmap_tree" >
                <div id="chart_<?php echo $options['id']; ?>" class="orgChart"></div>
            </div>
        <?php
        // We retrieve the contents of the buffer.
        $_content_html = ob_get_contents ();
        // We clean the buffer.
        ob_clean ();
        // We close the buffer.
        ob_end_flush ();

        return $this->renderScript($_content_js, $_content_html, 'cmf/orgchartpage/default/');
    }


    /**
     *render chart with a click event.
     *
     * @param string $htmlTree
     * @param array  $options
     *
     * @access private
     * @return string
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     */
    protected function renderbyclickAction($htmlTree, $options = null)
    {
        // Options management
        if (!isset($options['id']) || empty($options['id'])) {
            throw ExtensionException::optionValueNotSpecified('id', __CLASS__);
        }

        // We open the buffer.
        ob_start ();
        ?>
                jQuery(document).ready(function() {
                    jQuery.fx.interval = 0.1;
                    $("<?php echo $options['id']; ?>").click(function(e){
                            if ($('#pi_block-boxes').is(':hidden')){
                                $("div[data-block^='pi_menuleft']").toggle(300);
                                setTimeout(function(){
                                    $('.jOrgChart').addClass("animated");
                                },300);
                            } else {
                                setTimeout(function(){
                                    $('.jOrgChart').removeClass("animated");
                                },300);
                                setTimeout(function(){
                                    $("div[data-block^='pi_menuleft']").toggle(500);
                                },2500);
                            }
                    });

                    $("[data-drag^='dragmap_']").draggable();

                    $("#org").jOrgChart({
                        chartElement : '#pi_treeChart',
                        chartClass : 'jOrgChart',
                        dragAndDrop  : true
                    });

                });
        <?php
        // We retrieve the contents of the buffer.
        $_content_js = ob_get_contents ();
        // We clean the buffer.
        ob_clean ();
        // We close the buffer.
        ob_end_flush ();


        // We open the buffer.
        ob_start ();
        ?>
            <ul id="org" style="display:none">
                <li>
                   All Roots
                   <?php echo $htmlTree; ?>
                </li>
            </ul>
            <div id="pi_block-boxes-cadre" class="pi_menuleft" data-block="pi_menuleft" >&nbsp;</div>
            <div id="pi_block-box"  data-drag="dragmap_tree" >
                <div id="pi_block-boxes" class="pi_menuleft" data-block="pi_menuleft" >
                    <div id="pi_treeChart"></div>
                </div>
            </div>
        <?php
        // We retrieve the contents of the buffer.
        $_content_html = ob_get_contents ();
        // We clean the buffer.
        ob_clean ();
        // We close the buffer.
        ob_end_flush ();

        return $this->renderScript($_content_js, $_content_html, 'cmf/orgchartpage/renderbyclick/');
    }

    /**
     * Define page Org html with ul/li balises.
     *
     * <code>
     *         // appel sans cache
     *        {% set options_chartpage = {
     *                'action':'renderByClick',
     *                'id': '.org-chart-page',
     *                'menu': 'page' }
     *        %}
     *        {{ renderJquery('MENU', 'org-chart-page', options_chartpage )|raw }}
     *
     *        // appel avec cache
     *        {% set options_chartpage = {
     *                "action":"renderByClick",
     *                "id":".org-chart-page",
     *                "menu":"page"}
     *        %}
     *        {{ getService('pi_app_admin.manager.tree').run('organigram', 'Rubrique~org-chart-page', app.session.getLocale(), options_chartpage)|raw }}
     * </code>
     *
     * @param array $options
     *
     * @access public
     * @return string
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     */
    public function pageMenu($options = null)
    {
        $PageManager = $this->container->get('pi_app_admin.manager.page');
        $htmlTree = "";
        if ($PageManager instanceof PiPageManager) {
            $htmlTree = $PageManager->getChildrenHierarchyRub();
            $htmlTree = $PageManager->setTreeWithPages($htmlTree);
            $htmlTree = $PageManager->setHomePage($htmlTree);

            return $htmlTree;
        }
        throw ExtensionException::serviceUndefined('PiPageManager');
    }

    /**
     * Define page Org html with ul/li balises.
     *
     * <code>
     *  {% set options_chartpage = {
     *       'entity':'PiAppGedmoBundle:Menu',
     *       'category':'Menuwrapper',
     *       'action':'renderDefault',
     *       'menu': 'organigram',
     *       'fields':{
     *                  '0':{'content':'title', 'class':'pi_tree_desc'}
     *              },
     *       'id':'orga' } %}
     *  {{ renderJquery('MENU', 'org-chart-page', options_chartpage )|raw }}
     * <code>
     *
     * @param array $options
     *
     * @access public
     * @return string
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     */
    public function organigramMenu($options = null)
    {
        // Options management
        if (!isset($options['entity']) || empty($options['entity'])) {
            throw ExtensionException::optionValueNotSpecified('entity', __CLASS__);
        }
        if (!isset($options['category'])) {
            throw ExtensionException::optionValueNotSpecified('category', __CLASS__);
        }
        if (!isset($options['locale'])) {
            $locale = $this->container->get('request_stack')->getCurrentRequest()->getLocale();
        } else {
            $locale = $options['locale'];
        }
        $TreeManager = $this->container->get('pi_app_admin.manager.tree');
        if ($TreeManager instanceof PiTreeManager) {
            return $TreeManager->defaultOrganigram($locale, $options['entity'], $options['category'], $options);
        }
        throw ExtensionException::serviceUndefined('PiTreeManager');
    }
}
