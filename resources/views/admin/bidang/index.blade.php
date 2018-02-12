@extends('layouts.new-app')

@section('content')
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
  <h2 class="sub-header">List bidang</h2>
  <a href="{{ route('bidang.store') }}" class="btn btn-success" target="_blank">Tambah bidang</a>
  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead>
        <tr class="success">
          <th>NO</th>
          <th>Nama bidang</th>
          <th>Waktu pembuatan</th>
          <th>Terakhir diubah</th>
          <th>Dibuat/diubah oleh</th>
          @if(Auth::user())
          <th>Aksi</th>
          @endif
        </tr>
      </thead>
      <tbody>
        <?php $no = 0; ?>
        @foreach($bidangs as $data)
        <tr class="info">
          <td>{{$no += 1}}</td>
          <td>{{$data->name}}</td>
          <td>{{$data->created_at}}</td>
          <td>{{$data->updated_at}}</td>
          <td>{{$data->user->name}}</td>
          @if(Auth::user())
          <td width="5%"><a href="{{ route('bidang.edit', [$data->id]) }}" class="btn btn-warning">Edit</a></td>
          <td width="5%">
            <form class="" action="{{ route('bidang.destroy', [$data->id]) }}" method="post">
              {{ csrf_field() }}
              {{ method_field('DELETE') }}
              <button type="submit" name="button" onclick="return confirm('Apakah yakin menghapus bidang {{$data->name}} ?')" class="btn btn-danger">Delete</button>
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
