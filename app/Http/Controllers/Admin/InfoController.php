<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InfoController extends Controller
{
    public function index()
    {
        dd(Auth::user());
        return view('admin.info.index', [
            'user' => Auth::user()
        ]);
    }
}
