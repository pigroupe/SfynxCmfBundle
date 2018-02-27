<?php
declare(strict_types = 1);

namespace SfynxCmfContext\Infrastructure\Persistence\FieldsDefinition;

use Sfynx\DddBundle\Layer\Infrastructure\Persistence\FieldsDefinition\FieldsDefinitionAbstract;

/**
 * Class Tag
 *
 * @category SfynxCmfContext
 * @package Infrastructure
 * @subpackage Persistence\FieldsDefinition
 *
 * @copyright Copyright (c) 2016-2017, Aareon Group
 * @license http://www.pigroupe.com under a proprietary license
 * @version 1.1.1
 */
class Tag extends FieldsDefinitionAbstract
{
    /**
     * @var string[] Associative array where keys are parameters names from the request and values are db fields names.
     */
    protected $fields = [
        'id' => 'id',
        'groupname' => 'tag.groupname',
        'groupnameother' => 'tag.groupnameother',
        'name' => 'tag.name',
        'color' => 'tag.color',
        'hicolor' => 'tag.hicolor',
        'weight' => 'tag.weight',
        'createdAt' => 'tag.createdAt',
        'updatedAt' => 'tag.updatedAt',
        'publishedAt' => 'tag.publishedAt',
        'archiveAt' => 'tag.archiveAt',
        'archived' => 'tag.archived',
        'enabled' => 'tag.enabled',
    ];
}
