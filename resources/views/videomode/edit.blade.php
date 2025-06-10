@extends('admin.layout')
@section('content')
<style>
   .hide {
      display: none;
   }

   .modal-body iframe {
      width: 100% !important;
   }
</style>

<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Edit Video</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('videomode.index')}}">Video Mode</a></li>
                  <li class="breadcrumb-item active">Edit Video</li>
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
               <div class="card card-primary my-select">
                  @if ($message = Session::get('success'))
                  <div class="alert alert-success">
                     <p>{{ $message }}</p>
                  </div>
                  @endif
                  <div class="card-header color-me">
                     <h3 class="card-title">Edit Video</h3>
                  </div>
                  <div class="card-body">

                     <form action="{{ route('videomode.update',$videomode->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">

                           <!-- <div class="form-group">
                             <label for="exampleInputFile">Upload Video</label>
                             <div class="input-group">
                               <div class="custom-file">
                                 <input type="file" value="{{old('file')??$videomode->file}}" name="file" class="custom-file-input" id="exampleInputFile">
                                 <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                               </div>
                               <div class="input-group-append">
                                 <span class="input-group-text">Upload</span>
                               </div>
                             </div>
                           </div> -->
                           <?php
                           // $get_workout_tags = [];
                           // $unique_countries = array();
                           // foreach ($get_workout_tag as $result) {
                           //    $result_countries = explode(',', $result->tag);
                           //    foreach ($result_countries as $country) {
                           //       if (!in_array($country, $unique_countries)) {
                           //          $get_workout_tags[] = $country;
                           //       }
                           //    }
                           // }
                           ?>

                           <div class="form-group">

                              <label>Select Workout Video</label>
                              <select class="form-control get_workout" id="selectVideo">
                                 <option value="">Select Video</option>


                                 @foreach ($get_workout_tag as $get_workout_tagss)



                                 <option value="{{ $get_workout_tagss->id}}">{{ $get_workout_tagss->title}}</option>

                                 @endforeach
                              </select>
                           </div>


                           <div class="" id="video">
                              <div class="">
                                 <table class="table table-bordered" id="workout_video">
                                    </tr>



                                    <?php
                                    $workout_video_id =  $videomode->workout_video_id;
                                    $check = '';
                                    foreach ($w_video as $w_videos) {
                                       $w_videosida = $w_videos->id;
                                       if ($workout_video_id == $w_videosida) {
                                          $checks = 'checked';



                                    ?>
                                          <tr>
                                             <td>
                                                <div class="form-check">
                                                   <input type="radio" class="form-check-input" id="radio2" value="<?php echo $w_videos->id ?>" <?php echo $checks; ?> name="w_video" value="option2">
                                                   <label class="form-check-label" for="radio2"></label>
                                                </div>

                                             <td><?php echo $w_videos->title; ?></td>
                                             <td><?php echo $w_videos->description; ?></td>
                                             <td><?php echo $w_videos->tag; ?></td>
                                             <div class="modal" id="myModal_<?php echo $w_videos->id; ?>">
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
                                                      <source src="{{  $w_videos->url }}" type="video/mp4">
                                                      <source src="{{  $w_videos->url }}" type="video/ogg">
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

                                             <td><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal_<?php echo $w_videos->id; ?>"><i class="fa fa-eye" aria-hidden="true"></i></button>

                                                <button type="button" class="btn btn-danger btn-sm workout_videos"><i class="fa fa-trash" aria-hidden="true"></i></button>



                                             </td>



                                          </tr>
                                    <?php }
                                    } ?>
                                 </table>
                              </div>
                           </div>



                           <div class="form-group">
                              <label for="video_title">Title</label>
                              <input type="video_title" class="form-control" id="video_title" name="video_title" value="{{old('video_title')??$videomode->video_title}}" placeholder="Enter Name" required>
                           </div>

                           <!-- <div class="form-group">
                               <label>Description</label>
                               <textarea class="form-control" name="description" rows="3" placeholder="Enter Description" value="{{old('video_title')??$videomode->description}}" required>{{old('description')??$videomode->description}}</textarea>
                           </div> -->
                           <div class="form-group">
                              <label for="text">Description</label>
                              <textarea id="summernote" rows="4" cols="50" name="description">{{old('description')??$videomode->description}}</textarea>
                           </div>


                           <div class="form-group">
                              <label for="category">Category</label>
                              <select name="category" class="custom-select rounded-0" id="category" required>
                                 <option>Select Category</option>

                                 @foreach ($category as $skey => $val)
                                 <option class="form-control" {{ (old('category') || $videomode->category == $skey)?'selected':'' }} value="{{ $skey }}">{{$val}}</option>
                                 @endforeach
                              </select>
                           </div>


                           <div class="form-group">
                              <label for="muscle_group">Muscle Group</label>
                              <select name="muscle_group" class="custom-select rounded-0" id="muscle_group" required>
                                 <option>Select Muscle Group</option>
                                 @foreach ($muscle_group as $skey => $val)
                                 <option class="form-control" {{ (old('muscle_group') || $videomode->muscle_group == $skey)?'selected':'' }} value="{{ $skey }}">{{$val}}</option>
                                 @endforeach
                              </select>
                           </div>


                           <div class="form-group">
                              <label for="equipment">Equipment</label>
                              <input type="text" class="form-control" id="equipment" value="{{old('equipment')??$videomode->equipment}}" maxlength="25" placeholder="Enter Muscle Group" name="equipment" required>
                           </div>

                           <div class="form-group">
                              <label for="instructor">Instructor</label>
                              <select name="instructor" class="custom-select rounded-0" id="instructor">
                                 <option>Select Instructor</option>
                                 @foreach ($instructor as $skey => $val)
                                 <option class="form-control" {{ (old('instructor') || $videomode->instructor  == $skey)?'selected':'' }} value="{{ $skey }}">{{$val}}</option>
                                 @endforeach
                              </select>
                           </div>
                           <div class="form-group">
                              <label for="equipment">Workout Time</label>
                              <!-- <input type="text" class="html-duration-picker form-control" value="{{old('duration')??$videomode->duration}}" name="workout_time" data-duration="00:32:10" /> -->
                              <select class="form-control" name="workout_time" required>
                                 <option value="">Select a Option</option>
                                 <option value="5" <?php if ($videomode->duration == 5) {
                                                      echo "selected";
                                                   } ?>> 5 min</option>
                                 <option value="10" <?php if ($videomode->duration == 10) {
                                                         echo "selected";
                                                      } ?>>10 min</option>
                                 <option value="20" <?php if ($videomode->duration == 20) {
                                                         echo "selected";
                                                      } ?>>20 min</option>
                                 <option value="30" <?php if ($videomode->duration == 30) {
                                                         echo "selected";
                                                      } ?>>30 min</option>
                                 <option value="45" <?php if ($videomode->duration == 45) {
                                                         echo "selected";
                                                      } ?>>45 min</option>
                                 <option value="60" <?php if ($videomode->duration == 60) {
                                                         echo "selected";
                                                      } ?>>1 hour</option>
                              </select>
                           </div>
                           <?php

                           $unique_countries = array();
                           foreach ($get_demo_tag as $resultss) {
                              $result_countries = explode(',', $resultss->tag);
                              foreach ($result_countries as $countrys) {
                                 if (!in_array($countrys, $unique_countries)) {
                                    $unique_countries[] = $countrys;
                                 }
                              }
                           }
                           ?>


                           <div class="form-group">
                              <label>Select Demo Video</label>
                              <select class="select2 get_val" id="get_val" multiple="multiple" data-placeholder="Select Demo Video" name="tag[]" style="width: 100%;">
                                 <?php

                                 foreach ($unique_countries as $unique_countriess) {


                                 ?>
                                    <option value="<?php echo $unique_countriess; ?>" class="form-control"><?php echo $unique_countriess; ?></option>
                                 <?php } ?>
                              </select>
                           </div>
                           <div class="" id="video">
                              <div class="">
                                 <table class="table table-bordered" id="someTableID">
                                    </tr>
                                    <?php $demoid = explode(",", $videomode->demo_videoid);
                                    foreach ($demoid as $vid) {
                                       $vids = $vid;

                                    ?>
                                       <?php
                                       $check = '';
                                       foreach ($data as $datas) {
                                          $demoid = $datas->id;
                                          if ($vids == $demoid) {
                                             $check = 'checked';



                                       ?>
                                             <tr>
                                                <td>
                                                   <div class="form-check">
                                                      <input class="form-check-input checked-input" type="checkbox" id="flexCheckDefault" data-tag="{{$datas->tag}}" value="<?php echo $datas->id; ?>" name="demovideo[]" <?php echo $check; ?>>
                                                      <label class="form-check-label" for="flexCheckDefault">
                                                      </label>
                                                   </div>

                                                <td><?php echo $datas->title; ?></td>
                                                <td><?php echo $datas->description; ?></td>
                                                <td><?php echo $datas->tag; ?></td>
                                                <div class="modal" id="myModal_<?php echo $datas->id; ?>">
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

                                                <td><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal_<?php echo $datas->id; ?>"><i class="fa fa-eye" aria-hidden="true"></i></button>

                                                   <button type="button" class="btn btn-danger btn-sm btnDelete"><i class="fa fa-trash" aria-hidden="true"></i></button>



                                                </td>



                                             </tr>
                                    <?php }
                                       }
                                    } ?>
                                 </table>
                              </div>
                           </div>


                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                           <button type="submit" class="btn btn-primary">Submit</button>
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
   .select2-selection__choice {
      background: #13acb4 !important;
      color: white !important;
   }

   .html-duration-picker {
      text-align: left !important;
   }
</style>
<script src="https://cdn.jsdelivr.net/npm/html-duration-picker@latest/dist/html-duration-picker.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.5.1/chosen.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.5.1/chosen.jquery.min.js"></script>
<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
// <script>
// $(document).ready(function(){
//     $('#video_title').on('keyup', function(){
//         var inputText = $(this).val();
//         var cleanedText = inputText.replace(/[^\w\s]/gi, '');
//         $(this).val(cleanedText);
//     });
// });
// </script>
<script>
     $("#selectVideo").chosen();
   $(document).ready(function() {
      $('#category').on('change', function() {
         field = $(this).val();
         if (field == 'Cardio') {
            $('#equipment').removeAttr('required');
            $('#muscle_group').removeAttr('required');
         } else {
            $('#equipment').prop('required', true);
            $('#muscle_group').prop('required', true);
         }
      })
   })
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
   $(document).on('change', '#selectVideo', function() {
      var selecteds = $(this).val();
      if (selecteds) {
         $.ajax({
            type: 'POST',
            url: "{{ route('videomode.add_workout') }}",
            data: {
               selecteds: selecteds
            },
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: "json",
            success: function(data) {
               $('#workout_video').html(data);
            }
         });
      } else {

      }
   })
   // $(document).ready(function() {
   //    $.ajaxSetup({
   //       headers: {
   //          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   //       }
   //    });
   //    $('.get_workout').on('change', function() {

   //       var selecteds = $(".get_workout").val();
   //       console.log(selecteds);

   //       // alert(myVal);
   //       if (selecteds) {
   //          $.ajax({
   //             type: 'POST',
   //             url: "{{ route('videomode.add_workout') }}",
   //             data: {
   //                selecteds: selecteds
   //             },
   //             dataType: "json",
   //             success: function(data) {
   //                //   alert(data);
   //                $('#workout_video').html(data);
   //                //  $('#video').html(data);
   //             }
   //          });
   //       } else {

   //       }


   //    });
   // })
</script>
<script>
   $(document).ready(function() {
      var allVals = [];
      var id = [];
      $('.checked-input:checked').each(function() {
         allVals.push($(this).data('tag'));
         id.push($(this).val());
      });
      //   console.log(allVals);
      $.ajaxSetup({
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
      });
      $('.get_val').on('change', function() {
         var selected = $(".get_val").val();
         var selected = selected.concat(allVals)
         // console.log(selected);
         if (selected) {
            $.ajax({
               type: 'POST',
               url: "{{ route('videomode.add_video') }}",
               data: {
                  selected: selected,
                  id: id
               },
               dataType: "json",
               success: function(data) {
                  //   alert(data);
                  $('#someTableID').html(data);
                  //  $('#video').html(data);
               }
            });
         } else {

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
   $(document).ready(function() {

      $('#get_val').select2({
         allowClear: true,

         width: 500
      });

      //  $("select").on("select2:select", function(evt) {
      //      var element = evt.params.data.element;
      //      var $element = $(element);
      //      $element.detach();
      //      $(this).append($element);
      //      $(this).trigger("change");
      //  });

   });
</script>
@endsection