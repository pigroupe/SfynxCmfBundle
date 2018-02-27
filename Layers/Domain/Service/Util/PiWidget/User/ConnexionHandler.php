<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage Widget
 * @package User
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-12-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\User;

use Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Generalisation\HandlerWidgetInterface;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\ExtensionException;

/**
 * Connexion Widget plugin
 *
 * @subpackage Widget
 * @package User
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ConnexionHandler extends AbstractHandler implements HandlerWidgetInterface
{
    /** @var string */
    const ACTION = 'connexion';

    /**
     * {@inheritdoc}
     */
    public static function getAvailable()
    {
        return null;
    }

    /**
     * Sets the render of the connexion action.
     *
     * <code>
     *   <?xml version="1.0"?>
     *   <config>
     *         <widgets>
     *            <cachable>true</cachable>
     *            <css/>
     *             <user>
     *                 <controller>SfynxAuthBundle:User:_connexion_default</controller>
     *                 <params>
     *                     <template>SfynxSmoothnessBundle:Login\\Security:connexion-ajax.html.twig</template>
     *                     <referer_redirection>true</referer_redirection>
     *                 </params>
     *             </user>
     *         </widgets>
     *   </config>
     *  </code>
     * 
     * <code>  
     *  {% set widget_service_params = {"template":"SfynxSmoothnessBundle:Login@@@@@@@@Security:connexion-ajax.html.twig"} %} 
     *  {{ getService('pi_app_admin.manager.authentication').renderSource('SfynxAuthBundle:User~_connexion_default', 'fr_FR', widget_service_params)|raw }}
     * </code>
     *  
     * <code>
     *   <?xml version="1.0"?>
     *   <config>
     *         <widgets>
     *             <user>
     *                 <controller>SfynxAuthBundle:User:_reset_default</controller>
     *                 <params>
     *                     <template>SfynxSmoothnessBundle:Login\\Resetting:reset_content.html.twig</template>
     *                     <path_url_redirection>page_route_name_reset</url_redirection>
     *                 </params>
     *             </user>
     *         </widgets>
     *   </config>
     *  </code>
     *
     * <code>
     *  {% set widget_service_params = {"template":"SfynxSmoothnessBundle:Login@@@@@@@@Resetting:reset_content.html.twig", "url_redirection": path_url('page_lamelee_menuwrapper_monespace', {'locale':locale})~'#profil'} %} 
     *  {{ getService('pi_app_admin.manager.authentication').renderSource('SfynxAuthBundle:User~_reset_default', 'fr_FR', widget_service_params)|raw }}
     * </code>
     *    
     * {@inheritdoc}
     */
    public function handler($options = null)
    {
        $lang = $options['widget-lang'];
        // if the configXml field of the widget isn't configured correctly.
        try {
            $xmlConfig = new \Zend_Config_Xml($this->getConfigXml());
        } catch (\Exception $e) {
            return "  \n";
        }        
        // if the gedmo widget is defined correctly as a "lucene"
        if (($this->action == "connexion")
            && $xmlConfig->widgets->get('user')
        ) {
            $controller    = $xmlConfig->widgets->user->controller;        
            if ($this->isAvailableAction($controller)
                && $xmlConfig->widgets->user->get('params')
            ) {
                $params = array_merge($xmlConfig->widgets->user->params->toArray(), $this->convertParams($options));
//                $params['widget-id']        = $options['widget-id'];
//                $params['widget-lifetime']  = $options['widget-lifetime'];
//                $params['widget-cacheable'] = ((int) $options['widget-cacheable']) ? true : false;
//                $params['widget-update']    = $options['widget-update'];
//                $params['widget-public']    = $options['widget-public'];
//                $params['widget-ajax']      = ((int) $options['widget-ajax']) ? true : false;
//                $params['widget-sluggify']  = ((int) $options['widget-sluggify']) ? true : false;
                
                return $this->renderService('pi_app_admin.manager.authentication', "$this->entity~$this->method", $lang, $params);
            }
            throw ExtensionException::optionValueNotSpecified("gedmo controller", __CLASS__);
        }
        throw ExtensionException::optionValueNotSpecified("content", __CLASS__);
    }
}