@extends('layouts.master')

@section('custom_css')
    <style type="text/css">
      .main-section{
        padding: 20px;
        background-color: #fff;
        box-shadow: 0px 0px 20px #c1c1c1;
      }

      .file-drop-area label {
        display: block;
        padding: 2em;
        background: #eee;
        text-align: center;
        cursor: pointer;
      }
    </style>
    @endsection

@section('admin_content')

<div class="right_col" role="main">
  <div class="page-title">
              <div class="title_left">
                <h3>Seller</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Add New Seller</h2>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br>
                    <form method="POST" action="{{ route('seller.update', $seller->id) }}" enctype="multipart-data" class="form-horizontal form-label-left">
                      {{ csrf_field() }}

                       <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="brand">Brand<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="brand" required="required" name="brand" class="form-control" value="{{isset($bikes->brand)? $bikes->brand : ''}}">
                            @if ($errors->has('brand'))
                              <span class="help-block" style="color: red;">
                                <strong>{{ $errors->first('brand') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>

                      <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="model">Model <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="model" required="required" name="model" class="form-control" value="{{isset($bikes->model)? $bikes->model : ''}}">
                            @if ($errors->has('model'))
                              <span class="help-block" style="color: red;">
                                <strong>{{ $errors->first('model') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>

                      <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="version">Version <span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="version" name="version" class="form-control" value="{{isset($bikes->version)? $bikes->version : ''}}">
                          @if ($errors->has('version'))
                              <span class="help-block" style="color: red;">
                                <strong>{{ $errors->first('version') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>

                      <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="current_mp">Price (Rs.) <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="number" step="0.01" id="current_mp" name="current_mp" required="required" class="form-control" value="{{isset($bikes->current_mp)? $bikes->current_mp : ''}}">
                          @if ($errors->has('price'))
                              <span class="help-block" style="color: red;">
                                <strong>{{ $errors->first('price') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>

                      <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Features <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <textarea id="features" type="text" class="form-control" rows="6" cols="50"
                                        name="features" value="{{ old('features') }}" required > {{isset($bikes->features)? $bikes->features : ''}}</textarea>
                        </div>
                      </div>

                      <div class="ln_solid"></div>
                      <div class="item form-group">
                        <div class="col-md-10 col-sm-10 offset-7">
                          <a href="{{ url()->previous() }}" class="btn btn-primary" type="button">Close</a>

                          <button type="submit" class="btn btn-success">Update details</button>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>

@endsection

@section('custom_script')




</script>

@endsection
