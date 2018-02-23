<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Bidang;
use App\Folder;
use App\File;
use Auth;

class FolderController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function index()
    {
      $folders = Folder::with('user', 'bidang')->orderBy('name')->get();
      $bidangs = Bidang::orderBy('name')->get();
      return view('admin.folder.index', compact('folders', 'bidangs'));
    }

    public function find(Request $request)
    {
      //get selected id
      $id = $request->get('bidang_id');

      //if the id is not 0 or below from 0 then id can be processed to the next step
      if ($id > 0) {
        //get a file where bidangid equals with selected id
        $folders = Folder::with('user', 'bidang')->where('bidang_id', $id)->orderBy('name')->get();
        $bidangs = Bidang::orderBy('name')->get();
        return view('admin.folder.index', compact('folders', 'bidangs'));
      } else {
        return redirect()->route('folder.index')->with('warning', 'Gagal pencarian, anda belum memilih bidang!');
      }
    }

    public function create()
    {
      $bidangs = Bidang::orderBy('name')->get();
      return view('admin.folder.create', compact('bidangs'));
    }

    public function store(Request $request)
    {
      //get new name
      $name = strtoupper($request->get('name'));

      //checking all the data is empty
      $folders = Folder::where('name', $name)->first();
      $bidangid_new = $request->get('bidang_id');

      //if folder which we store already exist, you can't store it twice
      if ($folders == NULL) {
        $bidang_id = $bidangid_new;
        $bidang_name = Bidang::where('id', $bidang_id)->first();
        $bidang_name = $bidang_name->name;

        //making or storing ap
        $ap = $request->get('access_permission');
        if ($ap == NULL) {
          $ap = 775;
        }

        //create directory
        Storage::makeDirectory('public/'.$bidang_name.'/'.$name, $ap);

        //save to db
        $object = new Folder;
        $object->name = $name;
        $object->access_permission = $ap;
        $object->user_id = Auth::user()->id;
        $object->bidang_id = $bidang_id;
        $object->save();

        return redirect()->route('folder.index')->with('success', '1 data berhasil ditambah!');
      }
      //if folder is not null and folderbidangid is not same bidangidnew then you can proccess it
      elseif (($folders->name != NULL) && ($folders->bidang_id != $bidangid_new)) {
        $bidang_id = $bidangid_new;
        $bidang_name = Bidang::where('id', $bidang_id)->first();
        $bidang_name = $bidang_name->name;

        //making or storing ap
        $ap = $request->get('access_permission');
        if ($ap == NULL) {
          $ap = 775;
        }

        //create directory
        Storage::makeDirectory('public/'.$bidang_name.'/'.$name, $ap);

        //save to db
        $object = new Folder;
        $object->name = $name;
        $object->access_permission = $ap;
        $object->user_id = Auth::user()->id;
        $object->bidang_id = $bidang_id;
        $object->save();

        return redirect()->route('folder.index')->with('success', '1 data berhasil ditambah!');
      }
      else {
        return redirect()->route('folder.index')->with('warning', 'Data gagal ditambah, buat nama folder baru!');
      }
    }

    public function show($id)
    {
      $folders = Folder::with('bidang')->where('id', $id)->first();
      $lists = Bidang::orderBy('name')->get();
      return view('admin.folder.edit', compact('folders', 'lists'));
    }

    public function update(Request $request, $id)
    {
      //checking the name and bidang_id
      $newFolderName = strtoupper($request->get('name'));
      $checkName = Folder::where('name', $newFolderName)->first();

      $newBidangIdFolder = $request->get('bidang_id');
      if ($checkName == NULL) {
        //get $oldPath to change location or rename di
        $folders = Folder::with('bidang')->where('id', $id)->first();
        $oldName = $folders->name;
        $oldBidang = $folders->bidang->name;
        $oldFolderPath = "public/".$oldBidang."/".$oldName;

        //get bidang name
        $tmp = Bidang::where('id', $newBidangIdFolder)->first();
        $newBidangNameFolder = $tmp->name;
        $newPathFolder = "public/".$newBidangNameFolder."/".$newFolderName;

        //update at db
        $folders->update([
          'name' => $newFolderName,
          'user_id' => Auth::user()->id,
          'bidang_id' => $newBidangIdFolder
        ]);

        //update at directory
        Storage::move($oldFolderPath, $newPathFolder);

        return redirect()->route('folder.index')->with('success', '1 data telah diubah!');
      }
      elseif (($checkName->name != NULL) && ($checkName->id != $newBidangIdFolder)) {
        //get $oldPath to change location or rename di
        $folders = Folder::with('bidang')->where('id', $id)->first();
        $oldName = $folders->name;
        $oldBidang = $folders->bidang->name;
        $oldFolderPath = "public/".$oldBidang."/".$oldName;

        //get bidang name
        $tmp = Bidang::where('id', $newBidangIdFolder)->first();
        $newBidangNameFolder = $tmp->name;
        $newPathFolder = "public/".$newBidangNameFolder."/".$newFolderName;

        //update at db
        $folders->update([
          'name' => $newFolderName,
          'user_id' => Auth::user()->id,
          'bidang_id' => $newBidangIdFolder
        ]);

        //update at directory
        Storage::move($oldFolderPath, $newPathFolder);

        return redirect()->route('folder.index')->with('success', '1 data telah diubah!');
      }
      else {
        return redirect()->route('folder.index')->with('warning', 'Data gagal diubah, nama folder telah digunakan!');
      }
    }

    public function destroy($id)
    {
      //select 1 record
      $folders = Folder::with('bidang')->where('id', $id)->first();
      //get $oldPath to destroy it
      $oldName = $folders->name;
      $oldBidang = $folders->bidang->name;
      $path = "public/".$oldBidang."/".$oldName;

      //if the directory (folder) can be deleted then destroy files and folders data
      if (Storage::deleteDirectory($path)) {
        //destroy multiple child first
        $files = File::where('folder_id', $id)->get(['id']);
        File::destroy($files->toArray());

        //then destroy the parent
        $folders->delete();
        return redirect()->back()->with('success', 'Seluruh file dan directori berhasil dihapus!');
      }else {
        return redirect()->back()->with('warning', 'File dan directori gagal dihapus coba lagi!');
      }
    }
}
