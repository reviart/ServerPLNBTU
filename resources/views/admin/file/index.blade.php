@extends('layouts.new-app')

@section('content')
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
  <h2 class="sub-header">List file</h2>

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
      <a href="{{ route('file.store') }}" class="btn btn-success">Tambah file</a>
    </div>
    <form method="POST" action="{{ route('file.find') }}">
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
          <th>Nama file</th>
          <th>Nama folder</th>
          <th>Nama bidang</th>
          <th>Terakhir diubah</th>
          @if(Auth::user())
          <th colspan="4">Aksi</th>
          @endif
        </tr>
      </thead>
      <tbody>
        <?php $no = 0; ?>
        @foreach($files as $data)
        <tr class="active">
          <td>{{$no += 1}}</td>
          <td>{{$data->name}}</td>
          <td>{{$data->folder->name}}</td>
          <td>{{$data->bidang->name}}</td>
          <td>{{$data->updated_at}}</td>
          <td width="5%"><a href="{{ route('file.detail', [$data->id]) }}" class="btn btn-primary">Detail</a></td>
          @if(Auth::user())
          <td width="5%"><a href="{{ route('file.edit', [$data->id]) }}" class="btn btn-warning">Edit</a></td>
          @endif
          @if(Auth::user()->id == $data->user_id)
          <td width="5%">
            <form class="" action="{{ route('file.destroy', [$data->id]) }}" method="post">
              {{ csrf_field() }}
              {{ method_field('DELETE') }}
              <button type="submit" name="button" onclick="return confirm('Apakah yakin menghapus file {{$data->name}} ?')" class="btn btn-danger">Delete</button>
            </form>
          </td>
          @endif
          <td><a href="{{ route('file.download', [$data->id]) }}" class="btn btn-primary">Download</a></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
