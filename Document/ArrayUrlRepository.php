<?php

namespace Avalanche\Bundle\SitemapBundle\Document;

use Avalanche\Bundle\SitemapBundle\Sitemap\Url;
use Avalanche\Bundle\SitemapBundle\Sitemap\UrlRepositoryInterface;

class ArrayUrlRepository implements UrlRepositoryInterface
{
    private $urls = array();

    public function add(Url $url)
    {
        $this->urls[$url->getLoc()] = $url;
    }

    public function findAllOnPage($page)
    {
        return array_slice(
            $this->urls,
            UrlRepositoryInterface::PER_PAGE_LIMIT * ($page - 1),
            UrlRepositoryInterface::PER_PAGE_LIMIT,
            true
        );
    }

    public function findOneByLoc($loc)
    {
        $url = null;
        if (isset($this->urls[$loc])) {
            $url = $this->urls[$loc];
        }
        return $url;
    }

    public function remove(Url $url)
    {
        unset($this->urls[$url->getLoc()]);
    }

    public function pages()
    {
        return max(ceil(count($this->urls) / UrlRepositoryInterface::PER_PAGE_LIMIT), 1);
    }

    public function flush()
    {
    }

    public function getLastmod($page)
    {
    }

}
