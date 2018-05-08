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

/**
 * Text Content Widget plugin
 *
 * @subpackage Widget
 * @package Content
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class TextHandler extends AbstractHandler implements HandlerWidgetInterface
{
    /** @var string */
    const ACTION = 'text';

    /**
     * {@inheritdoc}
     */
    public static function getAvailable()
    {
        return null;
    }

    /**
      * Sets the render of the text action.
      *
      * {@inheritdoc}
      */
    public function handler($options = null)
    {
        $params = $this->convertParams($options);
        $lang   = $params['widget-lang'];
//        $params['widget-id']        = $options['widget-id'];
//        $params['widget-lifetime']  = $options['widget-lifetime'];
//        $params['widget-cacheable'] = ((int) $options['widget-cacheable']) ? true : false;
//        $params['widget-update']    = $options['widget-update'];
//        $params['widget-public']    = $options['widget-public'];
//        $params['widget-ajax']      = ((int) $options['widget-ajax']) ? true : false;
//        $params['widget-sluggify']  = ((int) $options['widget-sluggify']) ? true : false;
        // if the gedmo widget is defined correctly as a "text"
        if ( ($this->action == "text" )
            && $this->getTranslationWidget()
        ) {
            return $this->renderWidget->renderCache('pi_app_admin.manager.transwidget', 'transwidget', $this->getTranslationWidget()->getId(), $lang, $params);
        }

        return " no translation widget setting : {{ getService('sfynx.tool.string_manager').random(8)|raw }} \n";
    }
}
