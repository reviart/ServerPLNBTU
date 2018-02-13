<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Illuminate\Http\Request;
use App\Bidang;
use Auth;
use DB;

class BidangController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function index()
    {
      $bidangs = Bidang::with('user')->get();
      return view('admin.bidang.index', compact('bidangs'));
    }

    public function create()
    {
      return view('admin.bidang.create');
    }

    public function store(Request $request)
    {
      $name = strtoupper($request->get('name'));
      $ap = $request->get('access_permission');
      //checking the name first
      $bidang = Bidang::where('name', '=', $name)->first();
      if ($bidang == NULL) {
        //create directory
        $result = Storage::makeDirectory('public/'.$name, $ap);

        //save to db
        $object = new Bidang;
        $object->name = $name;
        $object->path = "public/".$name;
        $object->access_permission = $ap;
        $object->user_id = Auth::user()->id;
        $object->save();

        return redirect()->route('bidang.index')->with('success', '1 data berhasil ditambah!');
      } else {
        return redirect()->route('bidang.index')->with('warning', 'Data gagal ditambah, buat nama bidang baru!');
      }
    }

    public function show($id)
    {
      $bidangs = Bidang::where('id', $id)->first();
      return view('admin.bidang.edit', compact('bidangs'));
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {
      //if has file using count file
      $bidangs = Bidang::findOrFail($id);
      $bidangs->delete();
      $name = DB::table('bidangs')->select('name')->where('id', $id)->get();
      Storage::deleteDirectory('public/'.$name[0]->name)

      return redirect()->back()->with('success', '1 data berhasil dihapus!');
    }

    public function destroyAll($id)
    {
      $bidangs = Bidang::findOrFail($id);
      $bidangs->delete();
      $name = DB::table('bidangs')->select('name')->where('id', $id)->get();
      Storage::deleteDirectory('public/'.$name[0]->name)

      return redirect()->back()->with('success', '1 data berhasil dihapus!');
    }
}
