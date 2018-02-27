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
namespace Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Generalisation;

use Sfynx\CmfBundle\Layers\Domain\Entity\TranslationWidget;

/**
 * Interface HandlerWidgetInterface
 * @subpackage Widget
 * @package Gedmo
 * @since 2012-03-11
 */
interface HandlerWidgetInterface
{
    /**
     * Return list of available listener.
     *
     * @return array
     * @access public
     * @static
     * @since 2012-03-11
     */
    public static function getAvailable();

    /**
     * @param array $options
     * @access public
     * @return string
     */
    public function handler($options = null);

    /**
     * Sets the id widget.
     *
     * @param int $id id widget
     * @return void
     * @access public
     */
    public function setId($id);

    /**
     * Sets the ConfigXml widget.
     *
     * @param string $configXml        configXml widget
     * @return void
     * @access public
     */
    public function setConfigXml($configXml);

    /**
     * Gets the Widget manager service
     *
     * @return \Sfynx\CmfBundle\Layers\Domain\Service\Manager\PiWidgetManager
     * @access protected
     */
    public function getWidgetManager();

    /**
     * Sets the Widget translation.
     *
     * @param TranslationWidget $widgetTranslation
     * @return PiWidgetExtension
     * @access public
     */
    public function setTranslationWidget(TranslationWidget $widgetTranslation);
}
