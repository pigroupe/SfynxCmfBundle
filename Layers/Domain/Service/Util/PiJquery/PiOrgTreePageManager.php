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
use Sfynx\CmfBundle\Layers\Domain\Service\Manager\PiPageManager;

/**
 * Organigramm of all pages according to the section with DHTMLGOODIES Tree plugin.
 *
 * @subpackage   Cmf
 * @package    Jquery
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiOrgTreePageManager extends PiJqueryExtension
{
    /**
     * @var array
     * @static
     */
    static $menus = array('page');

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
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     */
    protected function init($options = null)
    {
        // css
        $this->container->get('sfynx.tool.twig.extension.layouthead')->addCssFile("bundles/sfynxtemplate/js/drag-drop-folder-tree/css/treepage.css");
        $this->container->get('sfynx.tool.twig.extension.layouthead')->addCssFile("bundles/sfynxtemplate/js/drag-drop-folder-tree/css/context-menu.css");
        $this->container->get('sfynx.tool.twig.extension.layouthead')->addCssFile("bundles/sfynxtemplate/js/drag-drop-folder-tree/css/drag-drop-folder-tree.css");

        // js
        $this->container->get('sfynx.tool.twig.extension.layouthead')->addJsFile("bundles/sfynxtemplate/js/drag-drop-folder-tree/js/ajax.js");
        $this->container->get('sfynx.tool.twig.extension.layouthead')->addJsFile("bundles/sfynxtemplate/js/drag-drop-folder-tree/js/context-menu.js");
        $this->container->get('sfynx.tool.twig.extension.layouthead')->addJsFile("bundles/sfynxtemplate/js/drag-drop-folder-tree/js/drag-drop-folder-tree.js");
    }

    /**
      * Set progress text for Progress flash dialog.
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
        if (!isset($options['id']) || empty($options['id']))
            throw ExtensionException::optionValueNotSpecified('id', __CLASS__);
        if (!isset($options['menu']) || empty($options['menu']) || (isset($options['menu']) && !in_array($options['menu'], self::$menus)) )
            throw ExtensionException::optionValueNotSpecified('id', __CLASS__);

        $method = strtolower($options['menu']) . "Menu";

        if (method_exists($this, $method))
            $htmlTree = $this->$method();
        else
            throw ExtensionException::MethodUnDefined($method);

        // We open the buffer.
        ob_start ();
        ?>
                //--------------------------------
                // Create tree
                //--------------------------------
                treeObj = new JSDragDropTree();
                treeObj.setTreeId('dhtmlgoodies_tree2');
                treeObj.setMaximumDepth(7);
                treeObj.setImageFolder("<?php echo $this->container->get('assets.packages')->getUrl("bundles/sfynxtemplate/js/drag-drop-folder-tree/images/"); ?>");
                treeObj.setMessageMaximumDepthReached('Maximum depth reached'); // If you want to show a message when maximum depth is reached, i.e. on drop.
                treeObj.initTree();
                treeObj.expandAll();

                jQuery(document).ready(function() {
                    $("<?php echo $options['id']; ?>").click(function(e){
                        var _width  = $('#pi_tree-boxes-cadre').width();

                        if ($('#pi_tree-boxes-cadre').is(':hidden')){
                            $("div[data-block^='pi_menuright']").attr("style", "display: block;margin-right: -"+(_width+1500)+"px;");
                            $("div[data-block^='pi_menuright']").delay(200).animate({marginRight: '0'}, 1500);
                        } else {
                            $("div[data-block^='pi_menuright']").fadeOut('slow', function(){
                                $(this).attr("style", "display: none;margin-right: -1500px;");
                            });
                        }
                    });
                });

                //--------------------------------
                // Save functions
                //--------------------------------
                var ajaxObjects = new Array();

                // Use something like this if you want to save data by Ajax.
                function saveMyTree()
                {
                        saveString = treeObj.getNodeOrders();
                        var ajaxIndex = ajaxObjects.length;
                        ajaxObjects[ajaxIndex] = new sack();
                        var url = 'saveNodes.php?saveString=' + saveString;
                        ajaxObjects[ajaxIndex].requestFile = url;    // Specifying which file to get
                        ajaxObjects[ajaxIndex].onCompletion = function() { saveComplete(ajaxIndex); } ;    // Specify function that will be executed after file has been found
                        ajaxObjects[ajaxIndex].runAJAX();        // Execute AJAX function

                }
                function saveComplete(index)
                {
                    alert(ajaxObjects[index].response);
                }
                // Call this function if you want to save it by a form.
                function saveMyTree_byForm()
                {
                    document.myForm.elements['saveString'].value = treeObj.getNodeOrders();
                    document.myForm.submit();
                }
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
            <div id="pi_tree-boxes-cadre" class="pi_menuright" data-block="pi_menuright" >
                <ul id="dhtmlgoodies_tree2" class="dhtmlgoodies_tree" >
                    <li id="node0" noDrag="true" noSiblings="true" noDelete="true" noRename="true">
                        <a href="#">Root node</a>
                        <?php echo $htmlTree; ?>
                    </li>
                </ul>
                <form>
                    <input type="button" onclick="saveMyTree()" value="Save">
                </Form>
            </div>
        <?php
        // We retrieve the contents of the buffer.
        $_content_html = ob_get_contents ();
        // We clean the buffer.
        ob_clean ();
        // We close the buffer.
        ob_end_flush ();

        return  $this->renderScript($_content_js, $_content_html, 'cmf/orgtreepage/');
    }

    /**
     * Define page Org html with ul/li balises.
     *
     * @param    array $options
     * @access public
     * @return string
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     */
    public function pageMenu()
    {
        $PageManager = $this->container->get('pi_app_admin.manager.page');

        if ($PageManager instanceof PiPageManager) {
            $htmlTree = $PageManager->getChildrenHierarchyRub();
            $htmlTree = $PageManager->setTreeWithPages($htmlTree);
            $htmlTree = $PageManager->setHomePage($htmlTree);
            $htmlTree = $PageManager->setNode($htmlTree);

            return $htmlTree;
        }
        throw ExtensionException::serviceUndefined('PiPageManager');
    }
}
