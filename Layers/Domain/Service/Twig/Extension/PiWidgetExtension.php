<?php
namespace Sfynx\CmfBundle\Layers\Domain\Service\Twig\Extension;

use Sfynx\CmfBundle\Layers\Domain\Service\Manager\Widget\Generalisation\RenderInterface;
use Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Generalisation\HandlerWidgetInterface;

use Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Generalisation\TraitWidgetConfiguration;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\EntityException;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\ServiceException;
use Sfynx\CmfBundle\Layers\Domain\Entity\TranslationWidget;
use Sfynx\CmfBundle\Layers\Domain\Service\Twig\TokenParser\StyleSheetWidgetTokenParser;

/**
 * Widget Matrix used in twig
 *
 * @subpackage   Admin
 * @package    Extension
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-11
 */
class PiWidgetExtension extends \Twig_Extension
{
    use TraitWidgetConfiguration;

    /**
     * Content de rendu du script.
     *
     * @static
     * @var int
     */
    protected static $_content;

    /**
     * @var RenderInterface
     */
    protected $renderWidget;

    /**
     * @var ContainerInterface
     * @access  protected
     */
    protected $container;

    /**
     * @var \Sfynx\CmfBundle\Layers\Domain\Service\Manager\PiWidgetManager
     */
    protected $widgetManager;

    /**
     * @var \Sfynx\CmfBundle\Layers\Domain\Service\Manager\PiTransWidgetManager
     */
    protected $transWidgetManager;

    /**
     * @var \Sfynx\CmfBundle\Layers\Domain\Entity\TranslationWidget
     */
    protected $translationsWidget;

    /**
     * @var service widget extension manager called
     */
    protected $serviceWidget;

    /**
     * @var String Entity Name
     * @access  protected
     */
    protected $entity;

    /**
     * @var String Method Name
     * @access  protected
     */
    protected $method;

    /**
     * @var String Action Name
     * @access  protected
     */
    protected $action;

    /**
     * @var int    id widget value
     */
    protected $id;

    /**
     * @var string configXml widget value
     */
    protected $configXml;

    /**
     * @var string service name
     */
    private $service;

    /**
     * Return list of available widget plugins.
     *
     * @return array
     * @access public
     * @static
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2011-12-28
     */
    public static function getAvailableWidgetPlugins()
    {
        return [
            'content' =>'Content',
            'gedmo'   =>'Gedmo',
            'search'  =>'Search',
            'user'    =>'User',
        ];
    }

    /**
     * Constructor.
     *
     * @param RenderInterface $renderWidget
     * @param ContainerInterface $container
     * @param string $container Name of content
     * @param string $action Name of action
     */
    public function __construct(
        RenderInterface $renderWidget,
        ContainerInterface $containerService,
        $container = 'CONTENT',
        $action = 'text'
    ) {
        $this->renderWidget = $renderWidget;
        $this->container = $containerService;
        $this->action = $action;
    }

    /**
     * Sets the id widget.
     *
     * @param int $id id widget
     * @return void
     * @access public
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Gets the id widget.
     *
     * @return id widget value
     * @access public
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     * @access public
     * @final
     */
    final public function getName()
    {
        return 'pi_widget_extension';
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     * @access public
     * @final
     */
    final public function getAction()
    {
        return $this->action;
    }

    /**
     * Sets the method
     *
     * @return string The extension name
     * @access public
     * @final
     */
    final public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * Sets the ConfigXml widget.
     *
     * @param string $configXml        configXml widget
     * @return void
     * @access public
     */
    public function setConfigXml($configXml)
    {
        $this->configXml = $configXml;
        return $this;
    }

    /**
     * Gets the ConfigXml widget.
     *
     * @return ConfigXml widget value
     * @access public
     */
    public function getConfigXml()
    {
        return $this->configXml;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * <code>
     *  {% set options = {'widget-id': 1} %}
     *  {{ renderWidget('CONTENT', 'text', options )|raw }}
     * </code>
     *
     * @return array An array of functions
     * @access public
     * @final
     */
    final public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('renderCache', [$this, 'renderCacheFunction']),
            new \Twig_SimpleFunction('renderJs', [$this, 'ScriptJsFunction']),
            new \Twig_SimpleFunction('renderCss', [$this, 'ScriptCssFunction']),
            new \Twig_SimpleFunction('renderWidget', [$this, 'FactoryFunction'])
        ];
    }

    /**
     * Returns the token parsers
     *
     * <code>
     *     {%  initWidget 'CONTENT:text' %} to execute the init method of the service.
     * </code>
     *
     * @return string The extension name
     * @access public
     * @final
     */
    final public function getTokenParsers()
    {
        return [
            new StyleSheetWidgetTokenParser(PiWidgetExtension::class),
        ];
    }

    /**
     * Put result content in cache with ttl.
     */
    final public function renderCacheFunction($key, $ttl, $serviceName, $method, $id, $lang, $params)
    {
        $dossier = $this->container->get('pi_app_admin.manager.page')->createCacheWidgetRepository();
        $this->container->get("sfynx.cache.filecache")->getClient()->setPath($dossier);
        $value = $this->container->get("sfynx.cache.filecache")->get($key);
        if (!$value) {
            $value = $this->container->get($serviceName)->$method($id, $lang, $params);
            $this->container->get("sfynx.cache.filecache")->getClient()->setPath($dossier); // IMPORTANT if in the method of the service the path is overwrite.
            // important : if ttl is equal to zero then the cache is infini
            $this->container->get("sfynx.cache.filecache")->set($key, $value, $ttl);
        }
        return $value;
    }

    /**
     *
     *
     * @param  string         $container            name of widget container.
     * @param  string         $actionName            name of action.
     * @param  array        $options            validator options.
     * @return service|null
     * @access public
     * @final
     */
    final public function ScriptJsFunction($container, $actionName, $options = null)
    {
        if ($this->isServiceSupported($container, $actionName)
            && method_exists($this->getServiceWidget(), 'scriptJs')
        ) {
            return $this->getServiceWidget()->runJs($options);
        }
        return null;
    }

    /**
     *
     *
     * @param string $container name of widget container
     * @param string $actionName name of action
     * @param array  $options validator options
     * @return service|null
     * @access public
     * @final
     */
    final public function ScriptCssFunction($container, $actionName, $options = null)
    {
        if ($this->isServiceSupported($container, $actionName)
            && method_exists($this->getServiceWidget(), 'scriptCss')
        ) {
            return $this->getServiceWidget()->runCss($options);
        }
        return null;
    }

    /**
     * Factory ! We check that the requested class is a valid service.
     *
     * @param  string $container   name of widget container.
     * @param  string $actionName  name of action.
     * @param  null|array $options validator options.
     *
     * @return service
     * @access public
     * @final
     */
    final public function FactoryFunction($container, $actionName, $options = null)
    {
        if ($this->isServiceSupported($container, $actionName)) {
            // Gestion des options
            if (!isset($options['widget-id']) || empty($options['widget-id'])) {
                throw ServiceException::optionValueNotSpecified('widget-id', __CLASS__);
            }
            if (!isset($options['widget-lang']) || empty($options['widget-lang'])) {
                throw ServiceException::optionValueNotSpecified('widget-lang', __CLASS__);
            }
            // we set params
            $this->setParams($options, false);
//            print_r($this->getServiceWidget()->getAction());
//            print_r($this->action);
//            print_r($this->service);
//            print_r($container);
//            print_r($actionName);
            return $this->getServiceWidget()->handler($options);
        }
        throw ServiceException::serviceNotSupported($actionName);
    }

    /**
     * Sets the service and the action names and return true if the service is supported.
     *
     * @param string $container name of widget container.
     * @param string $actionName name of action.
     *
     * @return boolean
     * @access private
     */
    protected function isServiceSupported($container, $actionName)
    {
        if (!isset($GLOBALS['WIDGET'][strtoupper($container)][strtolower($actionName)])) {
            throw ServiceException::serviceGlobaleUndefined(strtolower($actionName), 'WIDGET');
        } elseif (!$this->container->has($GLOBALS['WIDGET'][strtoupper($container)][strtolower($actionName)])) {
            throw ServiceException::serviceNotSupported($GLOBALS['WIDGET'][strtoupper($container)][strtolower($actionName)]);
        }
        $this->service   = $GLOBALS['WIDGET'][strtoupper($container)][strtolower($actionName)];
        $this->action    = strtolower($actionName);

        return true;
    }

    /**
     * Sets the Widget translation and the id of the util widget service manager called.
     *
     * @param array options
     * @param boolean $withTrans
     * @return void
     * @access protected
     */
    protected function setParams($options, $withTrans = false)
    {
        // we set the id widget.
        $this->getServiceWidget()->setId($options['widget-id']);
        // we get the widget manager
        $widgetManager  = $this->getServiceWidget()->getWidgetManager();
        // we get the widget entity
        $widget            = $this->getRepository()->findOneById($this->getServiceWidget()->getId(), 'Widget');
        // we set the current widget entity
        if ($widget instanceof \Sfynx\CmfBundle\Layers\Domain\Entity\Widget) {
            $widgetManager->setCurrentWidget($widget);
            $this->getServiceWidget()->setConfigXml($widget->getConfigXml());
        } else {
            throw EntityException::IdEntityUnDefined($this->getServiceWidget()->getId());
        }
        // we set the translation of the current widget
        if ($withTrans) {
            $widgetTranslation = $widgetManager->getTranslationByWidgetId($widget->getId(), $options['widget-lang']);
            $this->getServiceWidget()->setTranslationWidget($widgetTranslation);
        }
    }

    /**
     * Gets the widget service.
     *
     * @return HandlerWidgetInterface
     * @access protected
     */
    protected function getServiceWidget()
    {
        $this->setServiceWidget();
        return $this->serviceWidget;
    }

    /**
     * Sets the widget service.
     *
     * @return void
     * @access protected
     */
    protected function setServiceWidget()
    {
        if (!empty($this->service) && $this->container->has($this->service)) {
            $this->serviceWidget = $this->container->get($this->service);
            return $this;
        }
        throw ServiceException::serviceNotConfiguredCorrectly();
    }

    /**
     * Sets the Widget manager service.
     *
     * @return void
     * @access protected
     */
    protected function setWidgetManager()
    {
        $this->widgetManager = $this->container->get('pi_app_admin.manager.widget');
        return $this;
    }

    /**
     * Gets the Widget manager service
     *
     * @return \Sfynx\CmfBundle\Layers\Domain\Service\Manager\PiWidgetManager
     * @access protected
     */
    public function getWidgetManager()
    {
        if (empty($this->widgetManager)) {
            $this->setWidgetManager();
        }
        return $this->widgetManager;
    }

    /**
     * Sets the Translation Widget manager service.
     *
     * @return void
     * @access protected
     */
    protected function setTransWidgetManager()
    {
        $this->transWidgetManager = $this->container->get('pi_app_admin.manager.transwidget');
        return $this;
    }

    /**
     * Gets the Translation Widget manager service
     *
     * @return \Sfynx\CmfBundle\Layers\Domain\Service\Manager\PiTransWidgetManager
     * @access protected
     */
    protected function getTransWidgetManager()
    {
        if (empty($this->transWidgetManager)) {
            $this->setTransWidgetManager();
        }
        return $this->transWidgetManager;
    }

    /**
     * Gets the container instance.
     *
     * @return ContainerInterface
     * @access public
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Sets the repository service.
     *
     * @return void
     * @access protected
     */
    protected function setRepository()
    {
        $this->repository = $this->container->get('pi_app_admin.repository');
        return $this;
    }

    /**
     * Gets the repository service.
     *
     * @return ObjectRepository
     * @access protected
     */
    protected function getRepository()
    {
        if (empty($this->repository)) {
            $this->setRepository();
        }
        return $this->repository;
    }

    /**
     * Sets the Widget translation.
     *
     * @param TranslationWidget $widgetTranslation
     * @return PiWidgetExtension
     * @access public
     */
    public function setTranslationWidget(TranslationWidget $widgetTranslation)
    {
        if ($widgetTranslation instanceof TranslationWidget) {
            $this->translationsWidget = $widgetTranslation;
        }
        return $this;
    }

    /**
     * Gets the Widget translation.
     *
     * @return \Sfynx\CmfBundle\Layers\Domain\Entity\TranslationWidget
     * @access public
     */
    public function getTranslationWidget()
    {
        if ($this->translationsWidget instanceof TranslationWidget) {
            return $this->translationsWidget;
        }
        return false;
    }

    /**
     * execute the Widget service init method.
     *
     * @param  string $InfoService service information ex : "contenaireName:actionName"
     * @return void
     * @access public
     * @final
     */
    final public function initWidget($InfoService)
    {
        $method     = "";
        $infos      = explode(":", $InfoService);
        //
        if (count($infos) <=1) {
            throw ServiceException::serviceParameterUndefined($InfoService);
        }
        $container  = $infos[0];
        $actionName = $infos[1];
        //
        if (!in_array($container, array('css', 'js'))) {
            if (count($infos) == 3) {
                $method    = $infos[2];
            }
            if (count($infos) == 4) {
                $method    = $infos[2] . ":" . $infos[3];
            }
            if ($this->isServiceSupported($container, $actionName)){
                if (method_exists($this->getServiceWidget(), 'init')){
                    $this->getServiceWidget()->setMethod($method);
                    $this->getServiceWidget()->init();
                }
            }
        } else {
            if ($container == "css") {
                $all_css = json_decode($actionName);
                if (null !== $all_css) {
                    foreach ($all_css as $path_css) {
                        $this->container->get('sfynx.tool.twig.extension.layouthead')->addCssFile($path_css, 'append');
                    }
                }
            } elseif ($container == "js") {
                $all_js = json_decode($actionName);
                if (null !== $all_js) {
                    foreach ($all_js as $path_js) {
                        $this->container->get('sfynx.tool.twig.extension.layouthead')->addJsFile($path_js, 'append');
                    }
                }
            }
        }
    }

    /**
     * Call the render function of the child class called by service.
     *
     * @return string
     * @access public
     * @final
     */
    final public function runJs($options = null)
    {
        try{
            return $this->scriptJs($options);
        } catch (\Exception $e) {
            throw ServiceException::serviceRenderUndefined('WIDGET');
        }
    }
    public function scriptJs($options = null) {}

    /**
     * Call the render function of the child class called by service.
     *
     * @return string
     * @access    public
     * @final
     */
    final public function runCss($options = null)
    {
        try{
            return $this->scriptCss($options);
        } catch (\Exception $e) {
            throw ServiceException::serviceRenderUndefined('WIDGET');
        }
    }
    public function scriptCss($options = null) {}

    /**
     * Gets the language locale.
     *
     * @return \Symfony\Component\Locale\Locale
     * @access public
     */
    public function getLanguage()
    {
        return $this->container->get('request_stack')->getCurrentRequest()->getLocale();
    }

    /**
     * Returns the render source of a tag by the twig cache service.
     *
     * @param string    $tag
     * @param string    $id
     * @param string    $lang
     * @param array     $params
     *
     * @return string    extension twig result
     * @access    protected
     */
    protected function renderCache($serviceName, $tag, $id, $lang, $params = null)
    {
        return $this->renderWidget->renderCache($serviceName, $tag, $id, $lang, $params);
    }

    /**
     * Returns the render source of a service manager.
     *
     * @param string $id
     * @param string $lang
     * @param array $params
     *
     * @return string    extension twig result
     * @access protected
     */
    protected function renderService($serviceName, $id, $lang, $params = null)
    {
        return $this->renderWidget->renderService($serviceName, $id, $lang, $params);
    }

    /**
     * Returns the render source of a jquery extension.
     *
     * @param string    $JQcontainer
     * @param string    $id
     * @param string    $lang
     * @param array     $params
     *
     * @return string    extension twig result
     * @access    protected
     */
    protected function renderJquery($JQcontainer, $id, $lang, $params = null)
    {
        return $this->renderWidget->renderJquery($JQcontainer, $id, $lang, $params);
    }
}
