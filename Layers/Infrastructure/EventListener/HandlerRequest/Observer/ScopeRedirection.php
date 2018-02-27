<?php
namespace Sfynx\CmfBundle\Layers\Infrastructure\EventListener\HandlerRequest\Observer;

use SplSubject;
use Sfynx\CmfBundle\Layers\Infrastructure\EventListener\HandlerRequest\Generalisation\HandlerRequestInterface;
use Sfynx\CmfBundle\Layers\Infrastructure\EventListener\HandlerRequest\Observer\SfynxCmfEvents;
use Sfynx\AuthBundle\Infrastructure\Event\ViewObject\ResponseEvent;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ScopeRedirection
 *
 * NoNav redirection template in order to the configuration.
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ScopeRedirection implements HandlerRequestInterface
{
    /**
     * {@inheritdoc}
     */
    public function update(SplSubject $subject)
    {
        if (
            $subject->param->is_scop_authorized
            && $subject->container->has("sfynx.browser.lib.mobiledetect")
            && $subject->container->has("sfynx.browser.lib.browscap")
        ) {
            $this->browser      = $subject->container->get("sfynx.browser.browscap")->getClient();
            $this->mobiledetect = $subject->container->get("sfynx.browser.mobiledetect")->getClient();
            // we set globals information
            $globals     = $subject->param->scop_globals;
            $nav_desktop = strtolower($this->browser->Browser);
            $nav_mobile  = strtolower($this->browser->Platform);
            $isNoScope   = false;
            if (
                (!$this->browser->isMobileDevice)
                &&
                (!isset($globals["navigator"][$nav_desktop]) || floatval($this->browser->Version) <= $globals["navigator"][$nav_desktop])
            ) {
                $isNoScope = true;
            } elseif (
                ($this->browser->isMobileDevice && !$this->mobiledetect->isTablet())
                &&
                (!isset($globals["mobile"][$nav_mobile]) || floatval($this->browser->Platform_Version) <= $globals["mobile"][$nav_mobile] )
            ) {
                $isNoScope = true;
            } elseif (
                ($this->browser->isMobileDevice && $this->mobiledetect->isTablet())
                &&
                (!isset($globals["tablet"][$nav_mobile]) || floatval($this->browser->Platform_Version) <= $globals["tablet"][$nav_mobile] )
            ) {
                $isNoScope = true;
            }
            if ( ($this->browser->Version == '0.0') || ($this->browser->Platform_Version == '0.0') ) {
                $isNoScope = false;

            }
            if($isNoScope) {
                $isCookies = $this->browser->Cookies;
                $isJs      = $this->browser->JavaScript;
                $isNav     = true;
                if (isset($globals["navigator"][$nav_desktop])
                    && (floatval($this->browser->Version)  <= $globals["navigator"][$nav_desktop])
                ) {
                    $isNav = false;
                }
                if ($this->browser->isMobileDevice
                    && isset($globals["navigator"][$nav_mobile])
                    && (floatval($this->browser->Platform_Version)  <= $globals["navigator"][$nav_mobile])
                ) {
                    $isNav = false;
                }
                // we set response
                $response = new Response($subject->request->getUri());
                $response->headers->set('Content-Type', 'text/html');
                $response = $subject->templating->renderResponse(
                    'SfynxTemplateBundle:Template\\Nonav:nonav.html.twig',
                    ['locale' => $subject->request->getLocale(), 'isCookies'=>$isCookies, 'isJs'=>$isJs, 'isNav'=>$isNav],
                    $response
                );
                // we apply all events allowed to change the response
                $event_response = new ResponseEvent($response, $subject->param->date_expire);
                $subject->dispatcher->dispatch(
                    SfynxCmfEvents::HANDLER_REQUEST_CHANGERESPONSE_NOSCOPE,
                    $event_response
                );
                $response = $event_response->getResponse();

                return $response;
            }
        }

        return false;
    }
}
