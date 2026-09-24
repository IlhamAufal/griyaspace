<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

abstract class Controller
{
    protected function ajaxTableResponse(Request $request, string $view, array $data): View|string
    {
        if ($request->ajax()) {
            $data['isAjax'] = true;
            return view($view, $data);
        }

        return view($view, $data);
    }
}
