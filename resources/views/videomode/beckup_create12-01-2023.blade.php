@extends('admin.layout')
@section('content')
<style>
    .modal-body iframe {
        width: 100% !important;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Video Mode</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashbord</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('videomode.index')}}">Video Mode</a></li>
                        <li class="breadcrumb-item active">Add Video</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary">
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                        @endif
                        <div class="card-header color-me">
                            <h3 class="card-title">Add Video & Description Mode</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('videomode.store') }}" method="POST" id="video-mode" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <?php
                                  //  print_r($get_workout_tag);
                                    $get_workout_tags = [];
                                    $unique_countries = array();
                                    foreach ($get_workout_tag as $result) {
                                        $result_countries = explode(',', $result->tag);

                                        foreach ($result_countries as $country) {
                                            if (!in_array($country, $unique_countries)) {
                                                $get_workout_tags[] = $country;
                                            }
                                        }
                                    }
                                    ?>
                                        <!-- <div class="form-group">
                                        <label for="exampleInputFile">Upload Demo Video</label>
                                        <div class="input-group">
                                        <div class="custom-file">
                                        <input type="file" name="file" class="custom-file-input" id="exampleInputFile">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                        <div class="input-group-append">
                                        <span class="input-group-text">Upload</span>
                                        </div>
                                        </div>
                                        </div> -->

                                    <div class="form-group">
                                        <label>Select Workout Video</label>
                                        <select class="form-control get_workout">
                                            <option value="">Select Video</option>
                                            <?php

                                            foreach ($get_workout_tags as $get_workout_tagss) {

                                            ?>
                                                <option value="<?php echo $get_workout_tagss; ?>"><?php echo $get_workout_tagss; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <span class="get_workout_error text-danger"></span>

                                    <div class="row" id="videos">
                                        <div id="myselect"></div>
                                        <table class="table table-bordered" id="workout_video">

                                        </table>
                                        <span class="videos_error text-danger"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="video_title">Title</label>
                                        <input type="video_title" class="form-control" id="video_title" name="video_title" value="{{old('video_title')}}" placeholder="Enter Name" required>
                                        <span class="video_title_error text-danger"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="text">Description</label>
                                        <textarea class="summernote" id="video_desc" rows="4" cols="50" name="description">{{old('description')}}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="category">Category</label>
                                        <select name="category" class="custom-select rounded-0" id="category" required>
                                            <option value="">Select Category</option>
                                            @foreach ($category as $skey => $val)
                                            <option class="form-control" {{ (old('category') == $skey)?'selected':'' }} value="{{ $skey }}">{{$val}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="muscle_group">Muscle Group</label>
                                        <select name="muscle_group" class="custom-select rounded-0" id="muscle_group" required>
                                            <option value="">Select Muscle Group</option>
                                            @foreach ($muscle_group as $skey => $val)
                                            <option class="form-control" {{ (old('muscle_group') == $skey)?'selected':'' }} value="{{ $skey }}">{{$val}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="equipment">Equipment</label>
                                        <input type="text" class="form-control" id="equipment" value="{{old('equipment')}}" placeholder="Enter Muscle Group" name="equipment" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="instructor">Instructor</label>
                                        <select name="instructor" class="custom-select rounded-0" id="instructor" required>
                                            <option value="">Select Instructor</option>
                                            @foreach ($instructor as $skey => $val)
                                            <option class="form-control" {{ (old('instructor') == $skey)?'selected':'' }} value="{{ $skey }}">{{$val}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="instructor"> Intensity rating</label>
                                        <select name="intensity_rating" class="custom-select rounded-0" id="intensity_rating" required>
                                            <option value="">Select Intensity rating</option>
                                            @foreach ($intensityrating as $skey => $val)
                                            <option class="form-control" {{ (old('intensity_rating') == $skey)?'selected':'' }} value="{{ $skey }}">{{$val}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="equipment">Workout Time</label>
                                        <select class="form-control" name="name="workout_time"">
                                            <option value="">Select a Option</option>
                                            <option value="5"> 5 min</option>
                                            <option value="10">10 min</option>
                                            <option value="20">20 min</option>
                                            <option value="30">30 min</option>
                                            <option value="45">45 min</option>
                                            <option value="60">1 hour</option>
                                        </select>
                                        <!-- <input type="text" class="html-duration-picker form-control" name="workout_time" data-duration="00:32:10" /> -->
                                    </div>

                                    <?php
                                   // print_r($get_demo_tag);
                                    $unique_countries = array();
                                    foreach ($get_demo_tag as $result) {
                                        $result_countries = explode(',', $result->tag);
                                        foreach ($result_countries as $country) {
                                            if (!in_array($country, $unique_countries)) {
                                                $unique_countries[] = $country;
                                            }
                                        }
                                    }
                                    ?>

                                    <div class="form-group">
                                        <label>Select Demo Video</label>
                                        <select class="select2 get_val" id="get_val" multiple="multiple" required data-placeholder="Select Demo Video" name="tag[]" style="width: 100%;">
                                            <?php

                                            foreach ($unique_countries as $unique_countriess) {

                                            ?>
                                                <option value="<?php echo $unique_countriess; ?>" class="form-control"><?php echo $unique_countriess; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="row" id="video">
                                        <div id="myselect"></div>
                                        <table class="table table-bordered" >
                                        <tbody class="tddd" id="someTableID"></tbody>
                                        </table>
                                    </div>
                                    <!-- <div class="form-group">
                                        <input type="checkbox" name="mode_desc" id="mode_desc">
                                        <label>Do you want description mode?</label>
                                    </div> -->

                                    <!-- /.card-body -->
                                    <!-- <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div> -->
                                </div>
                            </form>
                            <form action="{{ route('desmode.store') }}" method="POST" id="desc-mode" style="display: none;" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                   <input type="hidden" name="last_id" id="last_id" value="">
                                    <div class="form-group">
                                        <label for="img_title">Title</label>
                                        <input type="img_title" class="form-control" id="img_title" name="img_title" value="{{old('img_title')}}" placeholder="Enter Name" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="text">Description</label>
                                        <textarea class="summernote" id="img_desc" rows="4" cols="50" name="description">{{old('video_title')}}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <input type="hidden" name="category" value="" id="selected_cat">
                                        <label for="category">Category</label>
                                        <select name="" class="custom-select rounded-0" id="category-desc" required readonly>
                                            <option value="">Select Category</option>
                                            @foreach ($category as $skey => $val)
                                            <option class="form-control" {{ (old('category') == $skey)?'selected':'' }} value="{{ $skey }}">{{$val}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="muscle_group">Muscle Group</label>
                                        <select name="muscle_group" class="custom-select rounded-0" id="muscle_group-desc" required>
                                            <option value="">Select Muscle Group</option>
                                            @foreach ($muscle_group as $skey => $val)
                                            <option class="form-control" {{ (old('muscle_group') == $skey)?'selected':'' }} value="{{ $skey }}">{{$val}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="equipment">Equipment</label>
                                        <input type="text" class="form-control" id="equipment-desc" value="{{old('equipment')}}" placeholder="Enter Muscle Group" name="equipment" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="instructor">Instructor</label>
                                        <select name="instructor" class="custom-select rounded-0" id="instructor-desc" required>
                                            <option value="">Select Instructor</option>
                                            @foreach ($instructor as $skey => $val)
                                            <option class="form-control" {{ (old('instructor') == $skey)?'selected':'' }} value="{{ $skey }}">{{$val}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="instructor"> Intensity rating</label>
                                        <select name="intensity_rating" class="custom-select rounded-0" id="intensity_rating-desc" required>
                                            <option value="">Select Intensity rating</option>
                                            @foreach ($intensityrating as $skey => $val)
                                            <option class="form-control" {{ (old('intensity_rating') == $skey)?'selected':'' }} value="{{ $skey }}">{{$val}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <?php
                                    $unique_countries = array();
                                    foreach ($get_demo_tag as $result) {
                                        $result_countries = explode(',', $result->tag);
                                        foreach ($result_countries as $country) {
                                            if (!in_array($country, $unique_countries)) {
                                                $unique_countries[] = $country;
                                            }
                                        }
                                    }
                                    ?>

                                    <div class="form-group">
                                        <label>Select Demo Video</label>
                                        <select class="get_val1" id="get_val1" multiple="multiple" required data-placeholder="Select Demo Video" name="tag[]" style="width: 100%;">
                                            <?php

                                            foreach ($unique_countries as $unique_countriess) {

                                            ?>
                                                <option value="<?php echo $unique_countriess; ?>" class="form-control"><?php echo $unique_countriess; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="row" id="video">
                                        <table class="table table-bordered" >
                                        <tbody id="someTableID1" class="second">

                                        </tbody>
                                        </table>
                                    </div>

                                    <div class="container1">

                                        <h1>Add Round</h1>

                                        <div class="form-group">
                                            <label for="exampleInputFile">Upload Image</label><br>
                                            <input type="file" name="round_description[data][file][]" multiple accept="image/*" data-type='image'>
                                        </div>

                                        <div class="form-group">
                                            <label>Round Description</label>
                                            <textarea class="form-control" name="round_description[data][des][]" rows="6" placeholder="Enter Description" value=""></textarea>
                                        </div>

                                    </div>

                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <button class="add_form_field btn btn-block btn-dark">Add More Round &nbsp;
                                                <span style="font-size:16px; font-weight:bold;">+ </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-3">
                                    <button type="button" id="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>
    </section>
</div>

<style>
    .select2-selection__choice {
        background: #00644de3 !important;
        color: black !important;
    }

    .html-duration-picker {
        text-align: left !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/html-duration-picker@latest/dist/html-duration-picker.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
     $(document).ready(function(){
      $('#category').on('change', function(){
           field = $(this).val();
           if(field =='Cardio'){
            $('#equipment').removeAttr('required');
            $('#muscle_group').removeAttr('required');
           } else{
            $('#equipment').prop('required',true);
            $('#muscle_group').prop('required',true);
           }
      })
   })
</script>
<script>
$(document).ready(function(){  
  $("#category").change(function() {   
    $("#category-desc").not(this).find("option[value="+ $(this).val() + "]").attr('selected', true);
    $('#selected_cat').val($(this).val());
    $('#category-desc').attr("disabled", true); 
  }); 
}); 
</script>

<script>
    $("#someTableID").on('click', '.btnDelete', function() {
        $(this).closest('tr').remove();
    });

    $("#workout_video").on('click', '.workout_videos', function() {
        $(this).closest('tr').remove();
    });
</script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.get_workout').on('change', function() {
            var selecteds = $(".get_workout").val();

            if (selecteds) {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('videomode.add_workout') }}",
                    data: {
                        selecteds: selecteds
                    },
                    dataType: "json",
                    success: function(data) {
                        $('#workout_video').html(data);
                    }
                });
            } else {

            }
        });
    })
</script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.get_val').on('change', function() {
            var selected = $(".get_val").val();

            if (selected) {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('videomode.add_video') }}",
                    data: {
                        selected: selected
                    },
                    dataType: "json",
                    success: function(data) {
                        $('#someTableID').html(data);
                    }
                });
            } else {
                $('#someTableID').html("");
            }
        });
    })
    
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet"> 
<script>

$('.tddd').sortable();
</script>

</script>

<!-- Description Mode -->
<script>
    var f = 1;
</script>

<script>
    $("#someTableID1").on('click', '.btnDelete', function() {
        $(this).closest('tr').remove();
    });
</script>
<script>
$('.second').sortable();
</script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.get_val1').on('change', function() {
            var selected = $(".get_val1").val();

            if (selected.length !=0) {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('videomode.add_video') }}",
                    data: {
                        selected: selected
                    },
                    dataType: "json",
                    success: function(data) {
                        $('#someTableID1').html(data);
                    }
                });
            } else {
                $('#someTableID1').html("");
            }
        });
    })

    $('#mode_desc').click(function() {
        let status = $('#mode_desc').prop('checked');

        if (status) {
            $('#desc-mode').show()

            $('#img_title').val($('#video_title').val());

            $('#equipment-desc').val($('#equipment').val());

            $('.note-editable').html($('#video_desc').val());

            let category = $('#category').val();
            $('#category-desc option[value="' + category + '"]').prop('selected', true);

            let muscle = $('#muscle_group').val();
            $('#muscle_group-desc option[value="' + muscle + '"]').prop('selected', true);

            let instructor = $('#instructor').val();
            $('#instructor-desc option[value="' + instructor + '"]').prop('selected', true);

            let intensity = $('#intensity_rating').val();
            $('#intensity_rating-desc option[value="' + intensity + '"]').prop('selected', true);
        } else {
            $('#desc-mode').hide()
        }
    })
</script>

<!-- Form Submit -->
<script>
    $('#submit').click(function() {
        let form = $('#video-mode')[0];
        let data = new FormData(form);
        var get_workout = $('.get_workout').val();
        var video_title = $('#video_title').val();    
        var radio2 = $('#radio2').val(); 
        //var radio2 = $('#radio2').val();       
    
     if(get_workout =='') {
         $('.get_workout_error').html('Select a option');
         return;
     }
    //alert(radio2)
     if(radio2 =='') {
       
         $('.videos_error').html('Select Workout Video');
         return;
     }
     if(video_title =='') {
         $('.video_title_error').html('input field are required');
         return;
     }
    

        $.ajax({
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            dataType:"json",
            url: "{{ route('videomode.store') }}",
            success: function(response) {
                var last = response.last_id;
                //return;
                let status = $('#mode_desc').prop('checked');

                if (status) {
                    save_description(last);
                } else {
                    location.href = '/videomode';
                }
            },
            error: function() {
                console.log('Something went wrong')
            }
        })
    })

    function save_description(last) {
          $('#last_id').val(last);
        //  return;
        let form = $('#desc-mode')[0];
        let data = new FormData(form);
     //   alert(last);

        $.ajax({
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            url: "{{ route('desmode.store') }}",
            success: function(response) {
                location.href = '/videomode';
            },
            error: function() {
                console.log('Something went wrong')
            }
        })
    }
</script>
<script>
     $(document).ready(function(){
    
    $('#get_val').select2({
        allowClear: true,
     
        width: 500
    });
             
    $("select").on("select2:select", function(evt) {
        var element = evt.params.data.element;
        var $element = $(element);
        $element.detach();
        $(this).append($element);
        $(this).trigger("change");
    });

});
</script>
@endsection