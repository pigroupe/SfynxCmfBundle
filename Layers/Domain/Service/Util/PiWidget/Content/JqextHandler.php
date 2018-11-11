<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage Widget
 * @package Content
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-02-10
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Content;

use Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Generalisation\HandlerWidgetInterface;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\ExtensionException;

/**
 * Content Widget plugin
 *
 * @subpackage Widget
 * @package Content
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class JqextHandler extends AbstractHandler implements HandlerWidgetInterface
{
    /** @var string */
    const ACTION = 'jqext';

    /**
     * {@inheritdoc}
     */
    public static function getAvailable()
    {
        $result = [
            'TWITTER:tweets_blog' => [
                'method' => ['rendertwitter', 'renderblog'],
                'rendertwitter'     => [
                    'edit' => 'admin_gedmo_sociallink_edit',
                ],
                'renderblog' => [
                    'edit' => 'admin_gedmo_sociallink',
                ],
            ],
        ];
        if (isset($GLOBALS['CONTENT_WIDGET_JQEXT'])) {
            return array_merge($result, $GLOBALS['CONTENT_WIDGET_JQEXT']);
        }
        return $result;
    }

    /**
     * Sets the render of the Jqext action.
     *
     * <code>
     *
     *      // Extending jQuery to insert and call tweets of a user name.
     *   <?xml version="1.0"?>
     *   <config>
     *         <widgets>
     *            <cachable>true</cachable>
     *            <css/>
     *             <content>
     *                 <controller>TWITTER:tweets_blog</controller>
     *                 <params>
     *                    <action>rendertwitter</action>
     *                    <twitter_id>novediaGroup</twitter_id>
     *                    <template>twitter.html.twig</template>
     *                 </params>
     *             </content>
     *         </widgets>
     *   </config>
     *
     *   // jquery extension to insert and call blog of an entity.
     *   <?xml version="1.0"?>
     *   <config>
     *         <widgets>
     *             <content>
     *                 <controller>TWITTER:tweets_blog</controller>
     *                 <params>
     *                     <cachable>false</cachable>
     *                    <action>renderblog</action>
     *                    <maxResults>15</maxResults>
     *                    <template>blog.html.twig</template>
     *                    <listenerentity>Sociallink</listenerentity>
     *                 </params>
     *             </content>
     *         </widgets>
     *   </config>
     *
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
        // if the gedmo widget is defined correctly as a "jqext"
        if (($this->action == "jqext")
            && $xmlConfig->widgets->get('content')
            && $xmlConfig->widgets->content->get('controller')
            && $xmlConfig->widgets->content->get('params')
        ) {
            $controller  = $xmlConfig->widgets->content->controller;
            $params      = $xmlConfig->widgets->content->params->toArray();
            $values      = explode(':', $controller);
            $JQcontainer = strtoupper($values[0]);
            $JQservice   = strtolower($values[1]);
            if ($this->isAvailableJqueryExtension($JQcontainer, $JQservice)) {
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
                    $params['cachable'] = ($xmlConfig->widgets->content->params->cachable === 'true') ? true : false;
                }
                if ($params['cachable']) {
                    return $this->renderWidget->renderCache('pi_app_admin.manager.jqext', $this->action, "$JQcontainer~$JQservice", $lang, $params);
                }
                return $this->renderWidget->renderJquery($JQcontainer, $JQservice, $lang, $params);
            }
        }
        throw ExtensionException::optionValueNotSpecified("content", __CLASS__);
   }
}
