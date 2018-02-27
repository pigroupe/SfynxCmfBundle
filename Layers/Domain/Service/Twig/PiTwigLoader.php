<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Admin_Twig
 * @package    Loader
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-03
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Domain\Service\Twig;

use Sfynx\CmfBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\PiPageManagerBuilderInterface;
use Sfynx\CmfBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\PiWidgetManagerBuilderInterface;
use Sfynx\CmfBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\PiTransWidgetManagerBuilderInterface;
use Sfynx\CmfBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\PiTreeManagerBuilderInterface;
use Sfynx\CmfBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\PiListenerManagerBuilderInterface;
use Sfynx\CmfBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\PiSliderManagerBuilderInterface;
use Sfynx\CmfBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\PiJqextManagerBuilderInterface;
use Sfynx\CmfBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\PiSearchLuceneManagerBuilderInterface;

/**
 * Loads a template from a repository.
 *
 * @subpackage   Admin_Twig
 * @package    Loader
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiTwigLoader implements \Twig_LoaderInterface
{
    const SOURCE_TYPE_PAGE = 'page';
    const SOURCE_TYPE_WIDGET = 'widget';
    const SOURCE_TYPE_TRANSWIDGET = 'transwidget';
    const SOURCE_TYPE_NAV = 'navigation';
    const SOURCE_TYPE_ORG = 'organigram';
    const SOURCE_TYPE_LISTENER = 'listener';
    const SOURCE_TYPE_SLIDER = 'slider';
    const SOURCE_TYPE_JQEXT = 'jqext';
    const SOURCE_TYPE_LUCENE = 'lucene';

    /**
     * List of concrete handlers that can be built using this factory.
     * @var string[]
     */
    protected static $sourcetypeList = [
        self::SOURCE_TYPE_PAGE => 'page_manager',
        self::SOURCE_TYPE_WIDGET => 'widget_manager',
        self::SOURCE_TYPE_TRANSWIDGET => 'transwidget_manager',
        self::SOURCE_TYPE_NAV => 'tree_manager',
        self::SOURCE_TYPE_ORG => 'tree_manager',
        self::SOURCE_TYPE_LISTENER => 'listener_manager',
        self::SOURCE_TYPE_SLIDER => 'slider_manager',
        self::SOURCE_TYPE_JQEXT => 'jqext_manager',
        self::SOURCE_TYPE_LUCENE => 'searchlucene_manager',
    ];

    /**
     * @var \Sfynx\CmfBundle\Layers\Domain\Service\Manager\PiPageManager
     */
    protected $page_manager = null;

    /**
     * @var \Sfynx\CmfBundle\Layers\Domain\Service\Manager\PiWidgetManager
     */
    protected $widget_manager = null;

    /**
     * @var \Twig_LoaderInterface
     */
    protected $fallback_loader = null;

    /**
     * PiTwigLoader constructor.
     * @param PiPageManagerBuilderInterface $page_manager
     * @param PiWidgetManagerBuilderInterface $widget_manager
     * @param PiTransWidgetManagerBuilderInterface $transwidget_manager
     * @param PiTreeManagerBuilderInterface $tree_manager
     * @param PiListenerManagerBuilderInterface $listener_manager
     * @param PiSliderManagerBuilderInterface $slider_manager
     * @param PiJqextManagerBuilderInterface $jqext_manager
     * @param PiSearchLuceneManagerBuilderInterface $searchlucene_manager
     * @param \Twig_LoaderInterface|null $loader
     */
    public function __construct(
                PiPageManagerBuilderInterface $page_manager,
                PiWidgetManagerBuilderInterface $widget_manager,
                PiTransWidgetManagerBuilderInterface $transwidget_manager,
                PiTreeManagerBuilderInterface $tree_manager,
                PiListenerManagerBuilderInterface $listener_manager,
                PiSliderManagerBuilderInterface $slider_manager,
                PiJqextManagerBuilderInterface $jqext_manager,
                PiSearchLuceneManagerBuilderInterface $searchlucene_manager,
                \Twig_LoaderInterface $loader = null)
    {
        $this->page_manager         = $page_manager;
        $this->widget_manager       = $widget_manager;
        $this->transwidget_manager  = $transwidget_manager;
        $this->tree_manager         = $tree_manager;
        $this->listener_manager     = $listener_manager;
        $this->slider_manager       = $slider_manager;
        $this->jqext_manager        = $jqext_manager;
        $this->searchlucene_manager = $searchlucene_manager;
        $this->loader               = $loader;
    }

    /**
     * Gets the source code of a translation page.
     *
     * @param  string $RenderResponseParam The param of the page/layout/widget to load
     *
     * @return string The template source code
     */
    public function getSourceContext($name)
    {
        $parsed_info = $this->page_manager->parseTemplateParam($name);
        if ($parsed_info === false) {
            return $this->loader->getSourceContext($name);
        }

        $source = '';
        list($type, $id, $lang, $params) = $parsed_info;
        if (!empty($id)
            && array_key_exists($type, self::$sourcetypeList)
        ) {
            $service = self::$sourcetypeList[$type];
            $source = $this->$service->renderSource($id, $lang, $params);
        }

        return new \Twig_Source($source, $name);
    }

    /**
     * Gets the cache key to use for the cache for a given template name.
     *
     * @param  string $name The name of the template to load
     *
     * @return string The cache key
     */
    public function getCacheKey($name)
    {
        if ($this->page_manager->parseTemplateParam($name) === false) {
            return $this->loader->getCacheKey($name);
        }
        return $name;
    }

    /**
     * Returns true if the template is still fresh.
     *
     * @param string    $name The template name
     * @param timestamp $time The last modification time of the cached template
     */
    public function isFresh($name, $time)
    {
        if ($this->page_manager->parseTemplateParam($name) === false) {
            return $this->loader->isFresh($name, $time);
        }
        return true; // isFresh is handled by cache invalidation
    }

    /**
     * Check if we have the source code of a template, given its name.
     *
     * @param string $name The name of the template to check if we can load
     *
     * @return bool If the template source code is handled by this loader or not
     */
    public function exists($name)
    {
//        return $this->loader->exists($name);
        return true;
    }
}
