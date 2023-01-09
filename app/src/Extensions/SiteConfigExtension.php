<?php

namespace App\Extensions;

use ContactPage;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\DropdownField;

class SiteConfigExtension extends DataExtension
{
    private static $db = [
        'TileBackground' => 'Boolean'
    ];

    private static $has_one = [
        'Logo' => Image::class,
        'Icon' => Image::class,
        'Background' => Image::class,
        'ContactPage' => ContactPage::class
    ];

    private static $owns = [
        'Logo',
        'Icon',
        'Background'
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab(
            'Root.Main',
            [
                UploadField::create(
                    "Logo",
                    "Site Logo"
                ),
                UploadField::create(
                    "Icon",
                    "Site Icon"
                )->setRightTitle('Used for favicon and touch icons - this must be a .png or .gif')
                ->setAllowedExtensions(['png', 'gif']),
                UploadField::create(
                    "Background",
                    "Site Background"
                ),
                CheckboxField::create("TileBackground"),
            ],
            'Tagline'
        );

        $fields->addFieldToTab(
            'Root.Main',
            DropdownField::create(
                "ContactPageID",
                "Link to 'contact' page"
            )->setSource(ContactPage::get()->map())
        );
    }
}
