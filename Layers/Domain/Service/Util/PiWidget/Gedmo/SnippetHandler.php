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
class SnippetHandler extends AbstractHandler implements HandlerWidgetInterface
{
    /** @var string */
    const ACTION = 'snippet';

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
     *                <id>1</id>
     *                <snippet>true</snippet>
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
        // if the gedmo widget is defined correctly as a "listener"
        if (($this->action == "snippet")
            && $xmlConfig->widgets->get('gedmo')
            && $xmlConfig->widgets->gedmo->get('snippet')
            && $xmlConfig->widgets->gedmo->get('id')
            && $xmlConfig->widgets->gedmo->snippet
        ) {
            $idWidget = intval($xmlConfig->widgets->gedmo->id);
            try {
                //return " {{ getService('pi_app_admin.manager.widget').exec(".$idWidget.", '$lang')|raw }} \n";
                return $this->container->get('pi_app_admin.manager.widget')->exec($idWidget, $lang);
            } catch (\Exception $e) {
                return "the gedmo snippet doesn't exist";
            }
        }
        throw ExtensionException::optionValueNotSpecified("gedmo", __CLASS__);
    }    
}