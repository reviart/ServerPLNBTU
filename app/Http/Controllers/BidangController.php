<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Bidang;
use App\Folder;
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
      $bidangs = Bidang::with('user')->orderBy('name')->get();
      return view('admin.bidang.index', compact('bidangs'));
      /*$id = $bidangs[0]->id;
      $c_folders = Folder::where('bidang_id', $id);
      hitung banyak folder yang memilih bidang tersebut
      countfolder $bidangs->id = */
      //$id = $bidangs[0]->id;
      //$c_folders = Folder::with('bidang')->count('bidang_id')->where('bidang_id', $id);
      //return "ok";
    }

    public function create()
    {
      return view('admin.bidang.create');
    }

    public function store(Request $request)
    {
      $name = strtoupper($request->get('name'));

      //checking the name first
      $bidang = Bidang::where('name', $name)->first();
      if ($bidang == NULL) {
        //making or storing ap
        $ap = $request->get('access_permission');
        if ($ap == NULL) {
          $ap = 777;
        }

        //create directory
        Storage::makeDirectory('public/'.$name, $ap);

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
      $newName = strtoupper($request->get('name'));
      //checking the name first
      $checkName = Bidang::where('name', $newName)->first();
      if ($checkName == NULL) {
        $bidangs = Bidang::find($id);
        $oldPath = $bidangs->path;
        $path = "public/".$newName;
        //update at db
        $bidangs->update([
          'name' => $newName,
          'path' => $path,
          'user_id' => Auth::user()->id
        ]);
        //update at directory
        Storage::move($oldPath, $path);

        return redirect()->route('bidang.index')->with('success', '1 data telah diubah!');
      } else {
        return redirect()->route('bidang.index')->with('warning', 'Data gagal diubah, nama bidang telah digunakan!');
      }
    }

    public function destroy($id)
    {
      $bidangs = Bidang::findOrFail($id);
      $path = Bidang::where('id', $id)->get();

      if (Storage::deleteDirectory($path[0]->path)) {
        //destroy multiple child first
        $folders = Folder::where('bidang_id', $id)->get(['id']);
        Folder::destroy($folders->toArray());

        //then destroy the parent
        $bidangs->delete();
        return redirect()->back()->with('success', 'Seluruh file dan directori berhasil dihapus!');
      }else {
        return redirect()->back()->with('warning', 'File dan directori gagal dihapus coba lagi!');
      }
    }
}
