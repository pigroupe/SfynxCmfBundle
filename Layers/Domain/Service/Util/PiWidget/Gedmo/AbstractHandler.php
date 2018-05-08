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

use Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Generalisation\AbstractWidgetHandler;

/**
 * Gedmo Widget plugin
 *
 * @subpackage Widget
 * @package Gedmo
 * @abstract
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
abstract class AbstractHandler extends AbstractWidgetHandler
{
    const actionsList = [
        'organigram' => 'MENU:',
        'slider' => 'SLIDER:'
    ];

    /**
     * checks if the controller  and the action are in the container.
     *
     * @param string $controller
     * @access protected
     * @return BooleanType
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-03-11
     */        
    protected function isAvailableAction($controller)
    {
        $values     = explode(':', $controller);
        $entity     = $values[0] . ':' . str_replace('\\\\', '\\', $values[1]);
        $method     = strtolower($values[2]);
        $Lists      = $this::getAvailable();

        if ($entity && !isset($Lists[$entity])
            || ($entity && !in_array($method, $Lists[$entity]['method']))
            || ($this->action !== static::ACTION)
        ) {
           return false;
        }
        $this->entity = $entity;
        $this->setMethod($method);

        return true;
    }
    
    /**
     * Sets init.
     *
     * @access protected
     * @return void
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-03-11
     */    
    protected function init()
    {
        if (!empty($this->method)
            && in_array($this->action, self::actionsList)
        ) {
            $this->container->get('sfynx.tool.twig.extension.jquery')->initJquery(self::actionsList[$this->action] . $this->method);
        }
    }

    /**
     * Sets JS script.
     *
     * @param    array $options
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