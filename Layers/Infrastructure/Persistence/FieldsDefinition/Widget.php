<?php
declare(strict_types = 1);

namespace SfynxCmfContext\Infrastructure\Persistence\FieldsDefinition;

use Sfynx\CoreBundle\Layers\Infrastructure\Persistence\FieldsDefinition\Generalisation\FieldsDefinitionAbstract;

/**
 * Class Widget
 *
 * @category SfynxCmfContext
 * @package Infrastructure
 * @subpackage Persistence\FieldsDefinition
 *
 * @copyright Copyright (c) 2016-2017, Aareon Group
 * @license http://www.pigroupe.com under a proprietary license
 * @version 1.1.1
 */
class Widget extends FieldsDefinitionAbstract
{
    /**
     * @var string[] Associative array where keys are parameters names from the request and values are db fields names.
     */
    protected $fields = [
        'id' => 'id',
        'block_id' => 'block_id',
        'plugin' => 'widget.plugin',
        'action' => 'widget.action',
        'isCacheable' => 'widget.isCacheable',
        'isPublic' => 'widget.isPublic',
        'lifetime' => 'widget.lifetime',
        'isTemplatingCache' => 'widget.isTemplatingCache',
        'isAjax' => 'widget.isAjax',
        'isSluggify' => 'widget.isSluggify',
        'configCssClass' => 'widget.configCssClass',
        'isSecure' => 'widget.isSecure',
        'secureRoles' => 'widget.secureRoles',
        'configXml' => 'widget.configXml',
        'position' => 'widget.position',
        'createdAt' => 'widget.createdAt',
        'updatedAt' => 'widget.updatedAt',
        'publishedAt' => 'widget.publishedAt',
        'archiveAt' => 'widget.archiveAt',
        'archived' => 'widget.archived',
        'enabled' => 'widget.enabled',
    ];
}
