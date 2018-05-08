<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage Widget
 * @package Search
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-06-13
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Search;

use Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Generalisation\HandlerWidgetInterface;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\ExtensionException;

/**
 * Search Widget plugin
 *
 * @subpackage Widget
 * @package Search
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class LuceneHandler extends AbstractHandler implements HandlerWidgetInterface
{
    /** @var string */
    const ACTION = 'lucene';

    /**
     * {@inheritdoc}
     */
    public static function getAvailable()
    {
        return null;
    }

    /**
     * Sets the render of the lucene action.
     *
     * <code>
     *   <?xml version="1.0"?>
     *   <config>
     *         <widgets>
     *            <cachable>true</cachable>
     *            <css/>
     *             <search>
     *                 <controller>LUCENE:search-lucene</controller>
     *                 <params>
     *                    <template>searchlucene-result.html.twig</template>
     *                    <MaxResults></MaxResults>
     *                    <lucene>
     *                        <action>renderPage</action>
     *                        <menu>searchpage</menu>
     *                        <searchBool>true</searchBool>
     *                        <searchBoolType>AND</searchBoolType>
     *                        <searchByMotif>true</searchByMotif>
     *                        <setMinPrefixLength>0</setMinPrefixLength>
     *                        <getResultSetLimit>0</getResultSetLimit>
     *                        <searchFields>
     *                            <sortField>Contents</sortField>
     *                            <sortType>SORT_STRING</sortType>
     *                            <sortOrder>SORT_ASC</sortOrder>
     *                        </searchFields>
     *                        <searchFields>
     *                            <sortField>Key</sortField>
     *                            <sortType>SORT_NUMERIC</sortType>
     *                            <sortOrder>SORT_DESC</sortOrder>
     *                        </searchFields>
     *                  </lucene>
     *                 </params>
     *             </search>
     *         </widgets>
     *   </config>
     * </code>
     *
     * {@inheritdoc}
     */
    public function handler($options = null)
    {
        $lang       = $options['widget-lang'];
        // if the configXml field of the widget isn't configured correctly.
        try {
            $xmlConfig    = new \Zend_Config_Xml($this->getConfigXml());
        } catch (\Exception $e) {
            return "  \n";
        }        
        // if the gedmo widget is defined correctly as a "lucene"
        if ( ($this->action == "lucene")
            && $xmlConfig->widgets->get('search')
            && $xmlConfig->widgets->search->get('controller')
            && $xmlConfig->widgets->search->get('params')
        ) {
            $params['template'] = "";
            $params['MaxResults'] = 0;

            $controller    = $xmlConfig->widgets->search->controller;
            if ($xmlConfig->widgets->search->params->get('template')) {
                $params['template'] = $xmlConfig->widgets->search->params->template;
            }
            if ($xmlConfig->widgets->search->params->get('MaxResults')) {
                $params['MaxResults'] = $xmlConfig->widgets->search->params->MaxResults;
            }
            if ($xmlConfig->widgets->search->params->get('lucene')) {            
                   $params      = array_merge($params, $xmlConfig->widgets->search->params->lucene->toArray());            
                   $values      = explode(':', $controller);
                   $JQcontainer = strtoupper($values[0]);
                   $JQservice   = strtolower($values[1]);
//                    print_r($this->renderWidget->renderCache('pi_app_admin.manager.search_lucene', $this->action, "$JQcontainer~$JQservice", $lang, $params));
//                    krsort($params); // array_multisort
//                    print_r($params);exit;                  
                   if ($this->isAvailableJqueryExtension($JQcontainer, $JQservice)) {
                       $params = array_merge($params, $this->convertParams($options));
//                       $params['widget-id']        = $options['widget-id'];
//                       $params['widget-lifetime']  = $options['widget-lifetime'];
//                       $params['widget-cacheable'] = ((int) $options['widget-cacheable']) ? true : false;
//                       $params['widget-update']    = $options['widget-update'];
//                       $params['widget-public']    = $options['widget-public'];
//                       $params['widget-ajax']      = ((int) $options['widget-ajax']) ? true : false;
//                       $params['widget-sluggify']  = ((int) $options['widget-sluggify']) ? true : false;
//                       $params['cachable']         = ((int) $options['widget-cachetemplating']) ? true : false;
                       if ($xmlConfig->widgets->gedmo->params->get('cachable')) {
                       	$params['cachable'] = ($xmlConfig->widgets->search->params->cachable === 'true') ? true : false;
                       }                       
                       if ($params['cachable']) {
                           return $this->renderWidget->renderCache('pi_app_admin.manager.search_lucene', $this->action, "$JQcontainer~$JQservice", $lang, $params);
                       }
                       return $this->renderWidget->renderJquery($JQcontainer, $JQservice, $lang, $params);
                   }
            }
            throw ExtensionException::optionValueNotSpecified("gedmo navigation", __CLASS__);
        }
        throw ExtensionException::optionValueNotSpecified("content", __CLASS__);
    }
}