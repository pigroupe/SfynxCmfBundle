<?php
declare(strict_types = 1);

namespace SfynxCmfContext\Infrastructure\Persistence\FieldsDefinition;

use Sfynx\CoreBundle\Layers\Infrastructure\Persistence\FieldsDefinition\Generalisation\FieldsDefinitionAbstract;

/**
 * Class TranslationPage
 *
 * @category SfynxCmfContext
 * @package Infrastructure
 * @subpackage Persistence\FieldsDefinition
 *
 * @copyright Copyright (c) 2016-2017, Aareon Group
 * @license http://www.pigroupe.com under a proprietary license
 * @version 1.1.1
 */
class TranslationPage extends FieldsDefinitionAbstract
{
    /**
     * @var string[] Associative array where keys are parameters names from the request and values are db fields names.
     */
    protected $fields = [
        'id' => 'id',
        'page_id' => 'page_id',
        'langStatus' => 'translationPage.langStatus',
        'status' => 'translationPage.status',
        'isSecure' => 'translationPage.isSecure',
        'secureRoles' => 'translationPage.secureRoles',
        'isIndexable' => 'translationPage.isIndexable',
        'breadcrumb' => 'translationPage.breadcrumb',
        'slug' => 'translationPage.slug',
        'metaTitle' => 'translationPage.metaTitle',
        'metaKeywords' => 'translationPage.metaKeywords',
        'metaDescription' => 'translationPage.metaDescription',
        'surtitre' => 'translationPage.surtitre',
        'titre' => 'translationPage.titre',
        'soustitre' => 'translationPage.soustitre',
        'descriptif' => 'translationPage.descriptif',
        'chapo' => 'translationPage.chapo',
        'texte' => 'translationPage.texte',
        'ps' => 'translationPage.ps',
        'createdAt' => 'translationPage.createdAt',
        'updatedAt' => 'translationPage.updatedAt',
        'publishedAt' => 'translationPage.publishedAt',
        'archiveAt' => 'translationPage.archiveAt',
        'archived' => 'translationPage.archived',
        'enabled' => 'translationPage.enabled',
        'langCode' => 'translationPage.langCode',
    ];
}
