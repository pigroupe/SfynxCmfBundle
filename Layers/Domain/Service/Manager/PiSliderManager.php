<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Admin_Managers
 * @package    Manager
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-04-25
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Domain\Service\Manager;

use Sfynx\CmfBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\PiSliderManagerBuilderInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of the slider Widget manager
 *
 * @subpackage   Admin_Managers
 * @package    Manager
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiSliderManager extends PiCoreManager implements PiSliderManagerBuilderInterface
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
     * Call the slider render source method.
     *
     * @param string $id
     * @param string $lang
     * @param string $params
     * @return string
     * @access    public
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-04-25
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
            $params        = $this->paramsDecode($params);
        } else {
            $this->recursive_map($params);
        }
        $params['locale'] = $lang;
        if ( isset($GLOBALS['JQUERY']['SLIDER'][$method]) && $this->container->has($GLOBALS['JQUERY']['SLIDER'][$method]) ) {
            return $this->container->get('sfynx.tool.twig.extension.jquery')->FactoryFunction('SLIDER', $method, $params);
        } else {
            throw new \InvalidArgumentException("you have not configure correctly the attibute id");
        }
    }

    /**
     * Return the build slide result of a slider entity
     *
     * @param string    $locale
     * @param string    $entity
     * @param string    $category
     * @param string    $template
     * @return string
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-05-03
     */
    public function getSlider($locale, $controller, $category, $template, $parameters = null)
    {
        $em = $this->container->get('doctrine')->getManager();
        $em->getRepository($controller)->setContainer($this->container);

        $boucle_array = false;
        $query_function = null;
        $ORDER_PublishDate = '';
        $ORDER_Position = '';
        $MaxResults = null;
        $enabled = true;

        if (isset($parameters['boucle_array']) && !empty($parameters['boucle_array'])) {
            $boucle_array = (int) $parameters['boucle_array'];
        }
        if (isset($parameters['query_function']) && !empty($parameters['query_function'])) {
            $query_function = $parameters['query_function'];
        }
        if (isset($parameters['orderby_date']) && !empty($parameters['orderby_date'])) {
            $ORDER_PublishDate = $parameters['orderby_date'];
        }
        if (isset($parameters['orderby_position']) && !empty($parameters['orderby_position'])) {
            $ORDER_Position = $parameters['orderby_position'];
        }
        if (isset($parameters['MaxResults']) && !empty($parameters['MaxResults'])) {
            $MaxResults = (int) $parameters['MaxResults'];
        }
        if (isset($parameters['enabled']) && !empty($parameters['enabled'])) {
            $enabled = (int) $parameters['enabled'];
        }
        if (empty($ORDER_PublishDate) && empty($ORDER_Position)) {
            $ORDER_Position = 'ASC';
        }

        if ((null === $query_function)) {
            $query = $em->getRepository($controller)->getAllByCategory($category, $MaxResults, $ORDER_PublishDate, $ORDER_Position, $enabled, false);
        } elseif (method_exists($em->getRepository($controller), $query_function)) {
            $query = $em->getRepository($controller)->$query_function($category, $MaxResults, $ORDER_PublishDate, $ORDER_Position, $enabled);
        } else {
            throw new \InvalidArgumentException("The metohd 'query_function' does not exist in the entity's repository {$controller}");
        }
        if (isset($parameters['searchFields']) && !empty($parameters['searchFields'])) {
            if (
                count($parameters['searchFields']) == 2
                &&
                isset($parameters['searchFields']['nameField'])
            ){
                if (!empty($parameters['searchFields']['nameField'])) {
                    $query->andWhere('a.'.$parameters['searchFields']['nameField'] .' :value')
                    ->setParameters(array(
                        'value'   => $parameters['searchFields']['valueField']
                    ));
                }
            } else {
                foreach ($parameters['searchFields'] as $searchFields) {
                    if (!empty($searchFields['nameField'])) {
                        $query->andWhere('a.'.$searchFields['nameField'] .' '.$searchFields['valueField']);
                    }
                }
            }
        }
        $allslides  = $em->getRepository($controller)->findTranslationsByQuery($locale, $query->getQuery(), 'object', false);
        // we construct all boucles.
        $_boucle    = [];
        $_boucle1   = [];
        $_boucle2   = [];
        $_boucle3   = [];
        $RouteNames = [];

        end($allslides);
        $last_key_value = key($allslides);
        reset($allslides);
        foreach ($allslides as $key => $slide) {
            if (method_exists($slide, 'getPosition')) {
                $position = $slide->getPosition() - 1;
                $RouteNames[$position] = "";
                if (method_exists($slide, 'getPage')
                    && ($slide->getPage() instanceof \Sfynx\CmfBundle\Layers\Domain\Entity\Page)
                ) {
                    $RouteNames[$position] = $slide->getPage()->getRouteName();
                }
            }

            $parameters['slide']  = $slide;
            $parameters['lang']   = $locale;
            $parameters['locale'] = $locale;
            $parameters['key']    = $key;
            $parameters['last']   = $last_key_value;

            if (!isset($parameters['width']) || empty($parameters['width'])) {
            	$parameters['width'] = "100%";
            }
            if (!isset($parameters['height']) || empty($parameters['height'])) {
            	$parameters['height'] = "100%";
            }
            $templateContent = $this->container->get('twig')->loadTemplate($template);
            if ($templateContent->hasBlock("boucle")) {
                $_boucle[]    = $templateContent->renderBlock("boucle", $parameters) . " \n";
            }
            if ($templateContent->hasBlock("boucle1")) {
                $_boucle1[]    = $templateContent->renderBlock("boucle1", $parameters) . " \n";
            }
            if ($templateContent->hasBlock("boucle2")) {
                $_boucle2[]    = $templateContent->renderBlock("boucle2", $parameters) . " \n";
            }
            if ($templateContent->hasBlock("boucle3")) {
                $_boucle3[]    = $templateContent->renderBlock("boucle3", $parameters) . " \n";
            }
            if (!$templateContent->hasBlock("boucle")
                && !$templateContent->hasBlock("boucle1")
                && !$templateContent->hasBlock("boucle2")
                && !$templateContent->hasBlock("boucle3")
            ) {
                $response  = $this->container->get('templating')->renderResponse("SfynxTemplateBundle:Template\\Slider:$template", $parameters);
                $_boucle[] = $response->getContent() . " \n";
            }
        }
        if ($boucle_array) {
            return [
                'boucle' => $_boucle,
                'boucle1' => $_boucle1,
                'boucle2' => $_boucle2,
                'boucle3' => $_boucle3,
                'routenames' => $RouteNames
            ];
        } else {
            return [
                'boucle' => implode(" \n", $_boucle),
                'boucle1' => implode(" \n", $_boucle1),
                'boucle2' => implode(" \n", $_boucle2),
                'boucle3' => implode(" \n", $_boucle3),
                'routenames' => $RouteNames
            ];
        }
    }
}
