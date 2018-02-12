@extends('layouts.new-app')

@section('content')
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
  <h2 class="sub-header">Profile dan list admin</h2>

  <h4>Nama : {{Auth::user()->name}}</h4>
  <h4>Email : {{Auth::user()->email}}</h4>
  <h4>Terakhir login : {{Auth::user()->current_sign_in}}</h4>

  <hr>
  <a href="{{ route('register') }}" class="btn btn-success" target="_blank">Tambah admin</a>
  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead>
        <tr class="success">
          <th>NO</th>
          <th>Nama</th>
          <th>Waktu pembuatan</th>
          <th>Terakhir login</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 0; ?>

        @foreach($datas as $data)
        <tr class="info">
          <td>{{$no += 1}}</td>
          <td>{{$data->name}}</td>
          <td>{{$data->created_at}}</td>
          <td>{{$data->current_sign_in}}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
