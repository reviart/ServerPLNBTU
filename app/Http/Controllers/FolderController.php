<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Bidang;
use App\Folder;
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
      $bidangs = Bidang::all();
      return view('admin.folder.index', compact('folders', 'bidangs'));
    }

    public function find(Request $request)
    {
      $id = $request->get('bidang_id');
      if ($id > 0) {
        $folders = Folder::with('user', 'bidang')->where('bidang_id', $id)->orderBy('name')->get();
        $bidangs = Bidang::all();
        return view('admin.folder.index', compact('folders', 'bidangs'));
      } else {
        return redirect()->route('folder.index')->with('warning', 'Gagal pencarian, anda belum memilih bidang!');
      }
    }

    public function create()
    {
      $bidangs = Bidang::all();
      return view('admin.folder.create', compact('bidangs'));
    }

    public function store(Request $request)
    {
      $name = strtoupper($request->get('name'));

      //checking all the data is empty
      $folders = Folder::where('name', $name)->first();
      $bidangid_new = $request->get('bidang_id');
      if ($folders == NULL) {
        $bidang_id = $bidangid_new;
        $bidang_name = Bidang::where('id', $bidang_id)->get();
        $bidang_name = $bidang_name[0]->name;

        //making or storing ap
        $ap = $request->get('access_permission');
        if ($ap == NULL) {
          $ap = 777;
        }

        //create directory
        Storage::makeDirectory('public/'.$bidang_name.'/'.$name, $ap);

        //save to db
        $object = new Folder;
        $object->name = $name;
        $object->path = "public/".$bidang_name."/".$name;
        $object->access_permission = $ap;
        $object->user_id = Auth::user()->id;
        $object->bidang_id = $bidang_id;
        $object->save();

        return redirect()->route('folder.index')->with('success', '1 data berhasil ditambah!');
      }
      elseif (($folders->name != NULL) && ($folders->bidang_id != $bidangid_new)) {
        $bidang_id = $bidangid_new;
        $bidang_name = Bidang::where('id', $bidang_id)->get();
        $bidang_name = $bidang_name[0]->name;

        //making or storing ap
        $ap = $request->get('access_permission');
        if ($ap == NULL) {
          $ap = 777;
        }

        //create directory
        Storage::makeDirectory('public/'.$bidang_name.'/'.$name, $ap);

        //save to db
        $object = new Folder;
        $object->name = $name;
        $object->path = "public/".$bidang_name."/".$name;
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
      $folders = Folder::where('id', $id)->first();
      $bidangs = Folder::with('bidang')->where('id', $id)->first();
      $lists = Bidang::all();
      //echo $bidangs->bidang->name;
      return view('admin.folder.edit', compact('folders', 'bidangs', 'lists'));
    }

    public function update(Request $request, $id)
    {
      //checking the name and bidang_id
      $newFolderName = strtoupper($request->get('name'));
      $checkName = Folder::where('name', $newFolderName)->first();
      $newBidangIdFolder = $request->get('bidang_id');
      if ($checkName == NULL) {
        //get $oldPath to change location or rename dir
        $folders = Folder::find($id);
        $oldFolderPath = $folders->path;

        //get bidang name
        $tmp = Bidang::where('id', $newBidangIdFolder)->first();
        $newBidangNameFolder = $tmp->name;
        $newPathFolder = "public/".$newBidangNameFolder."/".$newFolderName;

        //update at db
        $folders->update([
          'name' => $newFolderName,
          'path' => $newPathFolder,
          'user_id' => Auth::user()->id,
          'bidang_id' => $newBidangIdFolder
        ]);

        //update at directory
        Storage::move($oldFolderPath, $newPathFolder);

        return redirect()->route('folder.index')->with('success', '1 data telah diubah!');
      }
      elseif (($checkName->name != NULL) && ($checkName->id != $newBidangIdFolder)) {
        //get $oldPath to change location or rename dir
        $folders = Folder::find($id);
        $oldFolderPath = $folders->path;

        //get bidang name
        $tmp = Bidang::where('id', $newBidangIdFolder)->first();
        $newBidangNameFolder = $tmp->name;
        $newPathFolder = "public/".$newBidangNameFolder."/".$newFolderName;

        //update at db
        $folders->update([
          'name' => $newFolderName,
          'path' => $newPathFolder,
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
      $folders = Folder::findOrFail($id);
      $path = $folders->path;

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
