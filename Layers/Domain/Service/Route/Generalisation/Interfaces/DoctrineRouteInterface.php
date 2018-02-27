<?php
namespace Sfynx\CmfBundle\Layers\Domain\Service\Route;

/**
 * DoctrineRouteInterface interface.
 *
 * @subpackage   PiApp
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-02-23
 */
interface DoctrineRouteInterface
{
    public function addAllRoutePageCollections();
    public function parseRoutePages();
    public function getAllRouteValues();
    public function getAllRouteNames();
    public function getRoute($route);
    public function addRoute($route, $id, array $locales, array $defaults = array(), array $requirements = array());
}