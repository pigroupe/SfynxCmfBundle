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
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\ExtensionException;

/**
 * Gedmo Widget plugin
 *
 * @subpackage Widget
 * @package Gedmo
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SliderHandler extends AbstractHandler implements HandlerWidgetInterface
{
    /** @var string */
    const ACTION = 'slider';

    /**
     * {@inheritdoc}
     */
    public static function getAvailable()
    {
        return self::getAvailableByType('WIDGET_SLIDER');
    }

    /**
     * Sets the render of the slider action.
     *
     * <code>
     *    <?xml version="1.0"?>
     *    <config>
     *        <widgets>
     *            <cachable>true</cachable>
     *            <css/>
     *            <gedmo>
     *                <controller>PiAppGedmoBundle:Slider:slide-default</controller>
     *                <params>
     *                  <category>new</category>
     *                  <template>slide.html.twig</template>
     *                  <slider>
     *                        <action>renderDefault</action>
     *                        <menu>entity</menu>
     *                        <class>slide-class</class>
     *                        <id>flexslider</id>
     *                        <boucle_array>false</boucle_array>
     *                        <orderby_date></orderby_date>
     *                        <orderby_position>ASC</orderby_position>
     *                        <MaxResults>4</MaxResults>
     *                        <query_function>getAllAdherents</query_function>
     *                        <searchFields>
     *                           <nameField>field1</nameField>
     *                           <valueField>value1</valueField>
     *                        </searchFields>
     *                        <searchFields>
     *                           <nameField>field2</nameField>
     *                           <valueField>value2</valueField>
     *                        </searchFields>
     *                        <params>
     *                            <animation>slide</animation>
     *                            <slideDirection>horizontal</slideDirection>
     *                            <slideshow>true</slideshow>
     *                            <slideToStart>0</slideToStart>
     *                            <redirection>false</redirection>
     *                            <slideshowSpeed>6000</slideshowSpeed>
     *                            <animationDuration>800</animationDuration>
     *                            <directionNav>true</directionNav>
     *                            <pauseOnAction>true</pauseOnAction>
     *                            <pausePlay>true</pausePlay>
     *                            <controlNav>true</controlNav>
     *                        </params>
     *                  </slider>
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
        $lang      = $options['widget-lang'];
        // if the configXml field of the widget isn't configured correctly.
        try {
            $xmlConfig    = new \Zend_Config_Xml($this->getConfigXml());
        } catch (\Exception $e) {
            return "  \n";
        }    
        // if the gedmo widget is defined correctly as an "organigram"
        if (($this->action == "slider")
            && $xmlConfig->widgets->get('gedmo')
            && $xmlConfig->widgets->gedmo->get('controller')
            && $xmlConfig->widgets->gedmo->get('params')
        ) {
            $controller    = $xmlConfig->widgets->gedmo->controller;
            if ($this->isAvailableAction($controller)) {
                $category = "";
                $template = "";
                if ($xmlConfig->widgets->gedmo->params->get('category')) {
                    $category = $xmlConfig->widgets->gedmo->params->category;
                }
                if ($xmlConfig->widgets->gedmo->params->get('template')) {
                    $template = $xmlConfig->widgets->gedmo->params->template;
                }
                if ($xmlConfig->widgets->gedmo->params->get('slider')) {
                    $params = $xmlConfig->widgets->gedmo->params->slider->toArray();
                    $params = array_merge($params, $this->convertParams($options));
                    $params['entity']    = $this->entity;
                    $params['category']  = $category;
                    $params['template']  = $template;
//                    $params['widget-id']        = $options['widget-id'];
//                    $params['widget-lifetime']  = $options['widget-lifetime'];
//                    $params['widget-cacheable'] = ((int) $options['widget-cacheable']) ? true : false;
//                    $params['widget-update']    = $options['widget-update'];
//                    $params['widget-public']    = $options['widget-public'];
//                    $params['widget-ajax']      = ((int) $options['widget-ajax']) ? true : false;
//                    $params['widget-sluggify']  = ((int) $options['widget-sluggify']) ? true : false;
//                    $params['cachable']         = ((int) $options['widget-cachetemplating']) ? true : false;

                    if ($xmlConfig->widgets->gedmo->params->get('cachable')) {
                        $params['cachable'] = ($xmlConfig->widgets->gedmo->params->cachable === 'true') ? true : false;
                    }
                    if ($xmlConfig->widgets->gedmo->params->slider->get('params')) {
                        $params['params'] = $xmlConfig->widgets->gedmo->params->slider->params->toArray();
                    }                                        
                    if (!isset($params['action']) || empty($params['action'])) {
                        $params['action']   = 'renderDefault';
                    }
                    if (!isset($params['menu']) || empty($params['menu'])) {
                        $params['menu']     = 'entity';
                    }
                    if ($params['cachable']) {
                        return $this->renderWidget->renderCache('pi_app_admin.manager.slider', $this->action, $this->entity."~".$this->method."~".$category, $lang, $params);
                    }
                    return $this->renderWidget->renderService('pi_app_admin.manager.slider', $this->entity."~".$this->method."~".$category, $lang, $params);
                }
                throw ExtensionException::optionValueNotSpecified("params xmlConfig", __CLASS__);
            }
            throw ExtensionException::optionValueNotSpecified("controller configuration", __CLASS__);
        }
        throw ExtensionException::optionValueNotSpecified("gedmo", __CLASS__);
    }
 }