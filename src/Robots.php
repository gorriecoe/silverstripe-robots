<?php

namespace gorriecoe\Robots;

use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Control\Controller;

/**
 * Robots
 * Provides robots.txt functionality
 *
 * @package silverstripe-robots
 */
class Robots extends Controller
{
    /**
     * @var string|array
     */
    private static $sitemap = '/sitemap.xml';

    /**
     * @var array
     */
    private static $disallowed_urls = [
        '/admin'
    ];

    /**
     * @var array
     */
    private static $allowed_urls = [];

    /**
     * @var boolean
     */
    private static $disallow_unsearchable = true;

    /**
     * Determines if this is a public site
     *
     * @return boolean flag indicating if this robots is for a public site
     */
    protected function isLive()
    {
        return Director::isLive();
    }

    /**
     * Belongs_to relationship
     * @var array
     */
    private static $casting = [
        'Sitemap' => 'HTMLFragment',
        'Disallow' => 'HTMLFragment',
        'Allow' => 'HTMLFragment'
    ];

    /**
     * Generates the response containing the robots.txt content
     *
     * @return HTTPResponse
     */
    public function index()
    {
        return HTTPResponse::create(
            $this->renderWith('Robots'),
            200
        )
        ->addHeader(
            'Content-Type',
            'text/plain; charset="utf-8"'
        );
    }

    /**
     * Renders the sitemap link reference
     *
     * @return string
     */
    public function getSitemap()
    {

        // No sitemap if not public
        if (!$this->isLive()) {
            return '';
        }

        // Check if sitemap is configured
        $sitemaps = $this->config()->get('sitemap');
        if (!isset($sitemaps)) {
            return '';
        }

        if (is_string($sitemaps)) {
            $sitemaps = [$sitemaps];
        }

        $this->extend('updateSitemap', $sitemaps);

        $return = [];
        foreach ($sitemaps as $key => $link) {
            $return[] = sprintf(
                'Sitemap: %s',
                Director::absoluteURL($link)
            );
        }
        return implode("\n", $return);
    }

    /**
     * Returns an array of disallowed URLs
     *
     * @return array
     */
    protected function disallowedUrls()
    {
        if (!$this->isLive()) {
            return ['/'];
        }
        $urls = (array) $this->config()->get('disallowed_urls');
        $this->extend('updateDisallowedUrls', $urls);
        return array_unique($urls);
    }

    /**
     * Renders the list of disallowed pages
     *
     * @return string
     */
    public function getDisallow()
    {
        // List only disallowed urls
        $return = [];
        foreach ($this->disallowedUrls() as $url) {
            $return[] = sprintf(
                'Disallow: %s',
                $url
            );
        }
        return implode("\n", $return);
    }

    /**
     * Returns an array of allowed URLs
     *
     * @return array
     */
    protected function allowedUrls()
    {
        if (!$this->isLive()) {
            return [];
        }
        $urls = (array) $this->config()->get('allowed_urls');
        $this->extend('updateAllowedUrls', $urls);
        return $urls;
    }

    /**
     * Renders the list of allowed pages, if any
     *
     * @return string
     */
    public function getAllow()
    {
        $return = [];
        foreach ($this->allowedUrls() as $url) {
            $return[] = sprintf(
                'Allow: %s',
                $url
            );
        }
        return implode("\n", $return);
    }
}
