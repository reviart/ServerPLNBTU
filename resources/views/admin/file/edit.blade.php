@extends('layouts.new-app')

@section('content')
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
  <h2>Edit file</h2>
  <div class="row">
      <div class="col-md-10">
          <div class="panel panel-default">
              <div class="panel-heading"></div>
              <div class="panel-body">
                  <form class="form-horizontal" method="POST" action="{{ route('file.edit.submit', [$files->id]) }}">
                      {{ csrf_field() }}
                      {{ method_field('PUT') }}
                      <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                          <label for="name" class="col-md-3 control-label">Nama file</label>
                          <div class="col-md-8">
                              <input id="name" type="text" class="form-control" name="name" value="{{ $files->name }}" required autofocus>
                              @if ($errors->has('name'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('name') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>
                      <div class="form-group{{ $errors->has('bidang_id') ? ' has-error' : '' }}">
                          <label for="bidang_id" class="col-md-3 control-label">Bidang</label>
                          <div class="col-md-4">
                            <select class="form-control" id="sel1" name="bidang_id" required>
                              <option value="{{ $files->bidang->id }}">{{ $files->bidang->name }}</option>
                              @foreach($bidangs as $data)
                                <option value="{{$data->id}}">{{$data->name}}</option>
                              @endforeach
                            </select>
                          </div>
                      </div>
                      <div class="form-group{{ $errors->has('folder_id') ? ' has-error' : '' }}">
                          <label for="folder_id" class="col-md-3 control-label">Folder</label>
                          <div class="col-md-4">
                            <select class="form-control" id="sel1" name="folder_id" required>
                              <option value="{{ $files->folder->id }}">{{ $files->folder->name }}</option>
                              @foreach($folders as $data)
                                <option value="{{$data->id}}">{{$data->name}}-({{$data->bidang->name}})</option>
                              @endforeach
                            </select>
                          </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-8 col-md-offset-3">
                          <i>Huruf terakhir nama file tidak diperbolehkan menggunakan simbol!</i>
                          <i>Pastikan ekstensi file tidak dihapus!</i>
                        </div>
                      </div>
                      <div class="form-group">
                          <div class="col-md-6 col-md-offset-3">
                            <button type="submit" onclick="return confirm('Apakah data sudah terisi benar?')" class="btn btn-primary">
                                Save
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
