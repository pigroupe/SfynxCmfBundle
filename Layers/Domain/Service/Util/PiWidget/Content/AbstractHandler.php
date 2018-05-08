<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage Widget
 * @package Content
 * @abstract
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-02-10
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Content;

use Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Generalisation\AbstractWidgetHandler;

/**
 * Content Widget plugin
 *
 * @subpackage Widget
 * @package Content
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
abstract class AbstractHandler extends AbstractWidgetHandler
{
    const actionsList = ['jqext'];

    /**
     * checks if the jquery container and the jquery service exist.
     *
     * @param string    $JQcontainer
     * @param string    $JQservice
     * @access protected
     * @return BooleanType
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function isAvailableJqueryExtension($JQcontainer, $JQservice)
    {
        if ( isset($GLOBALS['JQUERY'][$JQcontainer][$JQservice])
            && $this->container->has($GLOBALS['JQUERY'][$JQcontainer][$JQservice])
        ) {
            return true;
        }
        return false;
    }

    /**
     * Sets init.
     *
     * @access protected
     * @return void
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function init()
    {
        if (!empty($this->method)
            && in_array($this->action, self::actionsList)
        ) {
            $this->container->get('sfynx.tool.twig.extension.jquery')->initJquery($this->method);
        }
    }

    /**
     * Sets JS script.
     *
     * @param array $options
     * @access public
     * @return void
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function scriptJs($options = null) {
        return '';
    }

    /**
     * Sets Css script.
     *
     * @param array $options
     * @access public
     * @return void
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function scriptCss($options = null) {
        return '';
    }

    /**
     * Sets Editor script.
     *
     * @param array $options
     * @access public
     * @return void
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function scriptEditor($options = null) {
        return '';
    }
}
