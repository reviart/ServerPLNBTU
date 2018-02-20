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

  <div class="row">
    <div class="col-md-8">
      <a href="{{ route('folder.store') }}" class="btn btn-success">Tambah folder</a>
    </div>
    <form method="POST" action="{{ route('folder.find') }}">
      {{ csrf_field() }}
      <div class="col-md-3">
        <select class="form-control" id="sel1" name="bidang_id" required>
          <option value="">Cari berdasarkan bidang</option>
          @foreach($bidangs as $data)
            <option value="{{$data->id}}">{{$data->name}}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-1">
        <button type="submit" onclick="return confirm('Apakah sudah memilih bidang?')" class="btn btn-primary">
            Cari
        </button>
      </div>
    </form>
  </div>

  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead>
        <tr class="success">
          <th>NO</th>
          <th>Nama folder</th>
          <th>Nama bidang</th>
          <th>Dibuat/diubah oleh</th>
          <th>Waktu pembuatan</th>
          <th>Terakhir diubah</th>
          <th colspan="2">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 0; ?>
        @foreach($folders as $data)
        <tr class="info">
          <td>{{$no += 1}}</td>
          <td>{{$data->name}}</td>
          <td>{{$data->bidang->name}}</td>
          <td>{{ substr($data->user->name, 0, 15) }}</td>
          <td>{{$data->created_at}}</td>
          <td>{{$data->updated_at}}</td>
          @if(Auth::user())
          <td width="5%"><a href="{{ route('folder.edit', [$data->id]) }}" class="btn btn-warning">Edit</a></td>
          @endif
          @if(Auth::user()->id == $data->user_id)
          <td width="5%">
            <form class="" action="{{ route('folder.destroy', [$data->id]) }}" method="post">
              {{ csrf_field() }}
              {{ method_field('DELETE') }}
              <button type="submit" name="button" onclick="return confirm('Apakah yakin menghapus folder {{$data->name}}? (seluruh file akan ikut terhapus)')" class="btn btn-danger">Delete</button>
            </form>
          </td>
          @else
          <td width="5%"></td>
          @endif
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
