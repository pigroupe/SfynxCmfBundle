<?php
declare(strict_types = 1);

namespace SfynxCmfContext\Infrastructure\Persistence\FieldsDefinition;

use Sfynx\DddBundle\Layer\Infrastructure\Persistence\FieldsDefinition\FieldsDefinitionAbstract;

/**
 * Class Comment
 *
 * @category SfynxCmfContext
 * @package Infrastructure
 * @subpackage Persistence\FieldsDefinition
 *
 * @copyright Copyright (c) 2016-2017, Aareon Group
 * @license http://www.pigroupe.com under a proprietary license
 * @version 1.1.1
 */
class Comment extends FieldsDefinitionAbstract
{
    /**
     * @var string[] Associative array where keys are parameters names from the request and values are db fields names.
     */
    protected $fields = [
        'id' => 'id',
        'pagetrans_id' => 'pagetrans_id',
        'user' => 'comment.user',
        'comment' => 'comment.comment',
        'email' => 'comment.email',
        'isApproved' => 'comment.isApproved',
        'position' => 'comment.position',
        'createdAt' => 'comment.createdAt',
        'updatedAt' => 'comment.updatedAt',
        'publishedAt' => 'comment.publishedAt',
        'archiveAt' => 'comment.archiveAt',
        'archived' => 'comment.archived',
        'enabled' => 'comment.enabled',
    ];
}
