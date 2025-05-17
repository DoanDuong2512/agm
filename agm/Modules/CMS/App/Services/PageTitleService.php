<?php
// filepath: /Users/dungnm/Desktop/code/elcom/agm/Modules/CMS/App/Services/PageTitleService.php

namespace Modules\CMS\App\Services;

class PageTitleService
{
    /**
     * Set the page title in session
     *
     * @param string $title
     * @return void
     */
    public static function setTitle(string $title): void
    {
        session(['cms_page_title' => $title]);
    }

    /**
     * Get the current page title
     *
     * @param string $default
     * @return string
     */
    public static function getTitle(string $default = 'ELCOM CMS'): string
    {
        return session('cms_page_title', $default);
    }
}