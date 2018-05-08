<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage Widget
 * @package Gedmo
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-03-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Gedmo;

use Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Generalisation\HandlerWidgetInterface;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\ExtensionException;

/**
 * Gedmo Widget plugin
 *
 * @subpackage Widget
 * @package Gedmo
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class OrganigramHandler extends AbstractHandler implements HandlerWidgetInterface
{
    /** @var string */
    const ACTION = 'organigram';

    /**
     * {@inheritdoc}
     */
    public static function getAvailable()
    {
        return self::getAvailableByType('WIDGET_ORGANIGRAM');
    }

    /**
     * Sets the render of the organigram action.
     *
     * <code>
     *  Pour appeler un organigramme sur un arbre.
     *    <?xml version="1.0"?>
     *    <config>
     *        <widgets>
     *            <cachable>true</cachable>
     *            <css/>
     *            <gedmo>
     *                <controller>PiAppGedmoBundle:Organigram:org-chart-page</controller>
     *                <params>
     *                    <category>BO</category>
     *                    <node>3</node>
     *                    <organigram>
     *                        <params>
     *                              <action>renderDefault</action>
     *                            <menu>organigram</menu>
     *                            <id>orga</id>
     *                        </params>
     *                        <fields>
     *                            <field>
     *                                <content>title</content>
     *                                <class>pi_tree_desc</class>
     *                            </field>
     *                            <field>
     *                                <content>descriptif</content>
     *                                <class></class>
     *                            </field>
     *                         </fields>
     *                    </organigram>
     *                </params>
     *            </gedmo>
     *        </widgets>
     *    </config>
     *
     *
     *  Pour appeler un arbre semantique.
     *    <?xml version="1.0"?>
     *    <config>
     *        <widgets>
     *            <cachable>false</cachable>
     *            <gedmo>
     *                <controller>PiAppGedmoBundle:Organigram:org-tree-semantique</controller>
     *                <params>
     *                    <enabledonly>true</enabledonly>
     *                    <category>Quand le déménagament doit-il avoir lieu ?</category>
     *                    <template>organigram-semantique.html.twig</template>
     *                    <organigram>
     *                        <params>
     *                            <action>renderDefault</action>
     *                            <menu>tree</menu>
     *                            <id>orga</id>
     *                        </params>
     *                    </organigram>
     *                </params>
     *            </gedmo>
     *        </widgets>
     *        <advanced>
     *            <roles>
     *                <role>ROLE_VISITOR</role>
     *                <role>ROLE_USER</role>
     *                <role>ROLE_ADMIN</role>
     *                <role>ROLE_SUPER_ADMIN</role>
     *            </roles>
     *        </advanced>
     *    </config>
     *
     *
     *  Pour appeler un breadcrumb sur un arbre.
     *    <?xml version="1.0"?>
     *    <config>
     *        <widgets>
     *            <cachable>true</cachable>
     *            <gedmo>
     *                <controller>PiAppGedmoBundle:Menu:org-tree-breadcrumb</controller>
     *                <params>
     *                    <node>3</node>
     *                    <template>organigram-breadcrumb.html.twig</template>
     *                    <organigram>
     *                        <params>
     *                            <action>renderDefault</action>
     *                            <menu>breadcrumb</menu>
     *                        </params>
     *                    </organigram>
     *                </params>
     *            </gedmo>
     *        </widgets>
     *    </config>     
     *
     * </code>
     * 
     * {@inheritdoc}
     */
    public function handler($options = null)
    {
        $lang       = $options['widget-lang'];
        $params     = [];
        // if the configXml field of the widget isn't configured correctly.
        try {
            $xmlConfig    = new \Zend_Config_Xml($this->getConfigXml());
        } catch (\Exception $e) {
            return "  \n";
        }    
        // if the gedmo widget is defined correctly as an "organigram"
        if ( ($this->action == "organigram")
            && $xmlConfig->widgets->get('gedmo')
            && $xmlConfig->widgets->gedmo->get('controller')
            && $xmlConfig->widgets->gedmo->get('params')
        ) {
            $controller    = $xmlConfig->widgets->gedmo->controller;            
            if ($this->isAvailableAction($controller)) {
                $category = $params['category'] = "";
                $params['entity'] = $this->entity;
                $params['node'] = "";
                $params['enabledonly'] = "true";
                $params['template'] = "";
                $params = array_merge($params, $this->convertParams($options));
//                $params['widget-id']        = $options['widget-id'];
//                $params['widget-lifetime']  = $options['widget-lifetime'];
//                $params['widget-cacheable'] = ((int) $options['widget-cacheable']) ? true : false;
//                $params['widget-update']    = $options['widget-update'];
//                $params['widget-public']    = $options['widget-public'];
//                $params['widget-ajax']      = ((int) $options['widget-ajax']) ? true : false;
//                $params['widget-sluggify']  = ((int) $options['widget-sluggify']) ? true : false;
//                $params['cachable']         = ((int) $options['widget-cachetemplating']) ? true : false;
                if ($xmlConfig->widgets->gedmo->params->get('cachable')) {
                    $params['cachable'] = ($xmlConfig->widgets->gedmo->params->cachable === 'true') ? true : false;
                }
                if ($xmlConfig->widgets->gedmo->params->get('category')) {
                    $category = $params['category'] = $xmlConfig->widgets->gedmo->params->category;
                }
                if ($xmlConfig->widgets->gedmo->params->get('node')) {
                    $params['node'] = $xmlConfig->widgets->gedmo->params->node;
                }
                if ($xmlConfig->widgets->gedmo->params->get('enabledonly')) {
                    $params['enabledonly'] = $xmlConfig->widgets->gedmo->params->enabledonly;
                }
                if ($xmlConfig->widgets->gedmo->params->get('template')) {
                    $params['template'] = $xmlConfig->widgets->gedmo->params->template;
                }
                if ($xmlConfig->widgets->gedmo->params->get('organigram')) {
                    if ($xmlConfig->widgets->gedmo->params->organigram->get('searchFields')) {
                    	$params['searchFields'] = $xmlConfig->widgets->gedmo->params->organigram->searchFields->toArray();
                    }
                    $params['query_function'] = null;
                    if ($xmlConfig->widgets->gedmo->params->organigram->get('query_function')) {
                    	$params['query_function'] = $xmlConfig->widgets->gedmo->params->organigram->query_function;
                    }
                    if ($xmlConfig->widgets->gedmo->params->organigram->get('params')) {
                        $params = array_merge($params, $xmlConfig->widgets->gedmo->params->organigram->params->toArray());
                    }
                    if ($xmlConfig->widgets->gedmo->params->organigram->get('fields') && $xmlConfig->widgets->gedmo->params->organigram->fields->get('field')) {
                        $params['fields'] = $xmlConfig->widgets->gedmo->params->organigram->fields->field->toArray();
                    }
                    if ($params['cachable']) {
                        return $this->renderWidget->renderCache('pi_app_admin.manager.tree', $this->action, "$this->entity~$this->method~$category", $lang, $params);
                    }
                    return $this->renderWidget->renderJquery("MENU", "$this->entity~$this->method~$category", $lang, $params);
                }
                throw ExtensionException::optionValueNotSpecified("params xmlConfig", __CLASS__);
            }
            throw ExtensionException::optionValueNotSpecified("controller configuration", __CLASS__);
        }
        throw ExtensionException::optionValueNotSpecified("gedmo", __CLASS__);
    }
}