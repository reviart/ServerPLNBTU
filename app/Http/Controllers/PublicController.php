<?php

namespace App\Http\Controllers;

use App\File;
use App\Folder;
use App\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PublicController extends Controller
{
  public function index()
  {
    $files = File::with('folder', 'bidang')->orderBy('folder_id', 'DESC')->get();
    $bidangs = Bidang::orderBy('name')->get();
    return view('pub_index', compact('files', 'bidangs'));
  }

  public function find(Request $request)
  {
    $id = $request->get('bidang_id');
    if ($id > 0) {
      $files = File::with('folder', 'bidang')->where('bidang_id', $id)->orderBy('folder_id', 'DESC')->get();
      $bidangs = Bidang::all();
      return view('pub_index', compact('files', 'bidangs'));
    } else {
      return redirect()->route('public.file')->with('warning', 'Gagal pencarian, anda belum memilih bidang!');
    }
  }

  public function download($id)
  {
    $files = File::with('bidang', 'folder')->where('id', $id)->first();
    $oldBidang = $files->bidang->name;
    $oldFolder = $files->folder->name;
    $oldName = $files->name;
    $path = "public/".$oldBidang."/".$oldFolder."/".$oldName;
    return response()->download(storage_path("/app/".$path));
  }

}
