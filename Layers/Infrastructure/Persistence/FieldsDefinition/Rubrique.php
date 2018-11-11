<?php
declare(strict_types = 1);

namespace SfynxCmfContext\Infrastructure\Persistence\FieldsDefinition;

use Sfynx\CoreBundle\Layers\Infrastructure\Persistence\FieldsDefinition\Generalisation\FieldsDefinitionAbstract;

/**
 * Class Rubrique
 *
 * @category SfynxCmfContext
 * @package Infrastructure
 * @subpackage Persistence\FieldsDefinition
 *
 * @copyright Copyright (c) 2016-2017, Aareon Group
 * @license http://www.pigroupe.com under a proprietary license
 * @version 1.1.1
 */
class Rubrique extends FieldsDefinitionAbstract
{
    /**
     * @var string[] Associative array where keys are parameters names from the request and values are db fields names.
     */
    protected $fields = [
        'id' => 'id',
        'parent_id' => 'parent_id',
        'titre' => 'rubrique.titre',
        'descriptif' => 'rubrique.descriptif',
        'texte' => 'rubrique.texte',
        'position' => 'rubrique.position',
        'createdAt' => 'rubrique.createdAt',
        'updatedAt' => 'rubrique.updatedAt',
        'publishedAt' => 'rubrique.publishedAt',
        'archiveAt' => 'rubrique.archiveAt',
        'archived' => 'rubrique.archived',
        'enabled' => 'rubrique.enabled',
        'lft' => 'rubrique.lft',
        'rgt' => 'rubrique.rgt',
        'lvl' => 'rubrique.lvl',
        'root' => 'rubrique.root',
    ];
}
