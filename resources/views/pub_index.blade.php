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

    </div>
    <form method="POST" action="{{ route('public.file.find') }}">
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
          <th>Terakhir ditambah/diubah</th>
          <th>Aksi</th>
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
          <td><a href="{{ route('public.file.download', [$data->id]) }}" class="btn btn-primary">Download</a></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
