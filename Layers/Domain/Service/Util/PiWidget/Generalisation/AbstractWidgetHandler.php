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

use Sfynx\CmfBundle\Layers\Domain\Service\Twig\Extension\PiWidgetExtension;

/**
 * Content Widget plugin
 *
 * @subpackage Widget
 * @package Content
 * @abstract
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
abstract class AbstractWidgetHandler extends PiWidgetExtension implements HandlerWidgetInterface
{
    use TraitWidgetConvertParams;

    /**
     * Return list of available slider.
     *
     * @param string $type
     *
     * @return array
     * @access public
     * @static
     */
    public static function getAvailableByType($type)
    {
        if (isset($GLOBALS[$type])) {
            return $GLOBALS[$type];
        }
        return [];
    }

    /**
     * {@inheritdoc}
     */
    abstract public static function getAvailable();

    /**
     * {@inheritdoc}
     */
    abstract public function handler($options = null);
}
