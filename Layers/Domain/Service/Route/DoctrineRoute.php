<?php
namespace Sfynx\CmfBundle\Layers\Domain\Service\Route;

use Sfynx\CmfBundle\Layers\Domain\Service\Route\DoctrineRouteInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;

/**
 * route cache management.
 *
 * @subpackage   Route
 * @package    Tool
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-02-27
 */
class DoctrineRoute implements DoctrineRouteInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     *
     * @var \Doctrine\Common\Cache\Cache
     */
    protected $cache;

    /**
     * Prime the cache when using {@see addRoute()} yes or no.
     *
     * @var bool
     */
    public $primeCache = true;

    /**
     * Constructor.
     *
     * @param bool
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container  = $container;
        $this->connection = $this->container->get('doctrine.dbal.default_connection');
        $this->em         = $this->container->get('doctrine.orm.default_entity_manager');
        $this->cache      = new ArrayCache;
    }

    /**
     * Add in the route collection all routes of all pages.
     *
     * @return \Symfony\Component\Routing\RouteCollection
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-02-27
     */
    public function addAllRoutePageCollections()
    {
        $all_route_values = $this->getAllRouteValues();
        if ((null === $all_route_values)){
            $this->parseRoutePages();
            $all_route_values = $this->getAllRouteValues();
        }

        return $all_route_values;
    }

    /**
     * Parse all routes of all pages and add them in the database and in the cache.
     *
     * @return \Symfony\Component\Routing\RouteCollection
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-02-27
     */
    public function parseRoutePages()
    {
        $all_routes = [];
        $results    = $this->connection->fetchAll("SELECT id,route,locales,defaults,requirements FROM pi_routing");
        foreach ($results as $key => $values) {
            $all_routes[ $values['route'] ]['id']           = $values['id'];
            $all_routes[ $values['route'] ]['locales']      = $values['locales'];
            $all_routes[ $values['route'] ]['defaults']     = $values['defaults'];
            $all_routes[ $values['route'] ]['requirements'] = $values['requirements'];
        }
//        print_r($all_routes);
        $all_pages = $this->em->getRepository('SfynxCmfBundle:Page')->getAllPageHtml()->getQuery()->getResult();
        foreach ($all_pages as $key => $page) {
            if (($page instanceof \Sfynx\CmfBundle\Layers\Domain\Entity\Page) && $page->getEnabled()) {
                if (!$page->getTranslations()->isEmpty()) {
                    $fieldEntity = null;
                    $route   = $page->getRouteName();
                    $locales = $this->container->get('pi_app_admin.manager.page')->getUrlByPage($page);
                    $defaults  = array('_controller'=>'SfynxCmfBundle:Frontend:page');
                    $requirements = array('_method'=>'GET|POST');
                    if (isset($all_routes[ $route ])) {
                        $fieldEntity = $all_routes[ $route ];
                    }
                    if (isset($all_routes[ $route ]) && !empty($all_routes[ $route ]['defaults'])) {
                        $defaults = json_decode($all_routes[ $route ]['defaults'], true);
                    }
                    if (isset($all_routes[ $route ]['requirements']) && !empty($all_routes[ $route ]['requirements'])) {
                        $requirements = json_decode($all_routes[ $route ]['requirements'], true);
                    }
                    if (
                        isset($GLOBALS['ROUTE']['SLUGGABLE'][ $route ])
                        && !empty($GLOBALS['ROUTE']['SLUGGABLE'][ $route ])
                        && isset($GLOBALS['ROUTE']['SLUGGABLE'][ $route ]['requirement'])
                    ){
                        $requirements = $GLOBALS['ROUTE']['SLUGGABLE'][ $route ]['requirement'];
                    }
                    $this->addRoute($fieldEntity, $route, $locales, $defaults, $requirements);
                   }
            }
        }
    }

    /**
     * Returns all route values of all pages which are saved in the doctrine cache.
     *
     * @return array
     * @access public
     */
    public function getAllRouteValues()
    {
        $all_route_names = $this->getAllRouteNames();
        if ((null !== $all_route_names)){
            $routes = [];
            foreach($all_route_names as $key => $route){
                $routes[] = $this->getRoute($route);
            }
            return $routes;
        }
        return null;
    }

    /**
     * Returns all route names of all pages which are saved in the doctrine cache otherwise in the database.
     *
     * @return array
     * @access public
     */
    public function getAllRouteNames()
    {
        $cache_all_routes    = $this->cache->fetch('pi_all_routes');
        if (!$cache_all_routes){
            return null;
        }
        return $cache_all_routes;
    }

    /**
     * Translate using Doctrine DBAL and a cache layer around it.
     *
     * @param array   $fieldEntity  All fields information
     * @param string  $route        The route name
     * @param array   $locales      An array with keys locales and values patterns
     * @param array   $defaults     An array of default parameter values
     * @param array   $requirements An array of requirements for parameters (regexes)
     * @return void
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-02-27
     */
    public function addRoute($fieldEntity, $route, array $locales, array $defaults = [], array $requirements = [])
    {
//        print_r($fieldEntity['id']);print_r(' - ');
//        print_r($route);print_r(' - ');
//        print_r($fieldEntity['locales']);print_r(' - ');
//        print_r(json_encode($locales));print_r(' - ');
//        print_r("\n");
        if (null !== $fieldEntity) {
            $this->connection->update('pi_routing', array(
                'locales'         => json_encode($locales),
                'defaults'         => json_encode($defaults),
                'requirements'     => json_encode($requirements),
            ), array('id' => $fieldEntity['id']));

        } else {
            $this->connection->insert('pi_routing', array(
                'route'          => $route,
                'locales'        => json_encode($locales),
                'defaults'       => json_encode($defaults),
                'requirements'   => json_encode($requirements),
            ));
        }
        // prime the cache!
        if ($this->primeCache) {
            $hashKey          = sha1($route);
            $cacheKey         = "pi_route__" . $hashKey;
            $RouteValues     = $this->cache->fetch($cacheKey);
            if (!$RouteValues) {
                $RouteValues = [];
            }
            $RouteValues[$hashKey]['route']        = $route;
            $RouteValues[$hashKey]['locales']      = $locales;
            $RouteValues[$hashKey]['defaults']         = $defaults;
            $RouteValues[$hashKey]['requirements']     = $requirements;
            $this->cache->save($cacheKey, $RouteValues);
            // we save the route name in the global cache values
            $cache_all_routes    = $this->cache->fetch('pi_all_routes');
            if (!$cache_all_routes) {
                $cache_all_routes = [];
            }
            $cache_all_routes[]    = $route;
            $this->cache->save('pi_all_routes', $cache_all_routes, 0);
        }
    }

    /**
     * Return all information of a route name which are save in the cache otherwise in the database.
     *
     * @param string  $route         The route name
     * @return array
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-02-27
     */
    public function getRoute($route)
    {
        // values can potentially be large, so we hash them and prevent collisions
        $hashKey         = sha1($route);
        $cacheKey        = "pi_route__" . $hashKey;
        $RouteValues     = $this->cache->fetch($cacheKey);
        if ($RouteValues && isset($RouteValues[$hashKey])) {
            //print_r($RouteValues[$hashKey]);exit;
            return $RouteValues[$hashKey];
        }

        if ($RouteValues = $this->connection->fetchColumn("SELECT id FROM pi_routing WHERE route = ?", array($route))) {
            return $RouteValues;
        }
        return null;
    }
}
