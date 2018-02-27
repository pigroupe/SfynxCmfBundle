<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   TwigCacheLoader
 * @package    Tool
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-12
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Domain\Service\Twig;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Twig cache config.
 *
 * @subpackage   TwigCacheLoader
 * @package    Tool
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiTwigCache
{
    /**
     *
     * @var \Twig_Environment
     */
    protected $twig_environment = null;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     *
     * @param \Twig_Environment $twig_environment
     */
    public function __construct(\Twig_Environment $twig_environment, ContainerInterface $container)
    {
        $this->twig_environment = $twig_environment;
        $this->container        = $container;
    }

    /**
     *
     * @return \Twig_Environment
     */
    public function getTwigEnvironment()
    {
        return $this->twig_environment;
    }

    /**
     *
     * @param \Twig_Environment $twig_environment
     */
    public function setTwigEnvironment(\Twig_Environment $twig_environment)
    {
        $this->twig_environment = $twig_environment;
    }

    /**
     * Delete the cache filename for a given template.
     *
     * @param string $name
     */
    public function invalidate($name)
    {
        $twigEnv = $this->getTwigEnvironment();
        @unlink($twigEnv->getCache(false)->generateKey($name, $twigEnv->getTemplateClass($name)));
    }

    /**
     * Loads and warms up a template by name.
     *
     * @param string $name
     */
    public function warmup($name/*, $ext*/)
    {
        $this->getTwigEnvironment()->loadTemplate($name);

        $isMemCacheEnable = $this->container->getParameter("pi_app_admin.page.memcache_enable_all");
        if ($isMemCacheEnable && $this->container->has("sfynx.cache.memcache")) {
            $this->container->get("sfynx.cache.memcache")->clear($name);
        }
    }

    /**
     * Renders a view and returns a Response.
     *
     * @param string   $name       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A Response instance
     *
     * @return Response A Response instance
     */
    public function renderResponse($name, array $parameters = [], Response $response = null)
    {
        if (null === $response) {
            $response = new Response();
        }
        $response->headers->set('PI-Application', 'Sfynx');
        $isMemCacheEnable = $this->container->getParameter("pi_app_admin.page.memcache_enable_all");
        // if the memcache service is disable
        // OR MemCache service doesn't exist
        // OR the content name isn't register in the memcache
        if ( !$isMemCacheEnable
            || !$this->container->has("sfynx.cache.memcache")
            || ($this->container->has("sfynx.cache.memcache") && !$this->container->get("sfynx.cache.memcache")->get($name))
        ) {
            //$response->setContent($this->getTwigEnvironment()->loadTemplate($name)->render($parameters));
            $response = $this->container->get('pi_app_admin.templating')->renderResponse($name, $parameters, $response);
            // if the memcache service does exist, we register the content page in the memcache
            if ($isMemCacheEnable) {
                //$source =  $this->getTwigEnvironment()->getLoader()->getSourceContext($name);
                $this->container->get("sfynx.cache.memcache")->set($name, $response);
            }
        } elseif ($isMemCacheEnable) {
            $response = $this->container->get("sfynx.cache.memcache")->get($name);
        }

        return $response;
    }
}
