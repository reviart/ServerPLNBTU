@extends('layouts.new-app')

@section('content')
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
  <h2>Tambah folder</h2>
  <div class="row">
      <div class="col-md-10">
          <div class="panel panel-default">
              <div class="panel-heading"></div>
              <div class="panel-body">
                  <form class="form-horizontal" method="POST" action="{{ route('folder.store.submit') }}">
                      {{ csrf_field() }}
                      <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                          <label for="name" class="col-md-3 control-label">Nama folder</label>
                          <div class="col-md-8">
                              <input id="name" type="text" class="form-control" name="name" autocomplete="off" required autofocus>
                              @if ($errors->has('name'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('name') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="form-group{{ $errors->has('access_permission') ? ' has-error' : '' }}">
                          <label for="access_permission" class="col-md-3 control-label">Hak akses</label>
                          <div class="col-md-8">
                              <input id="access_permission" type="number" class="form-control" name="access_permission" placeholder="Ex: 777 (tidak wajib)">
                              @if ($errors->has('access_permission'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('access_permission') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>
                      <div class="form-group{{ $errors->has('bidang_id') ? ' has-error' : '' }}">
                          <label for="bidang_id" class="col-md-3 control-label">Bidang</label>
                          <div class="col-md-4">
                            <select class="form-control" id="sel1" name="bidang_id" required>
                              <option>Pilih bidang</option>
                              @foreach($folders as $data)
                                <option value="{{$data->id}}">{{$data->name}}</option>
                              @endforeach
                            </select>
                          </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-8 col-md-offset-3">
                          <i>Huruf terakhir nama folder tidak diperbolehkan menggunakan simbol!</i>
                        </div>
                      </div>
                      <div class="form-group">
                          <div class="col-md-6 col-md-offset-3">
                              <button type="submit" onclick="return confirm('Apakah data sudah terisi benar?')" class="btn btn-primary">
                                  Create
                              </button>
                              <button type="button" name="button" onclick="history.back()" class="btn btn-warning">Cancel</button>
                          </div>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection
