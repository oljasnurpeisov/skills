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
                "step_by_step" => [],
                "for_authors" => ['description' => $request['for_authors_description_' . $language],
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

        $icons = [];

        if ($request->hasFile('icons')) {
            foreach ($request->file('icons') as $k => $file) {
                if (isset($file)) {
                    $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path() . '/images/advantages_icons/', $filename);
                    $icons[] = $filename;
                }
            }
        }
        $images = array_merge($request->icons_saved, $icons);

        foreach ($languages as $k => $language) {

            $data = [
                "step_by_step" => [],
                "for_authors" => ['description' => $request['for_authors_description_' . $language],
                    'btn_title' => $request['for_authors_btn_title_' . $language]],
                "advantages" => [],
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
                        'icon' => $images[$key],
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

            if ($request['theme_name_' . $language] != null){

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

        return redirect('/'.$lang.'/admin/static-pages/faq-index')->with('status', __('admin.notifications.record_stored'));

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
//        return $request;
        $languages = ['ru', 'kk', 'en'];

        foreach ($languages as $language) {

            $data = [
                "name" => $request['theme_name_' . $language],
            ];

            if (!empty($request['tab_name_' . $language])){
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

        return redirect('/'.$lang.'/admin/static-pages/faq-index')->with('status', __('admin.notifications.record_updated'));

    }

    public function faq_delete_theme($lang, $theme_key)
    {
        $item = Page::wherePageAlias('faq')->first();

        $languages = ['ru', 'kk', 'en'];

        foreach ($languages as $language) {
            $data_array = json_decode($item['data_' . $language]);
            unset($data_array[$theme_key]);

            $item['data_' . $language] = $data_array;
        }
        $item->save();

//        return $data_array;
        return redirect()->back()->with('status', __('admin.notifications.record_deleted'));
    }
}
