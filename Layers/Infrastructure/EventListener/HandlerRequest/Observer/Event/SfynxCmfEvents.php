<?php
namespace Sfynx\CmfBundle\Layers\Infrastructure\EventListener\HandlerRequest\Observer;

/**
 * Contains all events thrown in the SFYNX
 * 
 * @category   Cmf
 * @package    Event
 * @subpackage Constant
 * @final
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * 
 */
final class SfynxCmfEvents
{
    /**
     * The HANDLER_REQUEST_CHANGERESPONSE_PREFIX_LOCALE_REDIRECTION event occurs when the prefixe locale in route has been enabled in config.yml.
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const HANDLER_REQUEST_CHANGERESPONSE_PREFIX_LOCALE_REDIRECTION = 'pi.handler.request.prefixlocale.changeresponse';   

    /**
     * The HANDLER_REQUEST_CHANGERESPONSE_SEO_REDIRECTION event occurs when the url is in the SEO_link file for 301 redirection .
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const HANDLER_REQUEST_CHANGERESPONSE_SEO_REDIRECTION = 'pi.handler.request.seo.changeresponse';    
    
    /**
     * The HANDLER_REQUEST_CHANGERESPONSE_NOSCOPE event occurs when the user is not in the scop configure in config.yml.
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const HANDLER_REQUEST_CHANGERESPONSE_NOSCOPE = 'pi.handler.request.noscope.changeresponse';
}
