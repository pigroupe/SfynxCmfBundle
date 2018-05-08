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
use Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Gedmo\Navigation\NavigationCrawler;
use Sfynx\CrawlerBundle\Crawler\Excepetion\ExceptionXmlCrawler;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\ExtensionException;

/**
 * Gedmo Widget plugin
 *
 * @subpackage Widget
 * @package Gedmo
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class NavigationHandler extends AbstractHandler implements HandlerWidgetInterface
{
    /** @var string */
    const ACTION = 'navigation';

    /**
     * {@inheritdoc}
     */
    public static function getAvailable()
    {
        return self::getAvailableByType('WIDGET_NAVIGATION');
    }

    /**
     * Sets the render of the Menu action.
     *
     * <code>
     *    <?xml version="1.0"?>
     *    <config>
     *        <widgets>
     *            <cachable>true</cachable>
     *            <css/>
     *            <gedmo>
     *                <controller>PiAppGedmoBundle:Menu:_navigation_default</controller>
     *                <params>
     *                    <category>Menuwrapper</category>
     *                    <node>6</node>
     *                    <enabledonly>true</enabledonly>
     *                    <navigation>
     *                       <separatorClass>separateur</separatorClass>
     *                       <separatorText>&ndash;</separatorText>
     *                       <separatorFirst>false</separatorFirst>
     *                       <separatorLast>false</separatorLast>
     *                       <ulClass>infoCaption</ulClass>
     *                       <liClass>menuContainer</liClass>
     *                       <counter>true</counter>                       
     *                       <routeActifMenu>
     *                           <liActiveClass>menuContainer_highlight</liActiveClass>
     *                           <liInactiveClass></liInactiveClass>
     *                           <aActiveClass>tblanc</aActiveClass>
     *                           <aInactiveClass>tnoir</aInactiveClass>
     *                           <enabledonly>true</enabledonly>
     *                       </routeActifMenu>
     *                       <lvlActifMenu>
     *                           <liActiveClass></liActiveClass>
     *                           <liInactiveClass></liInactiveClass>
     *                           <aActiveClass>tnoir</aActiveClass>
     *                           <aInactiveClass>tnoir</aInactiveClass>
     *                           <enabledonly>false</enabledonly>
     *                       </lvlActifMenu>
     *                    </navigation>
     *                </params>
     *            </gedmo>
     *        </widgets>
     *    </config>
     * </code>
     * 
     * {@inheritdoc}
     */
    public function handler($options = null)
    {
//        $lang       = $options['widget-lang'];
//        $params     = [];
//        // if the configXml field of the widget isn't configured correctly.
//        try {
//            $xmlConfig    = new \Zend_Config_Xml($this->getConfigXml());
//        } catch (\Exception $e) {
//            return "  \n";
//        }
//        // if the gedmo widget is defined correctly as a "navigation"
//        if ( ($this->action == "navigation")
//            && $xmlConfig->widgets->get('gedmo')
//            && $xmlConfig->widgets->gedmo->get('controller')
//            && $xmlConfig->widgets->gedmo->get('params')
//        ) {
//            $controller    = $xmlConfig->widgets->gedmo->controller;
//            if ($this->isAvailableAction($controller)) {
//                $params['category'] = "";
//                $params['entity'] = $this->entity;
//                $params['node'] = "";
//                $params['enabledonly'] = "true";
//                $params['template'] = "";
//                $params['query_function'] = null;
//                $params['separatorClass'] = "";
//                $params['separatorText'] = "";
//                $params['separatorFirst'] = "false";
//                $params['separatorLast'] = "false";
//                $params['ulClass'] = "";
//                $params['liClass'] = "";
//                $params['counter'] = "";
//                $params = array_merge($params, $this->convertParams($options));
////                $params['widget-id']        = $options['widget-id'];
////                $params['widget-lifetime']  = $options['widget-lifetime'];
////                $params['widget-cacheable'] = ((int) $options['widget-cacheable']) ? true : false;
////                $params['widget-update']    = $options['widget-update'];
////                $params['widget-public']    = $options['widget-public'];
////                $params['widget-ajax']      = ((int) $options['widget-ajax']) ? true : false;
////                $params['widget-sluggify']  = ((int) $options['widget-sluggify']) ? true : false;
////                $params['cachable']         = ((int) $options['widget-cachetemplating']) ? true : false;
//                if ($xmlConfig->widgets->gedmo->params->get('cachable')) {
//                    $params['cachable'] = ($xmlConfig->widgets->gedmo->params->cachable === 'true') ? true : false;
//                }
//                if ($xmlConfig->widgets->gedmo->params->get('category')) {
//                    $params['category'] = $xmlConfig->widgets->gedmo->params->category;
//                }
//                if ($xmlConfig->widgets->gedmo->params->get('node')) {
//                    $params['node'] = $xmlConfig->widgets->gedmo->params->node;
//                }
//                if ($xmlConfig->widgets->gedmo->params->get('enabledonly')) {
//                    $params['enabledonly'] = $xmlConfig->widgets->gedmo->params->enabledonly;
//                }
//                if ($xmlConfig->widgets->gedmo->params->get('template')) {
//                    $params['template'] = $xmlConfig->widgets->gedmo->params->template;
//                }
//                if ($xmlConfig->widgets->gedmo->params->get('navigation')) {
//                    if ($xmlConfig->widgets->gedmo->params->navigation->get('searchFields')) {
//                    	$params['searchFields'] = $xmlConfig->widgets->gedmo->params->navigation->searchFields->toArray();
//                    }
//                    if ($xmlConfig->widgets->gedmo->params->navigation->get('query_function')) {
//                    	$params['query_function'] = $xmlConfig->widgets->gedmo->params->navigation->query_function;
//                    }
//                    if ($xmlConfig->widgets->gedmo->params->navigation->get('separatorClass')) {
//                        $params['separatorClass'] = $xmlConfig->widgets->gedmo->params->navigation->separatorClass;
//                    }
//                    if ($xmlConfig->widgets->gedmo->params->navigation->get('separatorText')) {
//                        $params['separatorText'] = $xmlConfig->widgets->gedmo->params->navigation->separatorText;
//                    }
//                    if ($xmlConfig->widgets->gedmo->params->navigation->get('separatorFirst')) {
//                        $params['separatorFirst'] = $xmlConfig->widgets->gedmo->params->navigation->separatorFirst;
//                    }
//                    if ($xmlConfig->widgets->gedmo->params->navigation->get('separatorLast')) {
//                        $params['separatorLast'] = $xmlConfig->widgets->gedmo->params->navigation->separatorLast;
//                    }
//                    if ($xmlConfig->widgets->gedmo->params->navigation->get('ulClass')) {
//                        $params['ulClass'] = $xmlConfig->widgets->gedmo->params->navigation->ulClass;
//                    }
//                    if ($xmlConfig->widgets->gedmo->params->navigation->get('liClass')) {
//                        $params['liClass'] = $xmlConfig->widgets->gedmo->params->navigation->liClass;
//                    }
//                    if ($xmlConfig->widgets->gedmo->params->navigation->get('counter')) {
//                        $params['counter'] = $xmlConfig->widgets->gedmo->params->navigation->counter;
//                    }
//                    if ($xmlConfig->widgets->gedmo->params->navigation->get('routeActifMenu')) {
//                        $params['routeActifMenu'] = $xmlConfig->widgets->gedmo->params->navigation->routeActifMenu->toArray();
//                    }
//                    if ($xmlConfig->widgets->gedmo->params->navigation->get('lvlActifMenu')) {
//                        $params['lvlActifMenu'] = $xmlConfig->widgets->gedmo->params->navigation->lvlActifMenu->toArray();
//                    }
//                    $category = $params['category'];
//                    if ($params['cachable']) {
//                        return $this->renderWidget->renderCache('pi_app_admin.manager.tree', $this->action, "$this->entity~$this->method~$category", $lang, $params);
//                    }
//                    return $this->renderWidget->renderService('pi_app_admin.manager.tree', "$this->entity~$this->method~$category", $lang, $params);
//                    //return $this->renderJquery("MENU", "$this->entity~$this->method~$category", $lang, $params);
//                }
//                throw ExtensionException::optionValueNotSpecified("gedmo navigation", __CLASS__);
//            }
//            throw ExtensionException::optionValueNotSpecified("controller configuration", __CLASS__);
//        }
//        throw ExtensionException::optionValueNotSpecified("gedmo", __CLASS__);

        $objCrawler = new NavigationCrawler($this->getConfigXml(), $options);
        if (!$xmlConfig = $objCrawler->getSimpleXml()) {
            throw ExceptionXmlCrawler::xmlNoValidated('gedmo', $objCrawler->getErrors());
        }
        if ($this->isAvailableAction($xmlConfig->widgets->gedmo->controller)) {
            $category = $xmlConfig->widgets->gedmo->params->category;
            $lang     = $options['widget-lang'];
            $id       = "$this->entity~$this->method~$category";
            $params   = $objCrawler->getDataInArray(['entity' => $this->entity]);

            if ($params['config']['widgets']['cachable']) {
                return $this->renderWidget->renderCache('pi_app_admin.manager.tree', $this->action, $id, $lang, $params);
            }
            return $this->renderWidget->renderService('pi_app_admin.manager.tree', $id, $lang, $params);
        }
        throw ExtensionException::optionValueNotSpecified('gedmo', __CLASS__);
    }
}