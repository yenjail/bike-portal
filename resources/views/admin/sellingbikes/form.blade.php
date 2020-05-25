@extends('layouts.master')

@section('admin_content')
<style type="text/css">
  .file-drop-area{
  display: block;
  padding: 2em;
  background: #eee;
  text-align: center;
  cursor: pointer;
}
</style>

<div class="right_col" role="main">
  <div class="page-title">
              <div class="title_left">
                <h3>Bikes For Sale</h3>
              </div>
            </div>
            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Add bike for sale</h2>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br>
                    <form method="POST" action="{{route('sellingbike.store')}}" enctype="multipart/form-data" class="form-horizontal form-label-left">
                      {{ csrf_field() }}

                      <div class="item form-group">
                        <label for="brand" class="col-form-label col-md-3 col-sm-3 label-align">Brand <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 ">
                          <select name="brand" class="form-control dynamic" id="brand" name="brand" required="required" data-dependent="model">
                            <option value="">Select Brand</option>
                            @foreach($brand as $b)
                            <option value="{{$b->brand}}">{{$b->brand}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>

                      <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="model">Model <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <select name="model" class="form-control dynamic" id="model" name="model" required="required" data-dependent="version">
                            <option value="">Select Model</option>

                          </select>

                        </div>
                      </div>

                      <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="version">Version <span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <select name="version" class="form-control" id="version" name="version">
                            <option value="">Select Version</option>

                          </select>
                        </div>
                      </div>

                      <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="seller_name">Seller Name<span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="seller_name" name="seller_name" class="form-control">
                          @if ($errors->has('seller_name'))
                              <span class="help-block" style="color: red;">
                                <strong>{{ $errors->first('seller_name') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>

                      <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="phone">Seller Contact No. <span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="phone" name="phone" class="form-control">
                          @if ($errors->has('phone'))
                              <span class="help-block" style="color: red;">
                                <strong>{{ $errors->first('phone') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>

                      <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="kms_run">Kms run<span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="number" step="0.01" id="kms_run" name="kms_run" class="form-control">
                          @if ($errors->has('kms_run'))
                              <span class="help-block" style="color: red;">
                                <strong>{{ $errors->first('kms_run') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>

                      <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="asking_price">Asking Price<span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="number" step="0.01" id="asking_price" name="asking_price" class="form-control">
                          @if ($errors->has('asking_price'))
                              <span class="help-block" style="color: red;">
                                <strong>{{ $errors->first('asking_price') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>

                       <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="make_year">Manufactured Year <span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="date" id="make_year" name="make_year" class="form-control">
                          @if ($errors->has('make_year'))
                              <span class="help-block" style="color: red;">
                                <strong>{{ $errors->first('make_year') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>

                      <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="bike_status">Status <span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <select name="bike_status" class="form-control" id="bike_status" name="bike_status" required="required">
                            <option value="">Select Bike Status</option>
                            <option value="used">Used</option>
                            <option value="unused">Unused</option>

                          </select>
                        </div>
                      </div>

                      <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="engine_cc">Engine CC(In Digits) <span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="number" id="engine_cc" name="engine_cc" class="form-control">
                          @if ($errors->has('engine_cc'))
                              <span class="help-block" style="color: red;">
                                <strong>{{ $errors->first('engine_cc') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>

                      <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="engine_cc">Color<span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="color" name="color" class="form-control">
                          @if ($errors->has('color'))
                              <span class="help-block" style="color: red;">
                                <strong>{{ $errors->first('color') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>

                      <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Additional Details <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <textarea id="additional_details" type="text" class="form-control" rows="6" cols="50"
                                        name="additional_details" value="{{ old('additional_details') }}" required autofocus></textarea>
                        </div>
                      </div>

                       <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Images? <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <div class="slim file-drop-area">
                            <label for="files">Drop your files here</label>
                            <input name="files[]" id="files" type="file" multiple>
                          </div>
                        </div>
                      </div>

                      <div class="item form-group">
                        <div class="col-md-10 col-sm-10 offset-7">
                          <a href="{{ url()->previous() }}" class="btn btn-primary" type="button">Close</a>

                          <button type="submit" class="btn btn-success">Save</button>
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

<script type="text/javascript" src="{{asset('slim/multipleuploader.js')}}"></script>
<script type="text/javascript">
  $(".dynamic").change(function () {
       if($(this).val() != '')
        {
         var select = $(this).attr("id");
         var value = $(this).val();
         var dependent = $(this).data('dependent');
         var _token = $('input[name="_token"]').val();
         $.ajax({
          url:"{{ route('sellingbike.fetch') }}",
          method:"POST",
          data:{select:select, value:value, _token:_token, dependent:dependent},
          success:function(result)
          {
           $('#'+dependent).html(result);
          }

         })
        }
    });
</script>


@endsection
