<?php
namespace Sfynx\CmfBundle\Layers\Infrastructure\EventListener\HandlerRequest\Observer;

use SplSubject;

use Sfynx\CmfBundle\Layers\Infrastructure\EventListener\HandlerRequest\Generalisation\HandlerRequestInterface;
use Sfynx\CmfBundle\Layers\Infrastructure\EventListener\HandlerRequest\Observer\SfynxCmfEvents;
use Sfynx\AuthBundle\Infrastructure\Event\ViewObject\ResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class HomepageRedirection
 *
 * Sets the good home_page
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class HomepageRedirection implements HandlerRequestInterface
{
    /**
     * {@inheritdoc}
     */
    public function update(SplSubject $subject)
    {
        if ($subject->param->is_prefix_locale) {
            $route = $subject->request->get('route_name');
            $url   = $subject->request->getRequestUri();
            if (($route != 'home_page') && ($url == '/')) {
                $url_homepage = $subject->route->generate('home_page');
                $response     = new RedirectResponse($url_homepage, 301);
                // we apply all events allowed to change the redirection response
                $event_response = new ResponseEvent($response, $subject->param->date_expire);
                $subject->dispatcher->dispatch(
                    SfynxCmfEvents::HANDLER_REQUEST_CHANGERESPONSE_PREFIX_LOCALE_REDIRECTION,
                    $event_response
                );
                $response = $event_response->getResponse();

                return $response;
            }
        }

        return false;
    }
}
