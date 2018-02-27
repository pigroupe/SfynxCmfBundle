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
namespace Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Generalisation;

trait TraitWidgetConvertParams {

    /**
     * Return twig parameters converted widget handlers.
     *
     * @param array $options
     *
     * @return array
     * @access public
     * @static
     */
    protected function convertParams($options = null)
    {
        if (null !== $options) {
            $params['widget-lang']      = $options['widget-lang'];
            $params['widget-id']        = $options['widget-id'];
            $params['widget-lifetime']  = $options['widget-lifetime'];
            $params['widget-cacheable'] = ((int)$options['widget-cacheable']) ? true : false;
            $params['widget-update']    = $options['widget-update'];
            $params['widget-public']    = $options['widget-public'];
            $params['widget-ajax']      = ((int)$options['widget-ajax']) ? true : false;
            $params['widget-sluggify']  = ((int)$options['widget-sluggify']) ? true : false;
            $params['config']['widgets']['cachable'] = ((int)$options['widget-cachetemplating']) ? true : false;

            return $params;
        }
        return [];
    }
}