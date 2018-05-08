<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Admin_Managers
 * @package    Manager
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-06-13
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Domain\Service\Manager;

use Sfynx\CmfBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\PiJqextManagerBuilderInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of the jquery Extension manager
 *
 * @subpackage   Admin_Managers
 * @package    Manager
 * 
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiJqextManager extends PiCoreManager implements PiJqextManagerBuilderInterface 
{    
    /**
     * Constructor.
     *
     * @param RequestInterface $request
     * @param ContainerInterface $container
     */
    public function __construct(
        RequestInterface $request,
        ContainerInterface $container
    ) {
        parent::__construct($request, $container);
    }

    /**
     * Call the slider render source method.
     *
     * @param string $id
     * @param string $lang
     * @param string $params
     * @return string
     * @access    public
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-04-25
     */
    public function renderSource($id, $lang = '', $params = null)
    {
        str_replace('~', '~', $id, $count);
        
        if ($count == 1) {
            list($JQcontainer, $JQservice) = explode('~', $this->_Decode($id));
        } else {
            throw new \InvalidArgumentException("you have not configure correctly the attibute id");
        }
        
        if (!is_array($params)) {
            $params = $this->paramsDecode($params);
        } else {
            $this->recursive_map($params);
        }
        
        $params['locale']    = $lang;
        
        if (isset($GLOBALS['JQUERY'][$JQcontainer][$JQservice])
            && $this->container->has($GLOBALS['JQUERY'][$JQcontainer][$JQservice])
        ) {
            return $this->container->get('sfynx.tool.twig.extension.jquery')->FactoryFunction($JQcontainer, $JQservice, $params);
        } else {
            throw new \InvalidArgumentException("you have not configure correctly the attibute id");
        }
    }    
}