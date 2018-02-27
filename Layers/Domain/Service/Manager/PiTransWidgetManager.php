<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Admin_Managers
 * @package    Manager
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-02-15
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Domain\Service\Manager;

use Sfynx\CmfBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\PiTransWidgetManagerBuilderInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use \Sfynx\CmfBundle\Layers\Domain\Entity\TranslationWidget;

/**
 * Description of the Translation Widget manager
 *
 * @subpackage   Admin_Managers
 * @package    Manager
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiTransWidgetManager extends PiCoreManager implements PiTransWidgetManagerBuilderInterface
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
     * Returns the render source of a translation widget.
     *
     * @param string $id
     * @param string $lang
     * @param array     $params
     * @return string
     * @access    public
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-02-15
     */
    public function renderSource($id, $lang = '', $params = null)
    {
        $widgetTrans = $this->getRepository('TranslationWidget')->find($id);

        if ( ($widgetTrans instanceof TranslationWidget)
            && $widgetTrans->getEnabled()) {
            return $widgetTrans->getContent();
        }
        return '&nbsp;';
    }
}
