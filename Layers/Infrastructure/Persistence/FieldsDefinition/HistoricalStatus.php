<?php
declare(strict_types = 1);

namespace SfynxCmfContext\Infrastructure\Persistence\FieldsDefinition;

use Sfynx\DddBundle\Layer\Infrastructure\Persistence\FieldsDefinition\FieldsDefinitionAbstract;

/**
 * Class HistoricalStatus
 *
 * @category SfynxCmfContext
 * @package Infrastructure
 * @subpackage Persistence\FieldsDefinition
 *
 * @copyright Copyright (c) 2016-2017, Aareon Group
 * @license http://www.pigroupe.com under a proprietary license
 * @version 1.1.1
 */
class HistoricalStatus extends FieldsDefinitionAbstract
{
    /**
     * @var string[] Associative array where keys are parameters names from the request and values are db fields names.
     */
    protected $fields = [
        'id' => 'id',
        'pagetrans_id' => 'pagetrans_id',
        'status' => 'historicalStatus.status',
        'comment' => 'historicalStatus.comment',
        'createdAt' => 'historicalStatus.createdAt',
        'updatedAt' => 'historicalStatus.updatedAt',
        'publishedAt' => 'historicalStatus.publishedAt',
        'archiveAt' => 'historicalStatus.archiveAt',
        'archived' => 'historicalStatus.archived',
        'enabled' => 'historicalStatus.enabled',
    ];
}
