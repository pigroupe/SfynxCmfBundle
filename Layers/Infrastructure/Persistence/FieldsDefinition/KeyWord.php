<?php
declare(strict_types = 1);

namespace SfynxCmfContext\Infrastructure\Persistence\FieldsDefinition;

use Sfynx\CoreBundle\Layers\Infrastructure\Persistence\FieldsDefinition\Generalisation\FieldsDefinitionAbstract;

/**
 * Class KeyWord
 *
 * @category SfynxCmfContext
 * @package Infrastructure
 * @subpackage Persistence\FieldsDefinition
 *
 * @copyright Copyright (c) 2016-2017, Aareon Group
 * @license http://www.pigroupe.com under a proprietary license
 * @version 1.1.1
 */
class KeyWord extends FieldsDefinitionAbstract
{
    /**
     * @var string[] Associative array where keys are parameters names from the request and values are db fields names.
     */
    protected $fields = [
        'id' => 'id',
        'groupname' => 'keyWord.groupname',
        'groupnameother' => 'keyWord.groupnameother',
        'name' => 'keyWord.name',
        'createdAt' => 'keyWord.createdAt',
        'updatedAt' => 'keyWord.updatedAt',
        'publishedAt' => 'keyWord.publishedAt',
        'archiveAt' => 'keyWord.archiveAt',
        'archived' => 'keyWord.archived',
        'enabled' => 'keyWord.enabled',
    ];
}
