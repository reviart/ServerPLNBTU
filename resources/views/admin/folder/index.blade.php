@extends('layouts.new-app')

@section('content')
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
  <h2 class="sub-header">List Folder</h2>

  <br>
  @if (session('success'))
      <div class="alert alert-success">
        <center>
          {{ session('success') }}
        </center>
      </div>
  @elseif (session('warning'))
      <div class="alert alert-warning">
        <center>
          {{ session('warning') }}
        </center>
      </div>
  @else
  @endif

  <a href="{{ route('folder.store') }}" class="btn btn-success">Tambah folder</a>
  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead>
        <tr class="success">
          <th>NO</th>
          <th>Nama Folder</th>
          <th>Nama bidang</th>
          <th>Dibuat/diubah oleh</th>
          <th>Waktu pembuatan</th>
          <th>Terakhir diubah</th>
          @if(Auth::user())
          <th colspan="3">Aksi</th>
          @endif
        </tr>
      </thead>
      <tbody>
        <?php $no = 0; ?>
        @foreach($folders as $data)
        <tr class="info">
          <td>{{$no += 1}}</td>
          <td>{{$data->name}}</td>
          <td>{{$data->bidang->name}}</td>
          <td>{{$data->user->name}}</td>
          <td>{{$data->created_at}}</td>
          <td>{{$data->updated_at}}</td>
          @if(Auth::user())
          <td width="5%"><a href="{{ route('folder.edit', [$data->id]) }}" class="btn btn-warning">Edit</a></td>
          <td width="5%">
            <form class="" action="{{ route('folder.destroy', [$data->id]) }}" method="post">
              {{ csrf_field() }}
              {{ method_field('DELETE') }}
              <button type="submit" name="button" onclick="return confirm('Apakah yakin menghapus folder {{$data->name}} ?')" class="btn btn-danger">Delete</button>
            </form>
          </td>
          <td width="5%">
            <form class="" action="{{ route('folder.destroyAll', [$data->id]) }}" method="post">
              {{ csrf_field() }}
              {{ method_field('DELETE') }}
              <button type="submit" name="button" onclick="return confirm('Apakah yakin menghapus folder {{$data->name}} beserta seluruh datanya?')" class="btn btn-danger">Delete ALL</button>
            </form>
          </td>
          @endif
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
