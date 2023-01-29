<?php

use SilverStripe\Assets\Image;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\AssetAdmin\Forms\UploadField;

class Page extends SiteTree
{
    private static $db = [
        'HomeSort' => 'Int'
    ];

    private static $has_one = [
        'HomePage' => HomePage::class
    ];
}
