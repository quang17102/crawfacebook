<?php

namespace App\Http\Controllers;

use App\Exports\LinkFollowExport;
use Excel;
use Illuminate\Http\Request;

class ExportExcelController extends Controller
{
    //
    public function linkfollow(Request $request)
    {
        return Excel::download(
            new LinkFollowExport(['ids' => $request->ids ?? []]),
            'danhsachlinktheodoi.xlsx'
        );
    }
}
