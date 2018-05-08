<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage Widget
 * @package Gedmo
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-03-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Gedmo\Navigation;

use Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Generalisation\TraitWidgetConvertParams;
use Sfynx\CrawlerBundle\Crawler\Generalisation\AbstractXmlCrawler;
use Sfynx\CrawlerBundle\Crawler\XmlCrawlerValidator;
use Sfynx\CrawlerBundle\Crawler\XmlCrawlerIterator;
use Sfynx\ToolBundle\Util\PiArrayManager;

/**
 * Listener Gedmo crawler
 *
 * @subpackage Widget
 * @package Gedmo
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class NavigationCrawler extends AbstractXmlCrawler
{
    use TraitWidgetConvertParams;

    protected $params;

    /**
     * Class constructor
     *
     * @param string $xml path|source to the xml
     * @param array  $params
     * @param array $optionConfiguration
     * @param boolean $isDestroyFile
     */
    public function __construct($xml, $params = [], $optionConfiguration = [], $isDestroyFile = false)
    {
        $this->params = $params;
        $validator = new XmlCrawlerValidator(__DIR__.'/navigation.xsd', $optionConfiguration);
        $this->setValidator($validator);
        $this->setIterator(new XmlCrawlerIterator($validator, $xml));

        parent::__construct($xml, $optionConfiguration, $isDestroyFile);
    }

    /**
     * {@inheritdoc}
     */
    public function getDataInArray(array $data = null)
    {
        if ($this->simpleXml) {
            return PiArrayManager::array_merge_recursive_distinct(
                PiArrayManager::array_merge_recursive_distinct($this->defaultParams($data), $this->transformer->xml2dataBuilder($this->simpleXml)),
                $this->convertParams($this->params)
            );
        }
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultParams(array $data = null)
    {
        return [
            "config" => [
                "widgets" => [
                    'cachable' => false,
                    'css' => null,
                    "gedmo"        => [
                        "controller" => '',
                        "params" => [
                            'template' => '',
                            'enabledonly' => false,
                            'category' => '',
                            'entity' => $data['entity'],
                            'node' => '',
                            'navigation' => [
                                'separatorClass' => '',
                                'separatorText' => '',
                                'separatorFirst' => false,
                                'separatorLast' => false,
                                'ulClass' => '',
                                'liClass' => '',
                                'counter' => true,
                                'query_function'  => null,
                                'searchFields' => '',
                                'liActiveClass'  => '',
                                'liInactiveClass'  => '',
                                'aActiveClass'  => '',
                                'aInactiveClass'  => '',
                                'enabledonly'  => '',
                                'routeActifMenu' => [
                                    'liActiveClass'   => 'menuContainer_highlight',
                                    'liInactiveClass' => '',
                                    'aActiveClass'    => 'tblanc',
                                    'aInactiveClass'  => 'tnoir',
                                    'enabledonly'     => "true",
                                ],
                                'lvlActifMenu' => [
                                    'liActiveClass'   => '',
                                    'liInactiveClass' => '',
                                    'aActiveClass'    => 'tnoir',
                                    'aInactiveClass'  => 'tnoir',
                                    'enabledonly'     => "true",
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}
