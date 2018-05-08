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
     *            <content>
     *                <id>1</id>
     *                <snippet>true</snippet>
     *            </content>
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
        // if the gedmo widget is defined correctly as a "snippet"
        if ( ($this->action == "snippet")
            && $xmlConfig->widgets->get('content')
            && $xmlConfig->widgets->content->get('snippet')
            && $xmlConfig->widgets->content->get('id')
            && $xmlConfig->widgets->content->snippet
        ) {
            $idWidget = $xmlConfig->widgets->content->id;
            try {
                $TranslationWidget = $this->getRepository()->getRepository('TranslationWidget')->getTranslationById((int) $idWidget, $lang);
                $params['widget-id']        = $options['widget-id'];
                $params['widget-lifetime']  = $options['widget-lifetime'];
                $params['widget-cacheable'] = ((int) $options['widget-cacheable']) ? true : false;
                $params['widget-update']    = $options['widget-update'];
                $params['widget-public']    = $options['widget-public'];
                $params['widget-ajax']      = ((int) $options['widget-ajax']) ? true : false;
                $params['widget-sluggify']  = ((int) $options['widget-sluggify']) ? true : false;

                return $this->renderWidget->renderCache('pi_app_admin.manager.transwidget', 'transwidget', $TranslationWidget->getId(), $lang, $params);
            } catch (\Exception $e) {
                return "the snippet doesn't exist";
            }
        }
        throw ExtensionException::optionValueNotSpecified("content", __CLASS__);
    }
}
