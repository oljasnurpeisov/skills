<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use Illuminate\Http\Request;

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

        foreach ($languages as $language) {

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

                if (isset($request['advantages_name_' . $language][$key])) {
                    $data['advantages'][] = array(
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
}
