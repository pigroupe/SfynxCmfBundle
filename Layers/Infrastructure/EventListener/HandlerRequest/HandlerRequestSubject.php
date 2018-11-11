<?php
namespace Sfynx\CmfBundle\Layers\Infrastructure\EventListener\HandlerRequest;

use SplSubject;
use SplObserver;

use Sfynx\ToolBundle\Builder\RouteTranslatorFactoryInterface;
use Sfynx\CacheBundle\Manager\Generalisation\CacheInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Sfynx\CmfBundle\Layers\Infrastructure\EventListener\HandlerRequest\Observer\HomepageRedirection;
use Sfynx\CmfBundle\Layers\Infrastructure\EventListener\HandlerRequest\Observer\SeoRedirection;
use Sfynx\CmfBundle\Layers\Infrastructure\EventListener\HandlerRequest\Observer\ScopeRedirection;

/**
 * Class HandlerRequestSubject.
 * Register the mobile/desktop format.
 *
 * @category   EventListener
 * @package    Handler
 * @subpackage Request
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       https://github.com/pigroupe/cmf-sfynx/blob/master/web/COPYING.txt
 * @since      2014-07-18
 */
class HandlerRequestSubject implements SplSubject
{
    const OBSERVER_HOMEPAGE = 0;
    const OBSERVER_SEO      = 1;
    const OBSERVER_SCOPE    = 2;

    /**
     * List of concrete handlers that can be built using this factory.
     * @var string[]
     */
    protected static $handlerList = [
        self::OBSERVER_HOMEPAGE => HomepageRedirection::class,
        self::OBSERVER_SEO      => SeoRedirection::class,
        self::OBSERVER_SCOPE    => ScopeRedirection::class,
    ];

    /** @var \SplObserver[] */
    protected $observers = [];
    /** @var RouteTranslatorFactoryInterface */
    public $route;
    /** @var CacheInterface */
    public $cache;
    /** @var EventDispatcherInterface */
    public $dispatcher;
    /** @var ContainerInterface */
    public $container;
    /** @var  Request */
    public $request;
    /** @var array */
    public $param;

    /**
     * Constructor.
     *
     * @param RouteTranslatorFactoryInterface $route
     * @param CacheInterface $cache
     * @param EventDispatcherInterface $dispatcher
     * @param $templating
     * @param ContainerInterface $container
     *
     * @access public
     * @return void
     */
    public function __construct(
        RouteTranslatorFactoryInterface $route,
        CacheInterface $cache,
        EventDispatcherInterface $dispatcher,
        $templating,
        ContainerInterface $container
    ) {
        $this->route = $route;
        $this->cache = $cache;
        $this->dispatcher = $dispatcher;
        $this->templating = $templating;
        $this->container = $container;
    }

    /**
     * Invoked to modify the controller that should be executed.
     *
     * @param GetResponseEvent $event The event
     *
     * @return null
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }
        $this->request = $event->getRequest();

        $isResponse = $this->notify();
        if ($isResponse instanceof Response) {
            $event->setResponse($isResponse);
        }
    }

    /**
     * Sets parameter template values.
     *
     * @access protected
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function setParams(array $option)
    {
        $this->param = (object) $option;
    }

    /**
     * {@inheritDoc}
     */
    public function attach(SplObserver $observer)
    {
        $key = array_search($observer,$this->observers, true);
        if (!$key) {
            $this->observers[] = $observer;
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function detach(SplObserver $observer)
    {
        $key = array_search($observer,$this->observers, true);
        if ($key) {
            unset($this->observers[$key]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function notify()
    {
        $this->observers = array_merge(self::$handlerList, $this->observers);
        foreach ($this->observers as $observer) {
            if (is_object($observer)) {
                $isResponse = $observer->update($this);
            } else {
                $isResponse = (new $observer())->update($this);
            }
            if ($isResponse instanceof Response) {
                return $isResponse;
            }
        }
    }
}
