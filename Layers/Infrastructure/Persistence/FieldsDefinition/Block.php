<?php
declare(strict_types = 1);

namespace SfynxCmfContext\Infrastructure\Persistence\FieldsDefinition;

use Sfynx\CoreBundle\Layers\Infrastructure\Persistence\FieldsDefinition\Generalisation\FieldsDefinitionAbstract;

/**
 * Class Block
 *
 * @category SfynxCmfContext
 * @package Infrastructure
 * @subpackage Persistence\FieldsDefinition
 *
 * @copyright Copyright (c) 2016-2017, Aareon Group
 * @license http://www.pigroupe.com under a proprietary license
 * @version 1.1.1
 */
class Block extends FieldsDefinitionAbstract
{
    /**
     * @var string[] Associative array where keys are parameters names from the request and values are db fields names.
     */
    protected $fields = [
        'id' => 'id',
        'page_id' => 'page_id',
        'name' => 'block.name',
        'configCssClass' => 'block.configCssClass',
        'configXml' => 'block.configXml',
        'position' => 'block.position',
        'createdAt' => 'block.createdAt',
        'updatedAt' => 'block.updatedAt',
        'publishedAt' => 'block.publishedAt',
        'archiveAt' => 'block.archiveAt',
        'archived' => 'block.archived',
        'enabled' => 'block.enabled',
    ];
}
