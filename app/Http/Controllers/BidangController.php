<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Bidang;
use Auth;

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
      $object = new Satuan;
      $object->name = $request->get('name');
      $object->user_id = $request->get('user_id');
      $object->save();

      return redirect()->route('bidang.index')->with('success', '1 record created!');
    }

    public function show($id)
    {
      $bidangs = Bidang::where('id', $id)->first();
      return view('admin.bidang.edit', compact('bidangs'));
    }

    public function update(Request $request, $id)
    {

    }


}
