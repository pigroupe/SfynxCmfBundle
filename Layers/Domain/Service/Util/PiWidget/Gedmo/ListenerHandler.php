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

use Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Generalisation\HandlerWidgetInterface;
use Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Gedmo\Listener\ListenerCrawler;
use Sfynx\CrawlerBundle\Crawler\Excepetion\ExceptionXmlCrawler;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\ExtensionException;

/**
 * Gedmo Widget plugin
 *
 * @subpackage Widget
 * @package Gedmo
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ListenerHandler extends AbstractHandler implements HandlerWidgetInterface
{
    /** @var string */
    const ACTION = 'listener';

    /**
     * {@inheritdoc}
     */
    public static function getAvailable()
    {
        return self::getAvailableByType('WIDGET_LISTENER');
    }

    /**
     * Sets the render of the Listener action.
     *
     * <code>
     *    <?xml version="1.0"?>
     *    <config>
     *        <widgets>
     *            <cachable>true</cachable>
     *            <css/>
     *            <gedmo>
     *                <controller>PiAppGedmoBundle:Activity:_template_list</controller>
     *                <params>
     *                    <id></id>
     *                    <category></category>
     *                    <MaxResults>5</MaxResults>
     *                    <template>_tmp_list_homepage.html.twig</template>
     *                    <order>DESC</order>
     *                </params>
     *            </gedmo>
     *        </widgets>
     *    </config>
     * </code>
     *
     * {@inheritdoc}
     */
    public function handler($options = null)
    {
        $objCrawler = new ListenerCrawler($this->getConfigXml(), $options);
        if (!$xmlConfig = $objCrawler->getSimpleXml()) {
            throw ExceptionXmlCrawler::xmlNoValidated('gedmo', $objCrawler->getErrors());
        }
        $id = $xmlConfig->widgets->gedmo->controller;

        if ($this->isAvailableAction($id)) {
            $params = $objCrawler->getDataInArray();
            $lang = $params['widget-lang'];

            if ($params['config']['widgets']['cachable']) {
                return $this->renderWidget->renderCache('pi_app_admin.manager.listener', $this->action, $id, $lang, $params);
            }
            return $this->renderWidget->renderService('pi_app_admin.manager.listener', $id, $lang, $params);
        }
        throw ExtensionException::optionValueNotSpecified('gedmo', __CLASS__);
    }
}