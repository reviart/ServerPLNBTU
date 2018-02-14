<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Bidang;
use App\Folder;
//use App\User;
use Auth;

class FolderController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function index()
    {
      $folders = Folder::with('user', 'bidang')->get();
      return view('admin.folder.index', compact('folders'));
    }

    public function create()
    {
      $folders = Folder::with('bidang')->get();
      return view('admin.folder.create', compact('folders'));
    }
}
