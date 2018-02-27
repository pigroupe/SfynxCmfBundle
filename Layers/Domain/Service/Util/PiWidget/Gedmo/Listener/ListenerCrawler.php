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
namespace Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Gedmo\Listener;

use Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Generalisation\TraitWidgetConvertParams;
use Sfynx\CrawlerBundle\Crawler\Generalisation\AbstractXmlCrawler;
use Sfynx\CrawlerBundle\Crawler\XmlCrawlerValidator;
use Sfynx\CrawlerBundle\Crawler\XmlCrawlerIterator;

/**
 * Listener Gedmo crawler
 *
 * @subpackage Widget
 * @package Gedmo
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ListenerCrawler extends AbstractXmlCrawler
{
    use TraitWidgetConvertParams;

    /**
     * @var array
     */
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
        $validator = new XmlCrawlerValidator(__DIR__.'/listener.xsd', $optionConfiguration);
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
            return array_merge(
                (array) $this->simpleXml->widgets->gedmo->params,
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
        return [];
    }
}
