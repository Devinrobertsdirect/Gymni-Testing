@extends('admin.layout')

@section('content')

<style>
      .img-fluidrounded {
        overflow: hidden;
       }

       .fa-times-circle {
          position: absolute;
          top: 0;
          right: 0;
      }

      a.delete2.btn.btn-block.btn-outline-danger {
       width: 150px;
     }

    .modal-body iframe{
      width: 100% !important;
   }

   button.btn-add-exercise-new {
        background: #13acb4;
        border: none;
        padding: 5px 10px 5px 8px;
        border-radius: 5px;
        margin-top: 34px;
        height: fit-content;
    }

    input.form-control.description-input-new {
        display: block;
        width: 60%;
        height: calc(2.25rem + 2px);
        padding: .375rem .75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: .25rem;
        box-shadow: inset 0 0 0 transparent;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }

    input::placeholder {
        font-size: 14px; /* or 16px, adjust as needed */
        color: #aaa;     /* optional: make it more visible */
    }


</style>

</style>

<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               <!-- <a href="javascript:void(0)"><button type="button" class="btn btn-primary btn-lg btn-block">Edit Description Mode</button></a> -->
            </div>

            {{-- <div class="col-sm-6">
               <a href="{{url('logweight-List?desid='.$desmode->id)}}"><button type="button" class="btn btn-secondary btn-lg btn-block">Log Weight</button></a>
            </div> --}}
         </div>

         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Edit Description Mode</h1>
            </div>

            <div class="col-sm-6">

               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('desmode.index')}}">Description Mode</a></li>
                  <li class="breadcrumb-item active">Edit Description Images</li>
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
            {{-- <div class="col-md-10"> --}}
            <div class="col-md-12">
               <div class="card card-primary my-select">
                  @if ($message = Session::get('success'))
                     <div class="alert alert-success">
                        <p>{{ $message }}</p>
                     </div>
                  @endif

                  @if ($message = Session::get('error'))
                    <div class="alert alert-danger col-xs-12 col-sm-12 col-md-12 col-lg-12">
                       <p>{{ $message }}</p>
                    </div>
                  @endif

                  <div class="card-header color-me">
                     <h3 class="card-title">Edit Description Mode</h3>
                  </div>

                  <div class="card-body">
                     <div class="messages"></div>

                     <form enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="form-group">
                                <label for="img_title">Title</label>
                                <input type="img_title" class="form-control" id="img_title" name="img_title" value="{{old('img_title')??$desmode->img_title}}" placeholder="Enter Name" required>
                            </div>

                            <div class="form-group">
                                <label for="img_title">Select Description Mode</label>
                                <select class="form-control get_id"  name="video_mode_lastid" required>
                                    <option>Select Video Title</option>
                                    <?php foreach($getvideo_mode_title as $row){ ?>
                                    <option value="<?php echo $row->id ?>"<?php if($desmode->video_mode_lastid ==  $row->id){ echo "selected";} ?>><?php echo $row->video_title ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <section class="content">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="card-body">
                                                    <div class="messagebox"></div>
                                                    <div id="sections-container">
                                                        @if($exerciseDescription->isEmpty())
                                                            <!-- Render one blank section -->
                                                            <div class="video-section">
                                                                <div class="row align-description-mode">
                                                                    <div class="left-from">
                                                                        <div class="form-group">
                                                                            <label for="title">Title of the Exercise</label>
                                                                            <div class="category-description-add">
                                                                                <input maxlength="15" type="text" name="exercise_title[]" class="form-control description-input" placeholder="Title of the Exercise" required>
                                                                                <button type="button" class="btn-add-section">
                                                                                    <i class="fa fa-plus-circle"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="right-from">
                                                                        <div class="exercise-group" style="display: flex;">
                                                                            <div class="col-lg-3">
                                                                                <div class="form-group">
                                                                                    <label for="exercise">Exercise Name</label>
                                                                                    <div class="category-description-add">
                                                                                        <input type="text" maxlength="15" name="exercise[]" class="form-control" placeholder="Exercise Name" required>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-lg-2">
                                                                                <div class="form-group">
                                                                                    <label for="sets">Sets <input type="checkbox" name="sets_toggle[]" class="toggle-input" data-target="#reps-input"></label>
                                                                                    <input type="text" oninput="this.value = this.value.slice(0, 13)" name="sets[]" class="form-control" placeholder="Sets" required>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-lg-2">
                                                                                <div class="form-group">
                                                                                    <label for="reps">Reps <input type="checkbox" name="reps_toggle[]" class="toggle-input" data-target="#reps-input"></label>
                                                                                    <input type="text" name="reps[]" oninput="this.value = this.value.slice(0, 13)" class="form-control" placeholder="Reps" required>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="col-lg-2">
                                                                                <div class="form-group">
                                                                                    <label for="weight">Weight <input type="checkbox" name="weight_toggle[]" class="toggle-input" data-target="#reps-input"></label>
                                                                                    <input type="text" oninput="this.value = this.value.slice(0, 13)" name="weight[]" class="form-control" placeholder="Weight" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-2">
                                                                                <div class="form-group">
                                                                                    <label for="rpe">RPE <input type="checkbox" name="rpe_toggle[]" class="toggle-input" data-target="#reps-input"></label>
                                                                                    <input type="text" oninput="this.value = this.value.slice(0, 13)" name="rpe[]" class="form-control" placeholder="RPE" required>
                                                                                </div>
                                                                            </div>
                                                                            <button type="button" class="btn-add-exercise-new">
                                                                                <i class="fa fa-plus-circle"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>

                                                                    <div>
                                                                        <label for="text">Notes</label>
                                                                        <textarea class="form-control" rows="1" cols="40" name="notes[]" placeholder="Notes"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            @foreach($exerciseDescription->groupBy('exercise_title') as $titleIndex => $exercises)
                                                                <div class="video-section">
                                                                    <div class="row align-description-mode">
                                                                        <div class="left-from">
                                                                            <div class="form-group">
                                                                                <label for="title">Title of the Exercise</label>
                                                                                <div class="category-description-add">
                                                                                    <input maxlength="15" type="text" name="exercise_title[]" class="form-control description-input" 
                                                                                        placeholder="Title of the Exercise" value="{{ $titleIndex }}" required>
                                                                                    @if($loop->first)
                                                                                        <button type="button" class="btn-add-section">
                                                                                            <i class="fa fa-plus-circle"></i>
                                                                                        </button>
                                                                                    @else
                                                                                        <button type="button" class="btn-remove-exercise">
                                                                                            <i class="fa fa-minus-circle"></i>
                                                                                        </button>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                
                                                                        <div class="right-from">
                                                                            @foreach($exercises as $exerciseIndex => $exercise)
                                                                                <div class="exercise-group" style="display: flex;">
                                                                                    <div class="col-lg-3">
                                                                                        <div class="form-group">
                                                                                            @if($exerciseIndex == 0)
                                                                                                <label for="exercise">Exercise Name</label>
                                                                                            @endif
                                                                                            <div class="category-description-add">
                                                                                                <input type="text" maxlength="15" name="exercise[]" class="form-control" 
                                                                                                    placeholder="Exercise Name" value="{{ $exercise->exercise_name }}" required>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-lg-2">
                                                                                        <div class="form-group">
                                                                                            @if($exerciseIndex == 0)
                                                                                                <label for="sets">
                                                                                                    Sets
                                                                                                    <input type="checkbox" class="toggle-input" name="sets_toggle[]" {{ $exercise->sets_status == 'YES' ? 'checked' : '' }}>
                                                                                                </label>
                                                                                            @endif
                                                                                            <input type="text" oninput="this.value = this.value.slice(0, 13)" name="sets[]" class="form-control" placeholder="Sets" value="{{ $exercise->sets }}" required>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-lg-2">
                                                                                        <div class="form-group">
                                                                                            @if($exerciseIndex == 0)
                                                                                                <label for="reps">
                                                                                                    Reps
                                                                                                    <input type="checkbox" class="toggle-input" name="reps_toggle[]" {{ $exercise->reps_status == 'YES' ? 'checked' : '' }}>
                                                                                                    <!-- (Sec) -->
                                                                                                </label>
                                                                                            @endif
                                                                                            <input type="text" oninput="this.value = this.value.slice(0, 13)" name="reps[]" class="form-control" placeholder="Reps" value="{{ $exercise->reps }}" required>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <div class="col-lg-2">
                                                                                        <div class="form-group">
                                                                                            @if($exerciseIndex == 0)
                                                                                                <label for="weight">
                                                                                                    Weight
                                                                                                    <input type="checkbox" class="toggle-input" name="weight_toggle[]" {{ $exercise->weight_status == 'YES' ? 'checked' : '' }}>
                                                                                                </label>
                                                                                            @endif
                                                                                            <input type="text" oninput="this.value = this.value.slice(0, 13)" name="weight[]" class="form-control" placeholder="Weight" value="{{ $exercise->weight }}" required>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <div class="form-group">
                                                                                            @if($exerciseIndex == 0)
                                                                                                <label for="rpe">
                                                                                                    RPE
                                                                                                    <input type="checkbox" class="toggle-input" name="rpe_toggle[]" {{ $exercise->rpe_status == 'YES' ? 'checked' : '' }}>
                                                                                                </label>
                                                                                            @endif  
                                                                                            <input type="text" oninput="this.value = this.value.slice(0, 13)" name="rpe[]" class="form-control" placeholder="RPE" value="{{ $exercise->rpe }}" required>
                                                                                        </div>
                                                                                    </div>
                                                                                    @if($exerciseIndex == 0)
                                                                                        <button type="button" class="btn-add-exercise-new">
                                                                                            <i class="fa fa-plus-circle"></i>
                                                                                        </button>
                                                                                    @else
                                                                                        <button type="button" class="btn-remove-exercise">
                                                                                            <i class="fa fa-minus-circle"></i>
                                                                                        </button>
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                
                                                                        <!-- Notes Section (Only Once Per Exercise Title) -->
                                                                        <div>
                                                                            <label for="text">Notes</label>
                                                                            <textarea class="form-control" rows="1" cols="40"
                                                                                    name="notes[]" placeholder="Notes">{{ $exercises->first()->notes }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <br> <!-- Adding space between exercise titles -->
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                            
                            <div class="form-group">
                                <label for="category">Category</label>
                                <input type="text" class="form-control" readonly required id="category" value="{{$desmode->category}}" placeholder="" name="category" required>
                            </div>

                            <div class="form-group">
                                <label for="muscle_group">Muscle Group</label>
                                <select name="muscle_group" class="custom-select rounded-0" id="muscle_group">
                                    <option value="">Select Muscle Group</option>
                                    @foreach ($muscle_group as $skey => $val)
                                        <option class="form-control" {{ old('muscle_group')??$desmode->muscle_group == $val?'selected':'' }} value="{{ $skey }}">{{$val}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="equipment">Equipment</label>
                                <input type="text" class="form-control" maxlength="25" id="equipment" value="{{old('equipment')??$desmode->equipment}}" placeholder="Enter Muscle Group" name="equipment" required>
                            </div>

                            <div class="form-group">
                                <label for="instructor">Instructor</label>
                                <select name="instructor" class="custom-select rounded-0" id="instructor" required>
                                    <option value="">Select Instructor</option>
                                        @foreach ($instructor as $skey => $val)
                                            <option class="form-control" {{ old('instructor')??$desmode->instructor == $val?'selected':'' }} value="{{ $skey }}">{{$val}}</option>
                                        @endforeach
                                </select>
                            </div>

                            <?php  
                                $unique_countries = array();
                                foreach($get_demo_tag as $result){
                                $result_countries = explode(',',$result->tag);
                                foreach($result_countries as $country){
                                    if(!in_array($country,$unique_countries)){
                                        $unique_countries[] = $country;
                                    }
                                }
                                }
                            ?>

                            <div class="form-group">
                                <label>Select Demo Video</label>
                                <select class="select2 get_val" id="get_val" multiple="multiple"  data-placeholder="Select Demo Video" name="tag[]" style="width: 100%;">
                                @foreach($unique_countries as $unique_countriess)
                                        <option value="{{$unique_countriess}}"class="form-control"><?php echo $unique_countriess; ?></option>
                                @endforeach;
                                </select> 
                            </div>

                            <div class="" id="video">
                                <table class="table table-bordered">
                                    <tbody class="tddd" id="someTableIDs">
                                        </tr>
                                        <?php $demoid = explode(",",$desmode->demo_videoid);
                                                foreach($demoid as $vid){
                                                    $vids = $vid;
                                        ?>

                                        <?php
                                            $check ='';
                                            foreach($data as $datas){
                                            $rand = rand();
                                            $demoid = $datas->id;
                                            if($vids == $demoid){
                                                $check ='checked';
                                        ?>
                                        <tr>
                                            <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"id="flexCheckDefault" value="<?php echo $datas->id; ?>" name="demovideo[]" <?php echo $check; ?>>
                                                <label class="form-check-label" for="flexCheckDefault"></label>
                                            </div>

                                            <td><?php echo $datas->title; ?></td>
                                            <td><?php echo $datas->description; ?></td>
                                            <td><?php echo $datas->tag; ?></td>
                                            <div class="modal" id="myModal_<?php echo $rand; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title"></h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>

                                                        <!-- Modal body -->
                                                        <div class="modal-body">
                                                            <video width="100%" height="240" controls>
                                                            <source src="{{ $datas->url }}" type="video/mp4">
                                                            <source src="{{ $datas->url }}" type="video/ogg">
                                                            Your browser does not support the video tag.
                                                            </video>
                                                        </div>

                                                        <!-- Modal footer -->
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <td><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal_<?php echo $rand; ?>"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                                <button type="button" class="btn btn-danger btn-sm btnDelete"><i class="fas fa-trash" style="color:white;"></i></button> 
                                            </td>
                                        </tr>

                                        <?php } } }?>
                                    </tbody>
                                </table>

                                <div class="">
                                    <table class="table table-bordered">
                                        <tbody class="tddd" id="someTableID"></tbody>
                                    </table>
                                </div>
                            </div>

                            @php if(!empty($desmode->round_description)) 

                                $round_description = json_decode($desmode->round_description);

                            @endphp

                            @if(!empty($round_description))
                                @php $k =1; $j=0;  @endphp
                                @php $j=0;  @endphp

                                <div class="container1">
                                    @foreach($round_description as $rkey => $rval )
                                        <h1 style="padding-top:30px;">Round {{$k}}</h1>

                                            @if(!empty($rval->images))
                                                <div class="form-group">
                                                    <label for="exampleInputFile">Upload Images</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                        <input type="file" name="round_description[data{{$j}}][file][]" multiple class="custom-file-input" id="exampleInputFile"  accept="image/*" />
                                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">Upload</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    @foreach($rval->images as $img)
                                                    <div class="col-2">
                                                        <div class="img-fluidrounded">
                                                            <img src="{{ asset('./public/images/'.$img) }}" class="img-fluid" alt="description_mode">
                                                            @if(count($rval->images) > 1)
                                                            <a href="{{route('delImg',['desId'=>$desmode->id,'img'=>$img])}}" onclick="return confirm('Are you sure to delete Image?')"><i class="fas fa-times-circle"></i></a>
                                                            @endif
                                                        </div> 
                                                    </div>
                                                        <input type="hidden" name="round_description[data{{$j}}][file][]" value="{{$img}}">
                                                    @endforeach

                                                    @endif
                                                </div>

                                                <br>

                                                <div class="form-group">
                                                    <label>Round Description</label>
                                                    <textarea class="form-control" name="round_description[data{{$j}}][des][]" rows="1" placeholder="Enter Description" value="{{$rval->description}}" required>{{$rval->description}}</textarea>
                                                </div>

                                            @if(count($round_description) > 1)
                                            <a href="{{route('delBlog',['desId'=>$desmode->id,'des'=>$rval->description])}}" onclick="return confirm('Are you sure to delete this Section ?')" class="delete2 btn btn-block btn-outline-danger">Delete</a>
                                            @endif

                                                @php $k++; $j++; @endphp

                                    @endforeach
                                </div>
                            @endif

                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <div class="row" style="justify-content: center;">
                                {{-- <div class="col-md-4">
                                        <button class="add_form_field btn btn-block btn-dark">Add More Round &nbsp; 
                                            <span style="font-size:16px; font-weight:bold;">+ </span>
                                        </button>
                                </div> --}}
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary addSub">Submit</button>
                                </div>
                            </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
            <div class="col-md-4"></div>
         </div>
      </div>
   </section>
</div>

<style>
   .select2-selection__choice{
      background: #13acb4!important; 
      color: black !important;
   }
</style>

<script src="https://cdn.jsdelivr.net/npm/html-duration-picker@latest/dist/html-duration-picker.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.5.1/chosen.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.5.1/chosen.jquery.min.js"></script>
<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->

<script>

     $(".get_id").chosen();

$(document).on('change', '.get_id', function() {

   var id = $(this).val();

         if (id) {

            $.ajax({

               type: 'GET',

               url: "{{ route('getid.get_cate_video') }}",

               data: {

                  id: id

               },

               dataType: "json",

               success: function(res) {

                  $('#category').val(res.cat)

                  if(res.cat =='Cardio'){

                     $('#equipment').removeAttr('required');

                     $('#muscle_group').removeAttr('required');

                  } else{

                     $('#equipment').prop('required',true);

                    $('#muscle_group').prop('required',true);

                  }

               }

            });

         } else {



         }

      });

</script>

</script>

<script>

    $("#someTableIDs").on('click', '.btnDelete', function () {

    $(this).closest('tr').remove();

});

</script>

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



    $(document).ready(function() {

        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });



        $('.get_val').on('change', function() {

            var selected = $(".get_val").val();

            

            console.log(selected);



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
   $('#someTableID').sortable();
</script>

<script>
   $(document).on('change', '.get_id', function() {
      var value = $(this).val();
      if(value){
         $.ajax({
            url:"{{route('checkVideoMode')}}",
            method:'POST',
            data:{value:value},
            dataType:'json',
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            success:function(resp){
               if(resp.status==1){
                  $('.messages').html('<div class="alert alert-warning"><strong>Info!</strong> '+resp.message+'.</div>');
                  $(".addSub").prop('disabled', true);
               } else{
                  $(".addSub").prop('disabled', false);
                  $('.messages').html('');
               }
            }
         })
      } else{
         // console.log('error');
      }
   }) 

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



<script>

   <?php if(!empty($round_description)){ ?>

    var  f = '<?php echo count($round_description);?>';

    <?php } ?>

</script>

<script>
    $(document).ready(function() {
        // Function to add a new section
        $(document).on("click", ".btn-add-section", function() {
            var newSection = `
                <div class="video-section">
                    <div class="row align-description-mode">
                        <div class="left-from">
                            <div class="form-group">
                                <label for="title">Title of the Exercise</label>
                                <div class="category-description-add">
                                    <input maxlength="15" type="text" name="exercise_title[]" class="form-control description-input" placeholder="Title of the Exercise" required="">
                                    <button type="button" class="btn-remove-exercise">
                                        <i class="fa fa-minus-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="right-from">
                            <div class="exercise-group" style="display: flex;">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="exercise">Exercise Name</label>
                                        <div class="category-description-add">
                                            <input type="text" name="exercise[]" maxlength="15" class="form-control" placeholder="Exercise Name" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="sets">Sets <input type="checkbox" name="sets_toggle[]" class="toggle-input" data-target="#reps-input"></label>
                                        <input type="text" oninput="this.value = this.value.slice(0, 13)" name="sets[]" class="form-control" placeholder="Sets" required>
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="reps">Reps <input type="checkbox" name="reps_toggle[]" class="toggle-input" data-target="#reps-input"></label>
                                        <input type="text" oninput="this.value = this.value.slice(0, 13)" name="reps[]" class="form-control" placeholder="Reps" required>
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="weight">Weight <input type="checkbox" class="toggle-input" name="weight_toggle[]" data-target="#reps-input"></label>
                                        <input type="text" oninput="this.value = this.value.slice(0, 13)" name="weight[]" class="form-control" placeholder="Weight" required>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="rpe">RPE <input type="checkbox" class="toggle-input" name="rpe_toggle[]" data-target="#reps-input"></label>
                                        <input type="text" oninput="this.value = this.value.slice(0, 13)" name="rpe[]" class="form-control" placeholder="RPE" required>
                                    </div>
                                </div>

                                <button type="button" class="btn-add-exercise-new"><i class="fa fa-plus-circle"></i>
                                    <!-- Add exercise -->
                                </button>
                            </div>
                        </div>
                        <div>
                            <label for="text">Notes</label>
                            <textarea class="form-control" rows="1" cols="40"
                                name="notes[]" placeholder="Notes"></textarea>
                        </div>
                    </div>
                </div>
            `;
            $("#sections-container").append(newSection);
        });

        // Function to add new exercise fields inside a section
        $(document).on("click", ".btn-add-exercise-new", function() {
            var newExercise = `
                <div class="exercise-group" style="display: flex;">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <div class="category-description-add">
                                <input type="text" name="exercise[]" maxlength="15" class="form-control" placeholder="Exercise Name" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <input type="text" oninput="this.value = this.value.slice(0, 13)" name="sets[]" class="form-control" placeholder="Sets" required>
                        </div>
                    </div>
                    
                    <div class="col-lg-2">
                        <div class="form-group">
                            <input type="text" oninput="this.value = this.value.slice(0, 13)" name="reps[]" class="form-control" placeholder="Reps" required>
                        </div>
                    </div>
                    
                    <div class="col-lg-2">
                        <div class="form-group">
                            <input type="text" oninput="this.value = this.value.slice(0, 13)" name="weight[]" class="form-control" placeholder="Weight" required>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <input type="text" oninput="this.value = this.value.slice(0, 13)" name="rpe[]" class="form-control" placeholder="RPE" required>
                        </div>
                    </div>

                     <button type="button" class="btn-remove-exercise">
                        <i class="fa fa-minus-circle"></i>
                     </button>
                </div>
            `;
            $(this).closest(".right-from").append(newExercise);
        });

        // Function to remove an exercise field
        $(document).on("click", ".btn-remove-exercise", function() {
            $(this).closest(".exercise-group").remove();
        });

        // Function to remove an complete exercise-title field
        $(document).on("click", ".btn-remove-exercise", function() {
            $(this).closest(".video-section").remove();
        });
    });
</script>

<script>
    $('.addSub').click(function(e) {
        e.preventDefault();

        let descriptionData = [];

        $('.video-section').each(function() {
            let exerciseTitle = $(this).find('input[name="exercise_title[]"]').val();
            let notes = $(this).find('textarea[name="notes[]"]').val();
            let exercises = [];

            $(this).find('.exercise-group').each(function() {
                let exerciseName = $(this).find('input[name="exercise[]"]').val();
                let sets = $(this).find('input[name="reps[]"]').val();
                //let setsStatus = $(this).find('select[name="reps_toggle[]"]').val();
                let setsStatus = $(this).find('input[name="reps_toggle[]"]').is(':checked') ? 'yes' : 'no';
                let reps = $(this).find('input[name="sets[]"]').val();
                let repsStatus = $(this).find('input[name="sets_toggle[]"]').is(':checked') ? 'yes' : 'no';
                //let repsStatus = $(this).find('select[name="sets_toggle[]"]').val();

                let weight = $(this).find('input[name="weight[]"]').val();
                let weightStatus = $(this).find('input[name="weight_toggle[]"]').is(':checked') ? 'yes' : 'no';
                //let weightStatus = $(this).find('select[name="weight_toggle[]"]').val();

                let rpe = $(this).find('input[name="rpe[]"]').val();
                let rpeStatus = $(this).find('input[name="rpe_toggle[]"]').is(':checked') ? 'yes' : 'no';
                //let rpeStatus = $(this).find('select[name="rpe_toggle[]"]').val();

                exercises.push({
                    exercise_name: exerciseName,
                    sets: { value: sets, status: setsStatus },
                    reps: { value: reps, status: repsStatus },
                    weight: { value: weight, status: weightStatus },
                    rpe: { value: rpe, status: rpeStatus }
                });
            });

            descriptionData.push({
                exercise_title: exerciseTitle,
                notes: notes,
                exercises: exercises
            });
        });

        let formData = {
            img_title: $('input[name="img_title"]').val(),
            video_mode_lastid: $('select[name="video_mode_lastid"]').val(),
            description: descriptionData,
            category: $('input[name="category"]').val(),
            muscle_group: $('select[name="muscle_group"]').val(),
            equipment: $('input[name="equipment"]').val(),
            instructor: $('select[name="instructor"]').val(),
            demovideo: $('input[name="demovideo[]"]:checked').map(function() {
                return $(this).val(); // Collect the values of all checked checkboxes
            }).get(),
            tag: $('#get_val').val() || []
        };

        $.ajax({
            url: "{{ route('desmode.update', $desmode->id) }}",
            type: "PUT",
            data: JSON.stringify(formData),
            contentType: "application/json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // location.reload();
                window.location.href = "{{ route('desmode.index') }}";
            },
            error: function(response) {
                console.log(response);
            }
        });
    });
</script>

@endsection