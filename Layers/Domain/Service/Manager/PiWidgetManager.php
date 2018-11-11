<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Admin_Managers
 * @package    Widget
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-23
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Domain\Service\Manager;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

use Sfynx\CmfBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\PiWidgetManagerBuilderInterface;
use Sfynx\CmfBundle\Layers\Domain\Service\Manager\Generalisation\PiCoreManager;
use Sfynx\CmfBundle\Layers\Domain\Service\Twig\Extension\PiWidgetExtension;
use Sfynx\CmfBundle\Layers\Domain\Entity\Widget;
use Sfynx\CmfBundle\Layers\Domain\Entity\TranslationWidget;
use Sfynx\CrawlerBundle\Crawler\XmlCrawler;
use Sfynx\CrawlerBundle\Crawler\XmlCrawlerTransformer;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;


/**
 * Description of the Widget manager
 *
 * @category   Sfynx\CmfBundle\Layers
 * @package    Domain
 * @subpackage Service\Manager
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiWidgetManager extends PiCoreManager implements PiWidgetManagerBuilderInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var PiWidgetExtension
     */
    protected $extensionWidget;

    /**
     * Constructor.
     *
     * @param RequestInterface $request
     * @param LoggerInterface $logger
     * @param ContainerInterface $container
     * @param PiWidgetExtension $WidgetExtension
     */
    public function __construct(
        RequestInterface $request,
        LoggerInterface $logger,
        ContainerInterface $container,
        PiWidgetExtension $WidgetExtension
    ) {
        $this->logger = $logger;
        $this->extensionWidget = $WidgetExtension;

        parent::__construct($request, $container);
    }

    /**
     * Returns the render source of a widget.
     *
     * @param int         $id        id widget
     * @param string     $lang    language
     *
     * @return string    widget content
     * @access    public
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-02-15
     */
    public function exec($id, $lang = "")
    {
        $this->setLanguage($lang);
        // we get the current Widget.
        $widget     = $this->getRepository('Widget')->findOneById($id);
        // we set the current result
        $this->setCurrentWidget($widget);
        // we return the render (cache or not)
        return $this->render($this->language);
    }

    /**
     * Returns the render of the current widget.
     *
     * @param string $lang
     *
     * @return string
     * @access    public
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-01-23
     */
    public function render($lang = '')
    {
        $this->setLanguage($lang);
        if ($this->getCurrentWidget()) {
            $widget = $this->getCurrentWidget();
        } else {
            throw new \InvalidArgumentException("you don't have set the current widget !");
        }
        //     Initialize response
        $response = $this->getResponseByIdAndType('widget', $widget->getId());
        // we get the translation of the current widget in terms of the lang value.
        // $widgetTrans        = $this->getTranslationByWidgetId($widget->getId(), $this->language);
        // Handle 404
        // We don't show the widget if :
        // * the widget doesn't exist.
        // * The widget doesn't have a translation set.
        if (!$widget || !$this->isWidgetSupported($widget)) {
            $transWidgetError     = $this->getRepository('translationWidget')->getTranslationByParams(1, 'content', 'error', $this->language);
            if (!$transWidgetError) {
                throw new \InvalidArgumentException("We haven't set in the data fixtures the error widget message in the $this->language locale !");
            }
            $response->setStatusCode(404);
            // We set the Etag value
            $id          = $transWidgetError->getId();
            $this->setEtag("transwidget:$id:$this->language");
            // create a Response with a Last-Modified header
            $response    = $this->configureCache($transWidgetError, $response);
        } else {
            // We set the Etag value
            $id          = $widget->getId();
            $this->setEtag("widget:$id:$this->language");
            // create a Response with a Last-Modified header
            $response    = $this->configureCache($widget, $response);
        }
        // Check that the Response is not modified for the given Request
        if ($response->isNotModified($this->container->get('request_stack')->getCurrentRequest())) {
            // return the 304 Response immediately
            return $response;
        } else {
            // if the widget has translation OR if the widget calls a snippet
            $response = $this->container->get('pi_app_admin.caching')->renderResponse($this->Etag, [], $response);
            if ( $widget && $this->isWidgetSupported($widget) ) {
                // We set the reponse
                $this->setResponse($widget, $response);
            }
            // we don't send the header but the content only.
            return $response->getContent();
        }
    }

    /**
     * Returns the render source of one widget.
     *
     * @param string $id
     * @param string $lang
     * @param array  $params
     *
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since  2014-07-21
     */
    public function renderSource($id, $lang = '', $params = null)
    {
        $this->setLanguage($lang);
        // we get the translation of the current page in terms of the lang value.
        $this->getWidgetById($id);
        $container   = $this->getCurrentWidget()->getPlugin();
        $NameAction  = $this->getCurrentWidget()->getAction();
        $id          = $this->getCurrentWidget()->getId();
        $cssClass    = $this->container->get('sfynx.tool.string_manager')->slugify($this->getCurrentWidget()->getConfigCssClass());
        $lifetime       = $this->getCurrentWidget()->getLifetime();
        $cacheable      = strval($this->getCurrentWidget()->getCacheable());
        $update         = $this->getCurrentWidget()->getUpdatedAt()->getTimestamp();
        $public         = strval($this->getCurrentWidget()->getPublic());
        $cachetemplating = strval($this->getCurrentWidget()->getCacheTemplating());
        $sluggify       = strval($this->getCurrentWidget()->getSluggify());
        $ajax           = strval($this->getCurrentWidget()->getAjax());
        $is_secure     = $this->getCurrentWidget()->getSecure();
        $heritage     = $this->getCurrentWidget()->getHeritage();

//        $configureXml    = $this->container->get('sfynx.tool.string_manager')->filtreString($this->getCurrentWidget()->getConfigXml());
//        $options = array(
//            'widget-id' => $id
//        );
//        $source = $this->extensionWidget->FactoryFunction(strtoupper($container), strtolower($NameAction), $options);

        // get secure value
        $if_script    = "";
        $endif_script = "";
        if ( $is_secure && !(null === $heritage) && (count($heritage) > 0) ) {
            $heritages_info = array_merge($heritage, $this->container->get('sfynx.auth.role.factory')->getNoAuthorizeRoles($heritage));
            if ( !(null === $heritages_info) ) {
                $if_script      = $heritages_info['twig_if'];
                $endif_script   = $heritages_info['twig_endif'];
            }
        }
        $source  = $if_script;
        if (!empty($cssClass)) {
            $source .= " <div class=\"{$cssClass}\"> \n";
        } else {
            $source .= " <div> \n";
        }
        $source .= "     {% set options = {'widget-id': '$id', 'widget-lang': '$this->language', 'widget-lifetime': '$lifetime', 'widget-cacheable': '$cacheable', 'widget-update': '$update', 'widget-public': '$public', 'widget-cachetemplating': '$cachetemplating', 'widget-ajax': '$ajax', 'widget-sluggify': '$sluggify'} %} \n";
        $source .= "     {{ renderWidget('".strtoupper($container)."', '".strtolower($NameAction)."', options )|raw }} \n";
        $source .= " </div> \n";
        $source .= $endif_script;

        return $source;
    }

    /**
     * Sets js and css script of the widget.
     *
     * @return void
     * @access    public
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-02-16
     */
    public function setScript()
    {
        $container  = strtoupper($this->getCurrentWidget()->getPlugin());
        $NameAction = strtolower($this->getCurrentWidget()->getAction());
        // If the widget is a "gedmo snippet"
        if (($container == 'CONTENT')
                && ($NameAction == 'snippet')
        ) {
            // if the configXml field of the widget is configured correctly.
            try {
                $xmlConfig    = new \Zend_Config_Xml($this->getCurrentWidget()->getConfigXml());
                if ($xmlConfig->widgets->get('content')){
                    $snippet_widget = $this->getWidgetById($xmlConfig->widgets->content->id);
                    $container      = strtoupper($snippet_widget->getPlugin());
                    $NameAction     = strtolower($snippet_widget->getAction());
                }
            } catch (\Exception $e) {
            }
        }
        // If the widget is a "gedmo snippet"
        elseif (($container == 'GEDMO')
                && ($NameAction == 'snippet')
        ) {
            // if the configXml field of the widget is configured correctly.
            try {
                $xmlConfig    = new \Zend_Config_Xml($this->getCurrentWidget()->getConfigXml());
                if ($xmlConfig->widgets->get('gedmo')){
                    $snippet_widget = $this->getWidgetById($xmlConfig->widgets->gedmo->id);
                    $container      = strtoupper($snippet_widget->getPlugin());
                    $NameAction     = strtolower($snippet_widget->getAction());
                }
            } catch (\Exception $e) {
            }
        }
        $this->script['js'][$container.$NameAction]  = $this->extensionWidget
                ->ScriptJsFunction($container, $NameAction);
        $this->script['css'][$container.$NameAction] = $this->extensionWidget
                ->ScriptCssFunction($container, $NameAction);
    }

    /**
     * Sets init the widget.
     *
     * @return string
     * @access    public
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-02-16
     */
    public function setInit()
    {
        $container  = strtoupper($this->getCurrentWidget()->getPlugin());
        $NameAction = strtolower($this->getCurrentWidget()->getAction());
        $method     = ":";
        $xmlConfig  = $this->getCurrentWidget()->getConfigXml();
        // if the configXml field of the widget isn't configured correctly.
        try {
            //$xmlConfig = new \Zend_Config_Xml($xmlConfig);
            $xmlConfig = (new XmlCrawler($xmlConfig))->getSimpleXml();
        } catch (\Exception $e) {
            return "  \n";
        }
        // we add all css files.
        if (($xmlConfig instanceof \SimpleXMLElement)
            && $xmlConfig->widgets->css->getName()
        ) {
            if (is_object($xmlConfig->widgets->css)) {
                $all_css = (new XmlCrawlerTransformer())->xml2dataBuilder($xmlConfig->widgets->css);
                $this->script['init'][$container.$NameAction.$method.'css'] =  "{% initWidget('css:".json_encode($all_css, JSON_UNESCAPED_UNICODE)."') %}";
            } elseif (is_string($xmlConfig->widgets->css)) {
                $this->script['init'][$container.$NameAction.$method.'css'] =  "{% initWidget('css:".json_encode([$xmlConfig->widgets->css], JSON_UNESCAPED_UNICODE)."') %}";
            }
        }
        // we add all js files.
        if (($xmlConfig instanceof \SimpleXMLElement)
            && $xmlConfig->widgets->js->getName()
        ) {
            if (is_object($xmlConfig->widgets->js)) {
                $all_js = (new XmlCrawlerTransformer())->xml2dataBuilder($xmlConfig->widgets->js);
                $this->script['init'][$container.$NameAction.$method.'js'] =  "{% initWidget('js:".json_encode($all_js, JSON_UNESCAPED_UNICODE)."') %}";
            } elseif (is_string($xmlConfig->widgets->js)) {
                $this->script['init'][$container.$NameAction.$method.'js'] =  "{% initWidget('js:".json_encode([$xmlConfig->widgets->js], JSON_UNESCAPED_UNICODE)."') %}";
            }
        }
        // we apply init methods of the applyed service.
        if (($xmlConfig instanceof \SimpleXMLElement)
            && $xmlConfig->widgets->gedmo->getName()
            && $xmlConfig->widgets->gedmo->controller->getName()
        ) {
            $controller    = $xmlConfig->widgets->gedmo->controller;
            $values     = explode(':', $controller);
            $entity     = strtolower($values[1]);
            $method    .= strtolower($values[2]);
            $this->script['init'][$container.$NameAction.$method] =  "{% initWidget('". $container . ":" . $NameAction . $method ."') %}";
        } elseif ( ($xmlConfig instanceof \SimpleXMLElement)
            && $xmlConfig->widgets->get('content')
            && $xmlConfig->widgets->content->get('controller')
        ) {
            $controller    = $xmlConfig->widgets->content->controller;
            str_replace(':', ':', $controller, $count);
            if ($count == 1) {
                $this->script['init'][$container.$NameAction.$method] =  "{% initWidget('". $container . ":" . $NameAction . ":" . $controller ."') %}";
            }
        } elseif (($xmlConfig instanceof \SimpleXMLElement)
            && $xmlConfig->widgets->get('search')
            && $xmlConfig->widgets->search->get('controller')
        ) {
            $controller    = $xmlConfig->widgets->search->controller;
            str_replace(':', ':', $controller, $count);
            if ($count == 1) {
                $this->script['init'][$container.$NameAction.$method] =  "{% initWidget('". $container . ":" . $NameAction . ":" . $controller ."') %}";
            }
        } else {
            $this->script['init'][$container.$NameAction.$method] =  "{% initWidget('". $container . ":" . $NameAction . $method ."') %}";
        }
    }

    /**
     * Sets widget translations.
     *
     * @param Widget $widget A widget entity
     *
     * @return array|TranslationWidget
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-02-13
     */
    protected function setWidgetTranslations(Widget $widget)
    {
        $all_translations = $widget->getTranslations();

        $this->translationsWidget[$widget->getId()] = null;
        if ($all_translations instanceof \Doctrine\ORM\PersistentCollection) {
            // records all translations
            foreach ($all_translations as $translation) {
                $this->translationsWidget[$widget->getId()][$translation->getLangCode()->getId()] = $translation;
            }
        }
        return $this->translationsWidget[$widget->getId()];
    }

    /**
     * Sets the response to one widget.
     *
     * @param Widget   $widget   A widget entity
     * @param Response $response The response instance
     *
     * @return void
     * @access private
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-31
     */
    protected function setResponse($widget, Response $response)
    {
        $this->responses['widget'][$widget->getId()] = $response;
    }

    /**
     * Returns the translation of a widget.
     *
     * @param int    $idwidget id widget
     * @param string $lang     lang vlue
     *
     * @return TranslationWidget
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-02-13
     */
    public function getTranslationByWidgetId($idwidget, $lang = '')
    {
        $this->setLanguage($lang);
        if (isset($this->translationsWidget[$idwidget]) && !empty($this->translationsWidget[$idwidget])) {
            if (!empty($this->language)
                && isset($this->translationsWidget[$idwidget][$this->language])
                && !empty($this->translationsWidget[$idwidget][$this->language])
            ) {
                $result = $this->translationsWidget[$idwidget][$this->language];
            } else {
                $result =  $this->translationsWidget[$idwidget];
            }
        } else {
            $result = $this->getRepository('TranslationWidget')->getTranslationById($idwidget, $this->language);
        }
        // we secure if the result is an array of translation object.
        if (is_array($result)) {
            $result = end($result);
        }
        // Initialize Locale
        if ($result instanceof TranslationWidget) {
            $this->setLanguage($result->getLangCode()->getId());
            // we set the result
            $this->setCurrentTransWidget($result);
        }

        return $result;
    }
}
