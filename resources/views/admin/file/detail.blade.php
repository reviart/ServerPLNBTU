@extends('layouts.new-app')

@section('content')
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<h2 class="sub-header">Detail File</h2>
<a href="{{ route('file.store') }}" class="btn btn-primary">Tambah file</a>
<a href="{{ route('file.edit', [$files[0]->id]) }}" class="btn btn-warning">Edit</a>
<button type="button" name="button" onclick="history.back()" class="btn btn-danger">Kembali</button>
<br><br>
  <table class="table table-striped">
    @foreach($files as $data)
    <tr>
      <th width="20%">Nama file</th>
      <td>{{$data->name}}</td>
    </tr>
    <tr>
      <th>Extension</th>
      <td>{{$data->ext}}</td>
    </tr>
    <tr>
      <th>Ukuran</th>
      <?php
        $base = log($data->size, 1024);
        $suffixes = array('', 'KB', 'MB', 'GB', 'TB');
        $hasil = round(pow(1024, $base - floor($base)), 2) .' '. $suffixes[floor($base)];
      ?>
      <td>{{$hasil}}</td>
    </tr>
    <tr>
      <th>Pembuat/pengedit</th>
      <td>{{$data->user->name}}</td>
    </tr>
    <tr>
      <th>Disimpan pada bidang</th>
      <td>{{$data->bidang->name}}</td>
    </tr>
    <tr>
      <th>Disimpan pada folder</th>
      <td>{{$data->folder->name}}</td>
    </tr>
    <tr>
      <th>Tanggal dibuat</th>
      <td>{{$data->created_at}}</td>
    </tr>
    <tr>
      <th>Tanggal diubah</th>
      <td>{{$data->updated_at}}</td>
    </tr>
    @endforeach
  </table>
</div>
@endsection
