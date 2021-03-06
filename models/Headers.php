<?php namespace Dizoo\PageHeaders\Models;

use Model;
use Cms\Classes\Page as Pg;
use Cms\Classes\Theme;

/**
 * Model
 */
class Headers extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    public $attachOne = [
        'image' => 'System\Models\File'
    ];
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    protected $pages = [];

    public function getPageidOptions() {

        $theme = Theme::getEditTheme();
        $pages = Pg::listInTheme($theme, true);
        $options = [];

        foreach($pages as $page) {
            $pageCheck = Headers::where('pageid', $page->id)->first();
            if (!$pageCheck || $page->id == $this->pageid) {
                $options[$page->id] = $page->title;
            }
        }

        if (class_exists('RainLab\Pages\Classes\PageList')) {
            $staticPages = new \RainLab\Pages\Classes\PageList($theme);
            foreach ($staticPages->listPages() as $name => $pageObject) {
                $staticCheck = Headers::where('pageid', $pageObject->id)->first();
                if (!$staticCheck || $pageObject->id == $this->pageid) {
                    $options[$pageObject->id] = $pageObject->title;
                }
            }
        }

        asort($options);
        return $options;
    }
    
    
    /**
     * @var string The database table used by the model.
     */
    public $table = 'dizoo_pageheaders_headers';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'image' => 'required',
        'pageid' => 'required|unique:dizoo_pageheaders_headers,pageid'
    ];
}
