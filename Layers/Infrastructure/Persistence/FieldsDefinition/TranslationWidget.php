<?php
declare(strict_types = 1);

namespace SfynxCmfContext\Infrastructure\Persistence\FieldsDefinition;

use Sfynx\DddBundle\Layer\Infrastructure\Persistence\FieldsDefinition\FieldsDefinitionAbstract;

/**
 * Class TranslationWidget
 *
 * @category SfynxCmfContext
 * @package Infrastructure
 * @subpackage Persistence\FieldsDefinition
 *
 * @copyright Copyright (c) 2016-2017, Aareon Group
 * @license http://www.pigroupe.com under a proprietary license
 * @version 1.1.1
 */
class TranslationWidget extends FieldsDefinitionAbstract
{
    /**
     * @var string[] Associative array where keys are parameters names from the request and values are db fields names.
     */
    protected $fields = [
        'id' => 'id',
        'widget_id' => 'widget_id',
        'content' => 'translationWidget.content',
        'createdAt' => 'translationWidget.createdAt',
        'updatedAt' => 'translationWidget.updatedAt',
        'publishedAt' => 'translationWidget.publishedAt',
        'archiveAt' => 'translationWidget.archiveAt',
        'archived' => 'translationWidget.archived',
        'enabled' => 'translationWidget.enabled',
        'langCode' => 'translationWidget.langCode',
    ];
}
