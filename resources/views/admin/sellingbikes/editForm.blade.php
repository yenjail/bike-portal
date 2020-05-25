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
                <h3>Bikes For Sale</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Add bike For Sale</h2>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br>
                    <form method="POST" action="{{ route('sellingbike.update', $bikes->id) }}" enctype="multipart-data" class="form-horizontal form-label-left">
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
                          <input type="text" id="seller_name" name="seller_name" class="form-control" value="{{isset($bikes->seller_name)? $bikes->seller_name : ''}}">
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
                          <input type="text" id="phone" name="phone" class="form-control" value="{{isset($bikes->phone)? $bikes->phone : ''}}">
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
                          <input type="number" step="0.01" id="kms_run" name="kms_run" class="form-control" value="{{isset($bikes->kms_run)? $bikes->kms_run : ''}}">
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
                          <input type="number" step="0.01" id="asking_price" name="asking_price" class="form-control" value="{{isset($bikes->asking_price)? $bikes->asking_price : ''}}">
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
                          <input type="date" id="make_year" name="make_year" class="form-control" value="{{isset($bikes->make_year)? $bikes->make_year : ''}}">
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
                          <input type="number" id="engine_cc" name="engine_cc" class="form-control" value="{{isset($bikes->engine_cc)? $bikes->engine_cc : ''}}">
                          @if ($errors->has('engine_cc'))
                              <span class="help-block" style="color: red;">
                                <strong>{{ $errors->first('engine_cc') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>

                      <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="color">Color<span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="color" name="color" class="form-control" value="{{isset($bikes->color)? $bikes->color : ''}}">
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
                                        name="additional_details" value="{{ old('additional_details') }}" required>{{isset($bikes->additional_details)? $bikes->additional_details : ''}}</textarea>
                        </div>
                      </div>



                       <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align"><span class="required"></span>
                        </label>

                        <div class="col-md-6 col-sm-6 ">
                          <div id="gallery_image">
                                                @if($proImage != NULL)
                                                    @foreach($proImage  as $image)


                                                        <div class="col-sm-4" align="center" id="content-{{explode('.',$image->image)[0] }}">

                                                            <img src="{{ asset($image->image) }}" height="80" width="90" style="margin-right: 120px;"/>
                                                            <br/>

                                                            <button type="button" class="btn btn-danger" onClick="removeImage('{{ explode('.',$image->image)[0]}}','{{ explode('.',$image->image)[1]}}','{{  $bikes->id }}')"><span class="glyphicon glyphicon-trash"></span></button>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                        </div>
                      </div>

                       <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Images? <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                          <div class="slim file-drop-area" data-size="640,640">
                            <label for="files">Drop your files here</label>
                            <input name="files[]" id="files" type="file" multiple>
                          </div>
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


    function removeImage(image,ext,id)
    {
        $imgname = image + '.'+  ext;

        var r = confirm('Are you sure to delete?');
        // console.log($('#content-'+image));
        if(r){
            $.ajax({
                url : '{{route('sellingbike.removeImage')}}',
                type: "POST",
                data: {"_token": "{{ csrf_token() }}",  "id": id, "image": $imgname},
                success : function (data) {
                    if(data){
                        // console.log($('#content-'+image));
                        console.log(image);
                        $('#content-'+image).remove();
                        // $a= $('#content-'+image);

                        alert('Image Deleted Successfully.');
                    }
                    else{
                        alert('Image could not be deleted.');
                    }

                },
                // and so on
                        });
        }
    }


    $("#file-1").fileinput({

         theme:'fa',
          uploadUrl:"{{url('admin/bikes-on-sale/updateImage')}}",
          uploadExtraData:function(){
           return{
            _token:$("input[name='_token']").val(),
            pId: '{{$bikes->id}}',
           };
          },
          overwriteInitial:false,
          allowedFileExtensions:['jpg','png','gif'],
          maxFileSize:2000,
          showRemove: false,
          showCancel: false,
          showUpload: false,
          maxFileNum:8,
          slugCallback:function(filename){
           return filename.replace('(','_').replace(']','_');
          }

    });

    $('#file-1').on('fileimageloaded', function(event, previewId) {
    console.log();



});



</script>

@endsection
