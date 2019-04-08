<?php

namespace gorriecoe\Robots;

use SilverStripe\ORM\DataExtension;
use SilverStripe\CMS\Model\SiteTree;
use gorriecoe\Robots\Robots;

/**
 * RobotsSitetree
 * Modifies robots.txt output dependant on sitetree.
 *
 * @package silverstripe-robots
 */
class RobotsSitetree extends DataExtension
{
    public function updateDisallowedUrls(&$urls)
    {
        if (Robots::config()->disallow_unsearchable) {
            foreach (SiteTree::get()->filter('ShowInSearch', false) as $page) {
                $link = $page->Link();
				// Don't disallow home page, no RedirectorPage with RedirectionType External
				if ($link !== '/' && $page->RedirectionType != 'External') {
                    $urls[] = $link;
                }
            }
        }
    }
}
