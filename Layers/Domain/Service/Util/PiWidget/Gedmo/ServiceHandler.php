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
class ServiceHandler extends AbstractHandler implements HandlerWidgetInterface
{
    /** @var string */
    const ACTION = 'service';

    /**
     * {@inheritdoc}
     */
    public static function getAvailable()
    {
        return null;
    }

    /**
     * Sets the render of the snippet action.
     *
     * <code>
     *    <?xml version="1.0"?>
     *    <config>
     *        <widgets>
     *            <cachable>true</cachable>
     *            <css/>
     *            <gedmo>
     *                <service>my-service</service>
     *                <method>MyMethod</method>
     *                <params>
     *                    <id></id>
     *                    <category></category>
     *                    <MaxResults>5</MaxResults>
     *                    <template>_tmp_list_homepage.html.twig</template>
     *                    <order>DESC</order>
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
        $lang      = $options['widget-lang'];
        // if the configXml field of the widget isn't configured correctly.
        try {
            $xmlConfig    = new \Zend_Config_Xml($this->getConfigXml());
        } catch (\Exception $e) {
            return "  \n";
        }
        // if the gedmo widget is defined correctly as a "service"
        if (($this->action == "service")
            && $xmlConfig->widgets->get("gedmo")
            && $xmlConfig->widgets->gedmo->get('service')
            && $xmlConfig->widgets->gedmo->get('method')
            && $xmlConfig->widgets->gedmo->get('params')
        ) {
            $idService = intval($xmlConfig->widgets->gedmo->service);
            $method = $xmlConfig->widgets->gedmo->method;
            $params = array_merge($xmlConfig->widgets->gedmo->params->toArray(), $this->convertParams($options));
            try {
                //return " {{ getService('pi_app_admin.manager.widget').exec(".$idWidget.", '$lang')|raw }} \n";
                return $this->container->get($idService)->$method($params);
            } catch (\Exception $e) {
                return "the $idService service doesn't exist";
            }
        }
        throw ExtensionException::optionValueNotSpecified("gedmo", __CLASS__);
    }    
}