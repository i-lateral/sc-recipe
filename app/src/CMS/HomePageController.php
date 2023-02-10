<?php

use SilverStripe\ORM\ArrayList;

class HomePageController extends PageController
{
    private static $allowed_actions = [];

    public function getRenderedSections()
    {
        $pages = $this->Sections()->sort('HomeSort','ASC');
		$sections = ArrayList::create();
		if ($pages) {
			foreach ($pages as $page) {
				$page->Layout = $page->renderWith([
                    $page->ClassName.'_homepage',
                    'Page_homepage',
                    'HomePage_section'
                ]);
				
				$sections->push($page);
			}
		}
		return $sections;
    }
}
