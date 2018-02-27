<?php


namespace Sfynx\CmfBundle\Dumper;

use Sfynx\CmfBundle\Layers\Domain\Entity\Page;

abstract class PageDumper
{
    /**
     * @var page
     */
    private $page;

    /**
     * Constructor.
     *
     * @param RouteCollection $routes The RouteCollection to dump
     */
    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    /**
     * {@inheritdoc}
     */
    public function getPage()
    {
        return $this->page;
    }
}
