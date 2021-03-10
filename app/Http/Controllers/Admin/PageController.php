<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PageController extends Controller
{
    public function main()
    {

        $item = Page::wherePageAlias('index')->first();

        return view('admin.v2.pages.static_pages.main', [
            'item' => $item
        ]);
    }

    public function mainUpdate(Request $request)
    {
        $languages = ['ru', 'kk', 'en'];
        $item = Page::wherePageAlias('index')->first();

        foreach ($languages as $language) {

            $data = [
                'main_banner' => ['title' => $request['banner_title_' . $language],
                    'teaser' => $request['banner_teaser_' . $language],
                    'image' => $request['avatar']],
                'step_by_step' => [],
                'for_authors' => ['description' => $request['for_authors_description_' . $language],
                    'btn_title' => $request['for_authors_btn_title_' . $language]]
            ];

            foreach ($request['steps_' . $language] as $key => $step) {
                if (isset($request['steps_' . $language][$key])) {
                    $data['step_by_step'][] = array(
                        'name' => $request['steps_' . $language][$key],
                        'description' => $request['descriptions_' . $language][$key],
                    );
                }
            }
            $item['data_' . $language] = json_encode($data);
        }

        $item->save();

        return back()->with('status', __('admin.notifications.update_success'));
    }

    public function forAuthors()
    {

        $item = Page::wherePageAlias('for_authors')->first();

        return view('admin.v2.pages.static_pages.for_authors', [
            'item' => $item
        ]);
    }

    public function forAuthorsUpdate(Request $request)
    {

        $languages = ['ru', 'kk', 'en'];
        $item = Page::wherePageAlias('for_authors')->first();

        foreach ($languages as $k => $language) {

            $data = [
                "step_by_step" => [],
                "for_authors" => ['description' => $request['for_authors_description_' . $language],
                    'btn_title' => $request['for_authors_btn_title_' . $language]],
                "advantages" => [],
                "for_authors_banner" => ['title' => $request['banner_title_' . $language],
                    'teaser' => $request['banner_teaser_' . $language],
                    'image' => $request['avatar']]
            ];

            foreach ($request['steps_' . $language] as $key => $step) {
                if (isset($request['steps_' . $language][$key])) {
                    $data['step_by_step'][] = array(
                        'name' => $request['steps_' . $language][$key],
                        'description' => $request['descriptions_' . $language][$key],
                    );
                }
            }


            foreach ($request['advantages_name_' . $language] as $key => $advantage) {
                if (isset($request['advantages_name_' . $language][$key])) {

                    $data['advantages'][] = array(
                        'icon' => $request['icon_' . $language . '_' . $key],
                        'name' => $request['advantages_name_' . $language][$key],
                        'description' => $request['advantages_descriptions_' . $language][$key],
                    );

                }
            }
            $item['data_' . $language] = json_encode($data);
        }

        $item->save();

        return back()->with('status', __('admin.notifications.update_success'));
    }

    public function faq_index()
    {
        $items = Page::wherePageAlias('faq')->first();

        $themes = [];

        if ($items->data_ru != null) {
            foreach (json_decode($items->data_ru) as $key => $item) {
                $themes[$key][] = $item->name;
            }
            foreach (json_decode($items->data_kk) as $key => $item) {
                $themes[$key][] = $item->name;
            }
            foreach (json_decode($items->data_en) as $key => $item) {
                $themes[$key][] = $item->name;
            }
        }

        return view('admin.v2.pages.static_pages.faq_index', [
            'items' => $themes
        ]);
    }

    public function create_faq_theme()
    {
        $item = Page::wherePageAlias('faq')->first();

        return view('admin.v2.pages.static_pages.faq_create', [
            'item' => $item
        ]);
    }

    public function store_faq_theme($lang, Request $request, Page $item)
    {
        $languages = ['ru', 'kk', 'en'];

        foreach ($languages as $language) {

            if ($request['theme_name_' . $language] != null) {

                $data = [
                    "name" => $request['theme_name_' . $language],
                ];


                foreach ($request['tab_name_' . $language] as $key => $step) {
                    if (isset($request['tab_name_' . $language][$key])) {
                        $data['tabs'][] = array(
                            'name' => $request['tab_name_' . $language][$key],
                            'description' => $request['tab_description_' . $language][$key],
                        );
                    }
                }

                $data_array = json_decode($item['data_' . $language]);
                $data_array[] = $data;

                $item['data_' . $language] = $data_array;
            }
        }
        $item->save();

        return redirect('/' . $lang . '/admin/static-pages/faq-index')->with('status', __('admin.notifications.record_stored'));

    }

    public function faq_view($lang, $theme_key)
    {
        $item = Page::wherePageAlias('faq')->first();

        return view('admin.v2.pages.static_pages.faq', [
            'item' => $item,
            'theme_key' => $theme_key
        ]);
    }

    public function update_faq_theme($lang, Request $request, Page $item, $theme_key)
    {
        $languages = ['ru', 'kk', 'en'];

        foreach ($languages as $language) {
            if ($request['theme_name_' . $language] != null) {
                $data = [
                    "name" => $request['theme_name_' . $language],
                ];

                if (!empty($request['tab_name_' . $language])) {
                    foreach ($request['tab_name_' . $language] as $key => $step) {
                        if (isset($request['tab_name_' . $language][$key]) && $request['tab_name_' . $language][$key] != '' && $request['tab_name_' . $language][$key] != null) {
                            $data['tabs'][] = array(
                                'name' => $request['tab_name_' . $language][$key],
                                'description' => $request['tab_description_' . $language][$key],
                            );
                        }
                    }
                }
                $data_array = json_decode($item['data_' . $language]);
                $data_array[$theme_key] = $data;

                $item['data_' . $language] = $data_array;
            }
        }
        $item->save();

        return redirect('/' . $lang . '/admin/static-pages/faq-index')->with('status', __('admin.notifications.record_updated'));

    }

    public function faq_delete_theme($lang, $theme_key)
    {
        $item = Page::wherePageAlias('faq')->first();

        $languages = ['ru', 'kk', 'en'];

        foreach ($languages as $language) {
            $data_array = json_decode($item['data_' . $language]);
            unset($data_array[$theme_key]);

            $item['data_' . $language] = array_values($data_array);
        }
        $item->save();

        return redirect()->back()->with('status', __('admin.notifications.record_deleted'));
    }

    public function courseCatalog()
    {
        $item = Page::wherePageAlias('course_catalog')->first();

        return view('admin.v2.pages.static_pages.course_catalog', [
            'item' => $item,
        ]);
    }

    public function courseCatalogUpdate(Request $request)
    {
        $languages = ['ru', 'kk', 'en'];
        $item = Page::wherePageAlias('course_catalog')->first();

        foreach ($languages as $language) {

            $data = [
                'course_catalog' => ['link' => $request['image_link_' . $language],
                    'image' => $request['image_' . $language]]
            ];

            $item['data_' . $language] = json_encode($data);
        }

        $item->save();

        return back()->with('status', __('admin.notifications.update_success'));
    }

    public function help_index()
    {
        $items = Page::wherePageAlias('help')->first();

        $themes = [];

        if ($items->data_ru != null) {
            foreach (json_decode($items->data_ru) as $key => $item) {
                $themes[$key][] = $item->name;
            }
            foreach (json_decode($items->data_kk) as $key => $item) {
                $themes[$key][] = $item->name;
            }
            foreach (json_decode($items->data_en) as $key => $item) {
                $themes[$key][] = $item->name;
            }
        }

        return view('admin.v2.pages.static_pages.help_index', [
            'items' => $themes
        ]);
    }

    public function create_help_theme()
    {
        $item = Page::wherePageAlias('help')->first();

        return view('admin.v2.pages.static_pages.help_create', [
            'item' => $item
        ]);
    }

    public function store_help_theme($lang, Request $request, Page $item)
    {
        $languages = ['ru', 'kk', 'en'];

        foreach ($languages as $language) {

            if ($request['theme_name_' . $language] != null) {

                $data = [
                    "name" => $request['theme_name_' . $language],
                ];


                foreach ($request['tab_name_' . $language] as $key => $step) {
                    if (isset($request['tab_name_' . $language][$key])) {
                        $data['tabs'][] = array(
                            'name' => $request['tab_name_' . $language][$key],
                            'description' => $request['tab_description_' . $language][$key],
                        );
                    }
                }
                $data_array = json_decode($item['data_' . $language]);
                $data_array[] = $data;

                $item['data_' . $language] = $data_array;
            }
        }
        $item->save();

        return redirect('/' . $lang . '/admin/static-pages/help-index')->with('status', __('admin.notifications.record_stored'));

    }

    public function help_view($lang, $theme_key)
    {
        $item = Page::wherePageAlias('help')->first();

        return view('admin.v2.pages.static_pages.help', [
            'item' => $item,
            'theme_key' => $theme_key
        ]);
    }

    public function update_help_theme($lang, Request $request, Page $item, $theme_key)
    {
        $languages = ['ru', 'kk', 'en'];

        foreach ($languages as $language) {

            $data = [
                "name" => $request['theme_name_' . $language],
            ];

            if (!empty($request['tab_name_' . $language])) {
                foreach ($request['tab_name_' . $language] as $key => $step) {
                    if (isset($request['tab_name_' . $language][$key])) {
                        $data['tabs'][] = array(
                            'name' => $request['tab_name_' . $language][$key],
                            'description' => $request['tab_description_' . $language][$key],
                        );
                    }
                }
            }
            $data_array = json_decode($item['data_' . $language]);
            $data_array[$theme_key] = $data;

            $item['data_' . $language] = $data_array;
        }
        $item->save();

        return redirect('/' . $lang . '/admin/static-pages/help-index')->with('status', __('admin.notifications.record_updated'));

    }

    public function help_delete_theme($lang, $theme_key)
    {
        $item = Page::wherePageAlias('help')->first();

        $languages = ['ru', 'kk', 'en'];

        foreach ($languages as $language) {
            $data_array = json_decode($item['data_' . $language]);
            unset($data_array[$theme_key]);

            $item['data_' . $language] = $data_array;
        }
        $item->save();

        return redirect()->back()->with('status', __('admin.notifications.record_deleted'));
    }

    public function calculator_view($lang)
    {
        $item = Page::wherePageAlias('calculator')->first();

        return view('admin.v2.pages.static_pages.calculator', [
            'item' => $item
        ]);
    }

    public function calculator_update(Request $request)
    {
        $languages = ['ru', 'kk', 'en'];
        $item = Page::wherePageAlias('calculator')->first();

        foreach ($languages as $language) {

            $data = [
                'calculator' => ['teaser' => $request['teaser_' . $language]]
            ];

            $item['data_' . $language] = json_encode($data);
        }

        $item->save();

        return back()->with('status', __('admin.notifications.update_success'));
    }

    public function phpInfo()
    {
        phpinfo();
    }
}
