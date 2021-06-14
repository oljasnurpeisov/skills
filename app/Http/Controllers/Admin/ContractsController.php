<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class ContractsController
 * @package App\Http\Controllers\Admin
 */
class ContractsController extends Controller
{
    /**
     * Просмотр всех договоров
     */
    public function index(): View
    {
        return view('admin.v2.pages.contracts.index', [
            'contracts' => Contract::with(['course', 'course.user'])->latest()->paginate()
        ]);
    }
}
