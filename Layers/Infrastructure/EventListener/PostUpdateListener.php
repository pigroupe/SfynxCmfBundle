<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Entity
 * @package    EventListener
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2011-01-27
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Infrastructure\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Sfynx\CmfBundle\Layers\Infrastructure\EventListener\CoreListener;

/**
 * Custom post update entities listener.
 * The postUpdate event occurs after the database update operations to entity data.
 * It is not called for a DQL UPDATE statement.
 *
 * @subpackage   Entity
 * @package    EventListener
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PostUpdateListener extends CoreListener
{
    /**
     * Constructs a new instance of SecurityListener.
     *
     * @param ContainerInterface        $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }
        
    /**
     * Methos which will be called when the event is thrown.
     *
     *
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        // we set the PostUpdate heritage roles management
        $this->_Heritage_roles($eventArgs);
        
        // we set the PostUpdate languages management
        $this->_locales_language_json_file($eventArgs);
                
        // we set the PostUpdate block page management
        $this->_Create_Block_Page($eventArgs);
        
        // we set the PostUpdate json page information
        $this->_JsonFilePage($eventArgs, 'update');          
        
        // We remove json file Etag of Page and Widget.
        $this->_deleteJsonFileEtag($eventArgs, false);
        
        // Method which will be called when we remove twig cached file of Page, Widget and translationWidget template.
        $this->_TwigCache($eventArgs);
        
        // we set the PostUpdate Cache Url Generator management
        $this->_updateCacheUrlGenerator($eventArgs);
        
        // we set the PostPersist search lucene management
        $this->_Search_lucene($eventArgs, 'update');        
        
        // we persist all entities in the $this->persistUpdates array;
        $this->_persistEntities($eventArgs);
    }
    
}