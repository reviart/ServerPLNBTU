@extends('layouts.new-app')

@section('content')
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
  <h2>Edit bidang</h2>
  <div class="row">
      <div class="col-md-10">
          <div class="panel panel-default">
              <div class="panel-heading"></div>
              <div class="panel-body">
                  <form class="form-horizontal" method="POST" action="{{ route('bidang.edit.submit', [$bidangs->id]) }}">
                      {{ csrf_field() }}
                      {{ method_field('PUT') }}
                      <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                          <label for="name" class="col-md-3 control-label">Nama bidang</label>
                          <div class="col-md-8">
                              <input id="name" type="text" class="form-control" name="name" value="{{ $bidangs->name }}" required autofocus>
                              @if ($errors->has('name'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('name') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-8 col-md-offset-3">
                          <i>Huruf terakhir nama bidang tidak diperbolehkan menggunakan simbol!</i>
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
