<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Bidang;
use App\Folder;
use App\File;
use Auth;

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
    }

    public function create()
    {
      return view('admin.bidang.create');
    }

    public function store(Request $request)
    {
      //get name then make it upper
      $name = strtoupper($request->get('name'));
      //checking the name first
      $bidang = Bidang::where('name', $name)->first();

      //if the new name is null in the db then you can save the data
      if ($bidang == NULL) {
        //making or storing access_permission with 755
        $ap = $request->get('access_permission');
        if ($ap == NULL) {
          $ap = 775;
        }

        //create directory
        Storage::makeDirectory('public/'.$name, $ap);

        //bidang's data save to db
        $object = new Bidang;
        $object->name = $name;
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
      //get new name
      $newName = strtoupper($request->get('name'));
      //checking the name first
      $checkName = Bidang::where('name', $newName)->first();

      //if the new name is null in the db then you can update the data
      if ($checkName == NULL) {
        //finding the path
        $bidangs = Bidang::find($id);
        $oldName = $bidangs->name;
        $oldPath = "public/".$oldName;
        $path = "public/".$newName;

        //update to the db
        $bidangs->update([
          'name' => $newName,
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
      //finding the path
      $bidangs = Bidang::findOrFail($id);
      $name = $bidangs->name;
      $path = "public/".$name;

      //if the directory (bidang) can be deleted then destroy files and folders data
      if (Storage::deleteDirectory($path)) {
        //destroy multiple child first (file)
        $files = File::where('bidang_id', $id)->get(['id']);
        File::destroy($files->toArray());

        //destroy folder (folder)
        $folders = Folder::where('bidang_id', $id)->get(['id']);
        Folder::destroy($folders->toArray());

        //then destroy the parent (bidang)
        $bidangs->delete();
        return redirect()->back()->with('success', 'Seluruh file dan directori berhasil dihapus!');
      }else {
        return redirect()->back()->with('warning', 'File dan directori gagal dihapus coba lagi!');
      }
    }
}
