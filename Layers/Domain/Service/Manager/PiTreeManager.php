<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Admin_Managers
 * @package    Manager
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-04-19
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Domain\Service\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Sfynx\CmfBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\PiTreeManagerBuilderInterface;
use Sfynx\CmfBundle\Layers\Domain\Service\Manager\Generalisation\PiCoreManager;
use Sfynx\ToolBundle\Util\PiArrayManager;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;

/**
 * Description of the Tree Widget manager
 *
 * @category   Sfynx\CmfBundle\Layers
 * @package    Domain
 * @subpackage Service\Manager
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiTreeManager extends PiCoreManager implements PiTreeManagerBuilderInterface
{
    /**
     * Constructor.
     *
     * @param RequestInterface $request
     * @param ContainerInterface $container
     */
    public function __construct(
        RequestInterface $request,
        ContainerInterface $container
    ) {
        parent::__construct($request, $container);
    }

    /**
     * Call the tree render source method.
     *
     * @param string $id
     * @param string $lang
     * @param string $params
     * @return string
     * @access    public
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-04-19
     */
    public function renderSource($id, $lang = '', $params = null)
    {
        str_replace('~', '~', $id, $count);
        if ($count == 2) {
            list($entity, $method, $category) = explode('~', $this->_Decode($id));
        } elseif ($count == 1) {
            list($entity, $method) = explode('~', $this->_Decode($id));
        } else {
            throw new \InvalidArgumentException("you have not configure correctly the attibute id");
        }

        if (!is_array($params)) {
            $params = $this->paramsDecode($params);
        } else {
            $this->recursive_map($params);
        }
        $params['locale'] = $lang;

        if (isset($category) && ($method == "_navigation_default")) {
            $template = $params['config']['widgets']['gedmo']['params']['template'];
            $params = $params['config']['widgets']['gedmo']['params'];
            return $this->defaultNavigation(
                $lang,
                $entity,
                $category,
                $template,
                $params
            );
        } elseif (isset($GLOBALS['JQUERY']['MENU'][$method])
            && $this->container->has($GLOBALS['JQUERY']['MENU'][$method])
        ) {
            return $this->container->get('sfynx.tool.twig.extension.jquery')->FactoryFunction('MENU', $method, $params);
        }
        throw new \InvalidArgumentException("you have not configure correctly the attibute id");
    }

    /**
     * Return the build tree result of a gedmo tree entity, with class options.
     *
     * @param string    $locale
     * @param string    $entity
     * @param string    $category
     * @param string    $template
     * @param array     $params
     * @access public
     * @return string
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function defaultNavigation($locale, $entity, $category, $template, $params = null)
    {
        $em   = $this->container->get('doctrine')->getManager();
        $node = null;
        $tree = '';

        $self             = &$this;
        $self->entity     = $entity;
        $self->locale     = $locale;
        $self->root_level = null;
        $self->nodes      = null;
        $self->params     = PiArrayManager::array_to_object($params);

        if (isset($self->params->node) && !empty($self->params->node) ) {
            $node  = $em->getRepository($entity)->findNodeOr404($self->params->node, $locale, 'object');
        }
        try {
            // we construct the query
            if (!isset($self->params->navigation->query_function)
                || empty($self->params->navigation->query_function)
            ) {
                if (isset($self->params->enabledonly)
                    && (!$self->params->enabledonly)
                ) {
                    $query  = $em->getRepository($entity)->setContainer($this->container)->getAllTree($locale, $category, 'query', false, false, $node);
                } else {
                    $query  = $em->getRepository($entity)->setContainer($this->container)->getAllTree($locale, $category, 'query', false, true, $node);
                }
            } elseif (method_exists($em->getRepository($entity), $self->params->navigation->query_function)) {
                $query_function = $self->params->navigation->query_function;
                if (isset($self->params->enabledonly)
                    && (!$self->params->enabledonly)
                ) {
                    $query  = $em->getRepository($entity)->$query_function($locale, $category, 'query', false, false, $node);
                } else {
                    $query = $em->getRepository($entity)->$query_function($locale, $category, 'query', false, true, $node);
                }
            } else {
                throw new \InvalidArgumentException("The metohd 'query_function' does not exist in the entity's repository {$entity}");
            }
            if (isset($self->params->navigation->searchFields)
                && !empty($self->params->navigation->searchFields)
            ) {
                if (
                    count($self->params->navigation->searchFields) == 2
                    && isset($self->params->navigation->searchFields->nameField)
                ) {
                    if (!empty($self->params->navigation->searchFields->nameField)) {
                        $query->andWhere('node.' . $self->params->navigation->searchFields->nameField .' :value')
                        ->setParameters([
                            'value'   => $self->params->navigation->searchFields->valueField
                        ]);
                    }
                } else {
                    foreach ($self->params->navigation->searchFields as $searchFields) {
                        if (!empty($searchFields->nameField)) {
                            $query->andWhere('node.' . $searchFields->nameField . ' ' . $searchFields->valueField);
                        }
                    }
                }
            }
            if (!empty($template)) {
                $self->nodes = $em->getRepository($entity)->findTranslationsByQuery($locale, $query->getQuery(), 'object', false);
            } else {
                $self->nodes = $em->getRepository($entity)->findTranslationsByQuery($locale, $query->getQuery(), 'array', false);
            }
        } catch (\Exception $e) {
            $self->nodes = null;
        }
        if (!empty($template)) {
            $self->repository     = $em->getRepository($entity);
            $response             = $this->container->get('templating')->renderResponse($template, (array) $self);
            $tree                 = $response->getContent() . " \n";
            $tree                 = $this->container->get('sfynx.tool.string_manager')->closetags($tree);
            $tree                 = utf8_decode(mb_convert_encoding($tree, "UTF-8", "HTML-ENTITIES"));
        } else {
            $options = [
                'decorate' => true,
                'rootOpen' => function($nodes) use (&$self) {
                    $first_node  = current($nodes);
                    if ((null === $self->root_level)) {
                        $self->root_level = 0;
                        if (!empty($first_node['lvl'])) {
                            $self->root_level = $first_node['lvl'];
                        }
                    }
                    if (($first_node['lvl'] == $self->root_level) || empty($first_node['lvl'])) {
                        $content = "\n <ul class='".$self->params->navigation->ulClass."' > \n";
                    } else {
                        $content = "\n <ul class='".$self->params->navigation->ulClass." niveau".($first_node['lvl']-$self->root_level)."' > \n";
                    }
                    return $content;
                },
                'rootClose' => "\n </ul> \n",
                'childOpen' => " \n",    // 'childOpen' => "    <li class='collapsed' > \n",
                'childClose' => "    </li> \n",
                'nodeDecorator' => function($node) use (&$self) {
                    // if the node is disable
                    if (
                        ( ($node['lvl']-$self->root_level) == 0 )
                        && isset($node["enabled"])
                        && ($node["enabled"] == 0)
                        && $self->params->navigation->routeActifMenu->enabledonly
                    ) {
                        return "";
                    }
                    if (
                        ( ($node['lvl']-$self->root_level) != 0 )
                        && isset($node["enabled"])
                        && ($node["enabled"] == 0)
                        && $self->params->navigation->lvlActifMenu->enabledonly
                    ) {
                        return "";
                    }
                    // we get the url of the page associated to the menu.
                    $menu   = $self->getContainer()->get('doctrine')->getManager()->getRepository($self->entity)->findOneById($node['id']);
                    if ( method_exists($menu, 'getPage')
                        && ($menu->getPage() InstanceOf \Sfynx\CmfBundle\Layers\Domain\Entity\Page)
                    ) {
                        $routename  = $menu->getPage()->getRouteName();
                        $url        = $self->getContainer()->get('sfynx.tool.route.factory')->generate($menu->getPage()->getRouteName(), ['locale'=>$self->locale]);
                    } elseif (method_exists($menu, 'getUrl')
                        && ($menu->getUrl() != "")
                    ) {
                        $routename = '';
                        $url       = $menu->getUrl();
                    } else {
                        $routename = '';
                        $url       = "#"; //$self->getContainer()->get('router')->generate($routename);
                    }
                    if (method_exists($menu, 'getTitle')) {
                        $title = $menu->getTitle();
                    } else {
                        $title = "Undefined title";
                    }
                    // we get the image of the menu if it exists.
                    $img_balise = "";
                    if (method_exists($menu, 'getMedia')
                        && ($menu->getMedia() instanceof \Sfynx\MediaBundle\Layers\Domain\Entity\Mediatheque)
                    ) {
                        $id = $menu->getMedia()->getId();
                        if ( !(null === $menu->getMedia())
                            && ($menu->getMedia()->getImage()->getName() != $self->getContainer()->getParameter("pi_app_admin.page.media_pixel"))
                        ) {
                            $img_balise = $self->getContainer()->get('sonata.media.twig.extension')->media($menu->getMedia()->getImage(), 'default_small', ['width'=>'20', 'alt'=>""]);
                        }
                    }
                    // we get all route name of all childs of the menu.
                    $childs_routesnames = $self->getContainer()->get('doctrine')->getManager()->getRepository($self->entity)->findChildsRouteByParentId($node['id'], $self->locale, 'string');
                    //print_r($self->nodes);
                    //print_r("<br /><br />");
                    $first_node    = reset($self->nodes);
                    $last_node     = end($self->nodes);
                    //
                    $separator = "";
                    $separatorlast = "";
                    if (!empty($self->params->navigation->separatorClass)) {
                        $separator = "<li class='{$self->params->navigation->separatorClass}'>{$self->params->navigation->separatorText}</li>";
                    } elseif ((!$self->params->navigation->separatorFirst)
                        && ($first_node['id'] == $node['id'])
                    ) {
                        $separator = "";
                        //print_r('first ' . $node['lft']);
                        //print_r("<br /><br />");
                    }
                    if ( ($self->params->navigation->separatorLast)
                        && ($last_node['id'] == $node['id'])
                    ) {
                        $separatorlast =  $separator;
                        //print_r('last ' . $node['lft']);
                        //print_r("<br /><br />");
                    }
                    // we create the decorator.
                    if (( ($node['lvl'] - $self->root_level) == 0 )) {
                        if (!empty($img_balise)) {
                            $content = $separator . '<li class="'.$self->params->navigation->liClass.' {{ in_paths("'.$childs_routesnames.'", "'.$self->params->navigation->routeActifMenu->liActiveClass.'", "'.$self->params->navigation->routeActifMenu->liInactiveClass.'")|raw }}" ><a class="menu-item {{ in_paths("'.$childs_routesnames.'", "'.$self->params->navigation->routeActifMenu->aActiveClass.'", "'.$self->params->navigation->routeActifMenu->aInactiveClass.'")|raw }} " href="'.$url.'" data-node="'.$node['id'].'" >'.$img_balise."</a>".$separatorlast." \n";
                        } else {
                            $content = $separator . '<li class="'.$self->params->navigation->liClass.' {{ in_paths("'.$childs_routesnames.'", "'.$self->params->navigation->routeActifMenu->liActiveClass.'", "'.$self->params->navigation->routeActifMenu->liInactiveClass.'")|raw }}" ><a class="menu-item {{ in_paths("'.$childs_routesnames.'", "'.$self->params->navigation->routeActifMenu->aActiveClass.'", "'.$self->params->navigation->routeActifMenu->aInactiveClass.'")|raw }} " href="'.$url.'" data-node="'.$node['id'].'" >'.$title."</a>".$separatorlast." \n";
                        }
                    } else {
                        // we initialize the counter
                        $counter = "";
                        if ($self->params->navigation) {
                            $counter = '-' . ($node['lvl']-$self->root_level);
                        }
                        if (!empty($img_balise)) {
                            $content = $separator . '<li class="'.$self->params->navigation->liClass.$counter.' {{ in_paths("'.$childs_routesnames.'", "'.$self->params->navigation->lvlActifMenu->liActiveClass.'", "'.$self->params->navigation->lvlActifMenu->liInactiveClass.'")|raw }}" ><a class="menu-item {{ in_paths("'.$childs_routesnames.'", "'.$self->params->navigation->lvlActifMenu->aActiveClass.'", "'.$self->params->navigation->lvlActifMenu->aInactiveClass.'")|raw }} " href="'.$url.'" data-node="'.$node['id'].'" >'.$img_balise."</a>".$separatorlast." \n";
                        } else {
                            $content = $separator . '<li class="'.$self->params->navigation->liClass.$counter.' {{ in_paths("'.$childs_routesnames.'", "'.$self->params->navigation->lvlActifMenu->liActiveClass.'", "'.$self->params->navigation->lvlActifMenu->liInactiveClass.'")|raw }}" ><a class="menu-item {{ in_paths("'.$childs_routesnames.'", "'.$self->params->navigation->lvlActifMenu->aActiveClass.'", "'.$self->params->navigation->lvlActifMenu->aInactiveClass.'")|raw }} " href="'.$url.'" data-node="'.$node['id'].'" >'.$title."</a>".$separatorlast." \n";
                        }
                    }

                    return $content;
                }
            ];

//            $all_routes_nodes = $em->getRepository($entity)->getRootNodes();
//            foreach($all_routes_nodes as $key=> $node){
//                $node  = $em->getRepository($entity)->findNodeOr404($node->getId(), $locale,'object');
//                $query = $em->getRepository($entity)->reorder($node);
//            }
//
//            // we repair the tree
//            $em->getRepository($entity)->recover();
//            $result = $em->getRepository($entity)->verify();

            $tree = $em->getRepository($entity)->buildTree($self->nodes, $options);
        }

        return $this->container->get('twig')->createTemplate($tree)->render([]);
    }

    /**
     * Return the build tree result of a gedmo tree entity, without class options.
     *
     * @param string    $locale
     * @param string    $entity
     * @param string    $category
     * @param array     $params
     * @return string
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-04-19
     */
    public function defaultOrganigram($locale, $entity, $category, $params = null)
    {
        $em = $this->container->get('doctrine')->getManager();
        //
        $node = null;
        $query_function = null;
        if (isset($params['node']) && !empty($params['node']) ){
            $node = $em->getRepository($entity)->findNodeOr404($params['node'], $locale,'object');
        }
        $category = utf8_decode($category);
        try {
            // we construct the query
            if (isset($params['navigation>']['query_function']) && !empty($params['navigation>']['query_function'])) {
                $query_function = $params['navigation>']['query_function'];
            }
            if ((null === $query_function)) {
                if (isset($params['enabledonly']) && ($params['enabledonly'] == "false")) {
                    $query  = $em->getRepository($entity)->setContainer($this->container)->getAllTree($locale, $category, 'query', false, false, $node);
                } else {
                    $query  = $em->getRepository($entity)->setContainer($this->container)->getAllTree($locale, $category, 'query', false, true, $node);
                }
            } elseif (method_exists($em->getRepository($entity), $query_function)) {
                if (isset($params['enabledonly']) && ($params['enabledonly'] == "false")) {
                    $query  = $em->getRepository($entity)->$query_function($locale, $category, 'query', false, false, $node);
                } else {
                    $query  = $em->getRepository($entity)->$query_function($locale, $category, 'query', false, true, $node);
                }
            } else {
                throw new \InvalidArgumentException("The metohd 'query_function' does not exist in the entity's repository {$entity}");
            }
            if (isset($params['navigation>']['searchFields']) && !empty($params['navigation>']['searchFields'])) {
                if (
                    count($params['navigation>']['searchFields']) == 2
                    &&
                    isset($params['navigation>']['searchFields']['nameField'])
                ) {
                    if (!empty($params['navigation>']['searchFields']['nameField'])) {
                        $query->andWhere('node.'.$params['navigation>']['searchFields']['nameField'] .' :value')
                        ->setParameters([
                            'value' => $params['navigation>']['searchFields']['valueField']
                        ]);
                    }
                } else {
                    foreach ($params['navigation>']['searchFields'] as $searchFields) {
                        if (!empty($searchFields['nameField'])) {
                            $query->andWhere('node.'.$searchFields['nameField'] .' '.$searchFields['valueField']);
                        }
                    }
                }
            }
            $nodes = $em->getRepository($entity)->findTranslationsByQuery($locale, $query->getQuery(), 'array', false);
        } catch (\Exception $e) {
            $nodes = null;
        }
        if (!empty($template)) {
            $params['locale']     = $locale;
            $params['nodes']      = $nodes;
            $params['repository'] = $em->getRepository($entity);
            $response             = $this->container->get('templating')->renderResponse($template, $params);
            $tree                 = $response->getContent() . " \n";
            $tree                 = $this->container->get('sfynx.tool.string_manager')->closetags($tree);
            $tree                 = utf8_decode(mb_convert_encoding($tree, "UTF-8", "HTML-ENTITIES"));
        } else {
            // we set the tree option
            $self         = &$this;
            $self->entity = $entity;
            $self->locale = $locale;
            if (isset($params['fields'])) {
                $self->fields = $params['fields'];
            }  else {
                $self->fields = null;
            }
            $options = array(
                    'decorate' => true,
                    'rootOpen' => "\n <ul> \n",
                    'rootClose' => "\n </ul> \n",
                    'childOpen' => "    <li class='collapsed' > \n",        // 'childOpen' => "    <li class='collapsed' > \n",
                    'childClose' => "    </li> \n",
                    'nodeDecorator' => function($node) use (&$self) {
                        // we get the url of the page associated to the menu.
                        $tree   = $self->getContainer()->get('doctrine')->getManager()->getRepository($self->entity)->findOneById($node['id']);
                        if ( method_exists($tree, 'getPage') && ($tree->getPage() InstanceOf \Sfynx\CmfBundle\Layers\Domain\Entity\Page) ) {
                            $routename  = $tree->getPage()->getRouteName();
                            $url        = $self->getContainer()->get('sfynx.tool.route.factory')->generate($tree->getPage()->getRouteName(), array('locale'=>$self->locale));
                        } else {
                            $routename  = '';
                            $url        = "#"; //$self->getContainer()->get('router')->generate($routename);
                        }
                        $content = '';
                        if (is_array($self->fields)) {
                            foreach($self->fields as $key => $field) {
                                if ( isset($field['content']) && !empty($field['content']) ) {
                                    if ($field['content'] == "leftright" ){
                                        if ( isset($field['class']) && !empty($field['class']) ) {
                                            $class = $field['class'];
                                        } else {
                                            $class = "pi_tree_desc";
                                        }
                                        $content .= "<p class='$class'>" . $node["lft"] . ' - '. $node["rgt"] . ' (n'. $node["id"] .', lvl '. $node["lvl"] .')' ."</p> \n";
                                    } else {
                                        if ( isset($field['class']) && !empty($field['class']) ) {
                                            $class = $field['class'];
                                        } else {
                                            $class = "pi_tree_desc";
                                        }
                                        $method     = 'get' . ucfirst(strtolower($field['content']));
                                        if ( method_exists($tree, $method)) {
                                            $field_content    = $tree->$method();
                                        } else {
                                            $field_content    = "Undefined content";
                                        }
                                        if ($key == 0) {
                                            $content .= '<a href="'.$url.'" data-rub="'.$node['id'].'" class="'.$class.'">'.$field_content."</a><p class='$class'>$routename<p> \n";
                                        } else {
                                            $content .= "<p class='$class'>".$field_content."</p> \n";
                                        }
                                    }
                                }
                            }
                        }
                        return  $content;
                    }
            );
            // we repair the tree
            //$em->getRepository($entity)->recover();
            //$result = $em->getRepository($entity)->verify();
            //$node = $em->getRepository($entity)->findNodeOr404(4, $locale,'object');
            //$left = $em->getRepository($entity)->children($node);
            //print_r($left);exit;
            $tree = $em->getRepository($entity)->buildTree($nodes, $options);
        }

        return $this->container->get('twig')->createTemplate($tree)->render([]);
    }
}
