<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Admin_Managers
 * @package    Widget
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2017-03-05
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Domain\Service\Manager\Widget\Generalisation;

/**
 * Description of the Widget render
 *
 * @subpackage   Admin_Managers
 * @package    Widget
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface RenderInterface
{
    /**
     * Sets parameter template values.
     *
     * @access protected
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function setParams(array $option);

    /**
     * Returns the render source of a tag by the twig cache service.
     *
     * @param string $tag
     * @param string $id
     * @param string $lang
     * @param array  $params
     *
     * @return string    extension twig result
     * @access public
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since  2012-04-19
     */
    public function renderCache($serviceName, $tag, $id, $lang, $params = null);

    /**
     * Returns the render source of a service manager.
     *
     * @param string $id
     * @param string $lang
     * @param array  $params
     *
     * @return string extension twig result
     * @access public
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since  2012-04-19
     */
    public function renderService($serviceName, $id, $lang, $params = null);

    /**
     * Returns the render source of a jquery extension.
     *
     * @param string    $JQcontainer
     * @param string    $id
     * @param string    $lang
     * @param array     $params
     *
     * @return string    extension twig result
     * @access    public
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-06-01
     */
    public function renderJquery($JQcontainer, $id, $lang, $params = null);
}
