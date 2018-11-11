<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Admin_Managers
 * @package    Widget
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2017-03-05
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Domain\Service\Manager\Widget;

use Sfynx\CmfBundle\Layers\Domain\Service\Manager\Widget\Generalisation\RenderInterface;
use Sfynx\CmfBundle\Layers\Domain\Service\Manager\Generalisation\PiCoreManager;
use Sfynx\ToolBundle\Util\PiArrayManager;
use Sfynx\ToolBundle\Util\PiStringManager;
use Sfynx\ToolBundle\Twig\Extension\PiToolExtension;
use Sfynx\ToolBundle\Route\RouteTranslatorFactory;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;

/**
 * Description of the Widget render
 *
 * @category   Sfynx\CmfBundle\Layers
 * @package    Domain
 * @subpackage Service\Manager\Widget
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class RenderWidget extends PiCoreManager implements RenderInterface
{
    /** @var RequestInterface */
    protected $request;
    /** @var PiArrayManager */
    protected $tools_array;
    /** @var PiStringManager */
    protected $string_manager;
    /** @var PiToolExtension */
    protected $twig_extension_tool;
    /** @var RouteTranslatorFactory */
    protected $route;

    /**
     * @param RequestInterface $request
     * @param PiArrayManager $tools_array
     * @param PiStringManager $string_manager
     * @param PiToolExtension $twig_extension_tool
     * @param RouteTranslatorFactory $route
     * @param $container
     * @access public
     * @return void
     */
    public function __construct(
        RequestInterface $request,
        PiArrayManager $tools_array,
        PiStringManager $string_manager,
        PiToolExtension $twig_extension_tool,
        RouteTranslatorFactory $route,
        $container
    ) {
        $this->request = $request;
        $this->tools_array = $tools_array;
        $this->string_manager = $string_manager;
        $this->twig_extension_tool = $twig_extension_tool;
        $this->router = $route;

        parent::__construct($request, $container);
    }

    /**
     * {@inheritdoc}
     */
    public function setParams(array $option)
    {
        $this->param = (object) $option;
    }

    /**
     * {@inheritdoc}
     */
    public function renderCache($serviceName, $tag, $id, $lang, $params = null)
    {
        // we create the twig code of the service to rn.
        if (!(null === $params)) {
            if (isset($params['widget-sluggify']) && ($params['widget-sluggify'] == true)) {
                $params['widget-sluggify-url']    = $this->request->getUri();
            }
            $json = $this->string_manager->json_encodeDecToUTF8($params);
            $set = " {{ getService('$serviceName').run('$tag', '$id', '$lang', {$json})|raw }} \n";
        } else {
            $set = " {{ getService('$serviceName').run('$tag', '$id', '$lang')|raw }} \n";
        }
        // we register the tag value in the json file if does not exist.
        $this->setJsonFileEtag($tag, $id, $lang, $params);

        return $set;
    }

    /**
     * {@inheritdoc}
     */
    public function renderService($serviceName, $id, $lang, $params = null)
    {
        if (!isset($params['locale']) || empty($params['locale'])) {
            $params['locale']    = $lang;
        }
        if (!(null === $params)) {
            $this->tools_array->recursive_method($params, 'krsort');
            $json = $this->string_manager->json_encodeDecToUTF8($params);
            //
            $esi_method      = $this->twig_extension_tool->encryptFilter('renderSource', $this->param->esi_key);
            $esi_serviceName = $this->twig_extension_tool->encryptFilter($serviceName, $this->param->esi_key);
            $esi_id          = $this->twig_extension_tool->encryptFilter($id, $this->param->esi_key);
            $esi_lang        = $this->twig_extension_tool->encryptFilter($lang, $this->param->esi_key);
            $esi_json        = $this->twig_extension_tool->encryptFilter($json, $this->param->esi_key);
            // we get query string
            $qs = (null !== $qs = $this->request->getQueryString()) ? '?'.$qs : '';
            $_server_ = [
                'REQUEST_URI'  => $this->request->getRequestUri(),
                'REDIRECT_URL' => $this->request->getServer()->get('REDIRECT_URL'),
                'lifetime'     => $params['widget-lifetime'],
                'cacheable'    => $params['widget-cacheable'],
                'update'       => $params['widget-update'],
                'public'       => $params['widget-public'],
            ];
            $esi_server = $this->twig_extension_tool->encryptFilter(json_encode($_server_, JSON_UNESCAPED_UNICODE), $this->param->esi_key);
            // url
            $url = $this->router->generate('public_esi_apply_widget', [
                'method'        =>$esi_method,
                'serviceName'   =>$esi_serviceName,
                'id'            =>$esi_id,
                'lang'          =>$esi_lang,
                'params'        =>$esi_json,
                'key'           =>$this->param->esi_key,
                'server'        =>$esi_server
            ]);
            $is_esi_activate = ($this->param->isEsi) ? true : false;
            $ttl = (int) $params['widget-lifetime'];
            if (($ttl > 0)
                && ($is_esi_activate
                    || $this->param->is_render_service_with_ajax
                    || (isset($params['widget-ajax'])
                        && ($params['widget-ajax'] == true)
                    )
                )
            ) {
                if ($is_esi_activate) {
                    $set  = "{% if is_esi_disable_after_post_request and (app_request_request_count >= 1) %}\n";
                    $set .= "    {{ getService('{$serviceName}').renderSource('{$id}', '{$lang}', {$json})|raw }}\n";
                    $set .= "{% else %}\n";
                    if ($this->param->is_render_service_for_varnish) {
                        $set .= "    <esi:include src=\"{$url}{$qs}\" />\n";
                    } else {
                        $set .= " {{ render_esi(\"{$url}{$qs}\")|raw }} \n";
                    }
                    $set .= "{% endif %}\n";
                } elseif ( $this->param->is_render_service_with_ajax || (isset($params['widget-ajax']) && ($params['widget-ajax'] == true)) ) {
                    $set  = "{% if is_widget_ajax_disable_after_post_request and (app_request_request_count >= 1) %}\n";
                    $set .= "    {{ getService('{$serviceName}').renderSource('{$id}', '{$lang}', {$json})|raw }}\n";
                    $set .= "{% else %}\n";
                    $set .= "    <span class=\"hiddenLinkWidget {{ '{$url}{$qs}'|obfuscateLink }}\" />\n";
                    $set .= "{% endif %}\n";
                }
            } else {
                if ($this->param->is_render_service_with_ttl && ($ttl > 0)) {
                    $set = " {{ renderCache('{$url}{$qs}', '{$ttl}', '{$serviceName}', 'renderSource', '{$id}', '{$lang}', {$json})|raw }}\n";
                } else {
                    $set = " {{ getService('{$serviceName}').renderSource('{$id}', '{$lang}', {$json})|raw }}\n";
                }
            }
            // we register the tag value in the json file if does not exist.
            if (isset($params['widget-id'])) {
                $this->setJsonFileEtag('esi', $params['widget-id'], $lang, ['esi-url'=>"{$url}{$qs}"]);
            } else {
                $this->setJsonFileEtag('esi', $serviceName, $lang, ['esi-url'=>"{$url}{$qs}"]);
            }
        } else {
            $set = " {{ getService('{$serviceName}').renderSource('{$id}', '{$lang}')|raw }}\n";
        }

        return $set;
    }

    /**
     * {@inheritdoc}
     */
    public function renderJquery($JQcontainer, $id, $lang, $params = null)
    {
        str_replace('~', '~', $id, $count);
        if ($count == 2) {
            list($entity, $method, $category) = explode('~', $id);
        } elseif ($count == 1) {
            list($entity, $method) = explode('~', $id);
        } elseif ($count == 0) {
            $method = $id;
        } else {
            throw new \InvalidArgumentException("you have not configure correctly the attibute id");
        }
        if (!isset($params['locale']) || empty($params['locale'])) {
            $params['locale']    = $lang;
        }
        if (!(null === $params)) {
            $this->tools_array->recursive_method($params, 'krsort');
            $json = $this->string_manager->json_encodeDecToUTF8($params);
            // set url of the esi page
            $esi_method      = $this->twig_extension_tool->encryptFilter('FactoryFunction', $this->param->esi_key);
            $esi_serviceName = $this->twig_extension_tool->encryptFilter("sfynx.tool.twig.extension.jquery", $this->param->esi_key);
            $esi_id          = $this->twig_extension_tool->encryptFilter($JQcontainer, $this->param->esi_key);
            $esi_lang        = $this->twig_extension_tool->encryptFilter($method, $this->param->esi_key);
            $esi_json        = $this->twig_extension_tool->encryptFilter($json, $this->param->esi_key);
            // we get query string
            $qs = (null !== $qs = $this->request->getQueryString()) ? '?'.$qs : '';
            $_server_ = [
                'REQUEST_URI'  => $this->request->getRequestUri(),
                'REDIRECT_URL' => $this->request->getServer()->get('REDIRECT_URL'),
                'lifetime'     => $params['widget-lifetime'],
                'cacheable'    => $params['widget-cacheable'],
                'update'       => $params['widget-update'],
                'public'       => $params['widget-public'],
            ];
            $esi_server = $this->twig_extension_tool->encryptFilter(json_encode($_server_, JSON_UNESCAPED_UNICODE), $this->param->esi_key);
            // url
            $url = $this->router->generate('public_esi_apply_widget', [
                'method'        =>$esi_method,
                'serviceName'   =>$esi_serviceName,
                'id'            =>$esi_id,
                'lang'          =>$esi_lang,
                'params'        =>$esi_json,
                'key'           =>$this->param->esi_key,
                'server'        =>$esi_server
            ]);
            $is_esi_activate = ($this->param->isEsi) ? true : false;
            $ttl = (int) $params['widget-lifetime'];
            if (($ttl > 0)
                && ($is_esi_activate
                    || $this->param->is_render_service_with_ajax
                    || (isset($params['widget-ajax'])
                        && ($params['widget-ajax'] == true)
                    )
                )
            ) {
                if($is_esi_activate) {
                    $set  = "{% if is_esi_disable_after_post_request and (app_request_request_count >= 1) %}\n";
                    $set .= "    {{ getService('sfynx.tool.twig.extension.jquery').FactoryFunction('{$JQcontainer}', '{$method}', {$json})|raw }}\n";
                    $set .= "{% else %}\n";
                    if ($this->param->is_render_service_for_varnish) {
                        $set .= "    <esi:include src=\"{$url}{$qs}\" />\n";
                    } else {
                        $set .= " {{ render_esi(\"{$url}{$qs}\")|raw }} \n";
                    }
                    $set .= "{% endif %}\n";
                } elseif ( $this->param->is_render_service_with_ajax || (isset($params['widget-ajax']) && ($params['widget-ajax'] == true)) ) {
                    $set  = "{% if is_widget_ajax_disable_after_post_request and (app_request_request_count >= 1) %}\n";
                    $set .= "    {{ getService('sfynx.tool.twig.extension.jquery').FactoryFunction('{$JQcontainer}', '{$method}', {$json})|raw }}\n";
                    $set .= "{% else %}\n";
                    $set .= "    <span class=\"hiddenLinkWidget {{ '{$url}{$qs}'|obfuscateLink }}\" />\n";
                    $set .= "{% endif %}\n";
                }
            } else {
                if ($this->param->is_render_service_with_ttl && ($ttl > 0)) {
                    $set = " {{ renderCache('{$url}{$qs}', '{$ttl}', 'sfynx.tool.twig.extension.jquery', 'FactoryFunction', '{$JQcontainer}', '{$method}', {$json})|raw }}\n";
                } else {
                    $set = " {{ getService('sfynx.tool.twig.extension.jquery').FactoryFunction('{$JQcontainer}', '{$method}', {$json})|raw }}\n";
                }
            }
            // we register the tag value in the json file if does not exist.
            if (isset($params['widget-id'])) {
                $this->setJsonFileEtag('esi', $params['widget-id'], $lang, ['esi-url'=>"{$url}{$qs}"]);
            } else {
                $this->setJsonFileEtag('esi', $JQcontainer, $lang, ['esi-url'=>"{$url}{$qs}"]);
            }
        } else {
            $set = " {{ getService('sfynx.tool.twig.extension.jquery').FactoryFunction('{$JQcontainer}', '{$method}')|raw }}\n";
        }

        return $set;
    }
}
