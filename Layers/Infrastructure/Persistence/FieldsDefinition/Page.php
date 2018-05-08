<?php
declare(strict_types = 1);

namespace SfynxCmfContext\Infrastructure\Persistence\FieldsDefinition;

use Sfynx\DddBundle\Layer\Infrastructure\Persistence\FieldsDefinition\FieldsDefinitionAbstract;

/**
 * Class Page
 *
 * @category SfynxCmfContext
 * @package Infrastructure
 * @subpackage Persistence\FieldsDefinition
 *
 * @copyright Copyright (c) 2016-2017, Aareon Group
 * @license http://www.pigroupe.com under a proprietary license
 * @version 1.1.1
 */
class Page extends FieldsDefinitionAbstract
{
    /**
     * @var string[] Associative array where keys are parameters names from the request and values are db fields names.
     */
    protected $fields = [
        'id' => 'id',
        'rubrique_id' => 'rubrique_id',
        'isCacheable' => 'page.isCacheable',
        'isPublic' => 'page.isPublic',
        'lifetime' => 'page.lifetime',
        'routeName' => 'page.routeName',
        'url' => 'page.url',
        'metaContentType' => 'page.metaContentType',
        'createdAt' => 'page.createdAt',
        'updatedAt' => 'page.updatedAt',
        'publishedAt' => 'page.publishedAt',
        'archiveAt' => 'page.archiveAt',
        'archived' => 'page.archived',
        'enabled' => 'page.enabled',
        'layout' => 'page.layout',
        'user' => 'page.user',
    ];
}
