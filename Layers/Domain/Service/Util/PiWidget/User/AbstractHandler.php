<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Admin
 * @package    Widget
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-12-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\User;

use Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Generalisation\AbstractWidgetHandler;

/**
 * User Widget plugin
 *
 * @subpackage   Admin
 * @package    Widget
 * @abstract
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
abstract class AbstractHandler extends AbstractWidgetHandler
{
    /**
     * Return list of available jqext.
     *
     * @return array
     * @access public
     * @static
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-07-23
     */
    public static function getAvailableConnexion()
    {
        return [
            'SfynxAuthBundle:User'    => [
                'method' => ['_connexion_default','_reset_default'],
            ],
        ];
    }    
    
    /**
     * checks if the controller  and the action are in the container.
     *
     * @param string    $controller
     * @access protected
     * @return BooleanType
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-03-11
     */        
    protected function isAvailableAction($controller)
    {
           $values     = explode(':', $controller);
           $entity     = $values[0] .":". $values[1];
           $method     = strtolower($values[2]);
           $getAvailable  = "getAvailable" . ucfirst($this->action);
           $Lists         = self::$getAvailable();

           if ( $entity
               && (!isset($Lists[$entity])
               || !in_array($method, $Lists[$entity]['method']))
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
     */
    protected function init() {}

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