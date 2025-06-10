@extends('admin.layout')
@section('content')
<style>
   img.img-fluid {
      width: 300px;
      height: 300px;
      display: block;
      margin: 0 auto;
   }


   .Default-btn {
      display: inline-block;
      font-weight: 400;
      color: #fff;
      text-align: center;
      vertical-align: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      margin-top: 10px;
      user-select: none;
      background-color: #13acb4 !important;
      /*    border: 1px solid transparent;*/
      border: 0;
      padding: 0.375rem 0.75rem;
      font-size: 1rem;
      line-height: 1.5;
      border-radius: 0.25rem;
      transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
   }

   .cancel-btn {
      display: inline-block;
      font-weight: 400;
      color: #fff;
      text-align: center;
      vertical-align: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      margin-top: 10px;
      user-select: none;
      background-color: #2F3131 !important;
      /*    border: 1px solid transparent;*/
      border: 0;
      padding: 0.375rem 0.75rem;
      font-size: 1rem;
      line-height: 1.5;
      border-radius: 0.25rem;
      transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
   }

   .add-inline-exercise {
      display: flex;
      gap: 9px;
      margin-bottom: 11px;
      justify-content: flex-start;
   }

   .exercise-wrap {
      width: 50%;
   }

   .exercise-wrap input#title {
      width: 100%;
   }

   .reps-wrap {
      width: 48%;
      display: flex;
      flex-wrap: wrap;
   }

   .reps-wrap label {
      width: 100%;
   }

   .reps-wrap input#title {
      width: 30%;
   }

   .btn-add-field {
      border: none;
      background: none;
      font-size: 20px;
      color: #9DA0A4;
   }
</style>
<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Log Weight</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item active">Edit Weight</li>
               </ol>
            </div>
         </div>
      </div>

   </section>


   <section class="content">
      <div class="card">
         <div class="card-header color-me">
            <h3 class="card-title">Edit logweight</h3>

         </div>

         <div class="card-body">
            <div class="row">
               <!-- <h1>Add logweight</h1>   -->

               <div class="card-body">
                  @if ($message = Session::get('success'))
                  <div class="alert alert-success">
                     <p>{{ $message }}</p>
                  </div>
                  @endif
                  <form method="post" action="{{url('updateLogweight')}}">
                     @csrf
                     <input type="hidden" value="{{$getLogweightid->description_mode_id}}" name="desid">
                     <div class="row">
                        <input type="hidden" name="logweightId" value="{{$getLogweightid->id}}">
                        <div class="col-lg-4">
                           <div class="form-group">
                              <label for="title">Title of the video mode</label>
                              <select class="form-control" id="title" name="workout_title" value="" placeholder="Title Of The Workout" required="">
                                 <option value="">Title of the video mode</option>
                                 @foreach($videomode as $row)
                                 <option value="{{$row->id}}" <?php if ($getLogweightid->workout_title == $row->id) {
                                                                  echo "selected";
                                                               } ?>>{{ $row->video_title }}</option>
                                 @endforeach
                              </select>
                           </div>
                           <div class="form-group mb-2">
                              <label for="title">Circuit</label>
                              <label for="staticEmail2" class="sr-only">Circuit</label>
                              <select class="form-control" name="circuit_type" id="circuit_type" required="">
                                 <option value="">Select Circuit</option>
                                 <option value="Circuit-1" <?php if ($getLogweightid->circuit_type == 'Circuit-1') {
                                                               echo "selected";
                                                            } ?>>Circuit-1</option>
                                 <option value="Circuit-2" <?php if ($getLogweightid->circuit_type == 'Circuit-2') {
                                                               echo "selected";
                                                            } ?>>Circuit-2</option>
                                 <option value="Circuit-3" <?php if ($getLogweightid->circuit_type == 'Circuit-3') {
                                                               echo "selected";
                                                            } ?>>Circuit-3</option>
                                 <option value="Circuit-4" <?php if ($getLogweightid->circuit_type == 'Circuit-4') {
                                                               echo "selected";
                                                            } ?>>Circuit-4</option>
                                 <option value="Circuit-5" <?php if ($getLogweightid->circuit_type == 'Circuit-5') {
                                                               echo "selected";
                                                            } ?>>Circuit-5</option>
                                 <option value="Circuit-6" <?php if ($getLogweightid->circuit_type == 'Circuit-6') {
                                                               echo "selected";
                                                            } ?>>Circuit-6</option>
                              </select>
                           </div>
                           <div class="form-group mb-2">
                              <label for="title">Round</label>
                              <label for="staticEmail2" class="sr-only">Round</label>
                              <select class="form-control" name="round" id="round" required="">
                                 <option value="">Select Round</option>
                                 <option value="Round-1" <?php if ($getLogweightid->round == 'Round-1') {
                                                            echo "selected";
                                                         } ?>>Round-1</option>
                                 <option value="Round-2" <?php if ($getLogweightid->round == 'Round-2') {
                                                            echo "selected";
                                                         } ?>>Round-2</option>
                                 <option value="Round-3" <?php if ($getLogweightid->round == 'Round-3') {
                                                            echo "selected";
                                                         } ?>>Round-3</option>
                                 <option value="Round-4" <?php if ($getLogweightid->round == 'Round-4') {
                                                            echo "selected";
                                                         } ?>>Round-4</option>
                                 <option value="Round-5" <?php if ($getLogweightid->round == 'Round-5') {
                                                            echo "selected";
                                                         } ?>>Round-5</option>
                                 <option value="Round-6" <?php if ($getLogweightid->round == 'Round-6') {
                                                            echo "selected";
                                                         } ?>>Round-6</option>
                              </select>
                           </div>
                        </div>

                        <div class="col-lg-8">
                           <?php
                           $ex =  (explode(",", $getLogweightid->exercise));
                           // $countt =  (explode(",", $getLogweightid->count));
                           $reps =  (explode(",", $getLogweightid->reps));
                           $i = 1;
                           foreach ($ex as $key => $row) {
                              $count = $i++;
                           ?>
                              <div class="form-group  re_{{$count}}">
                                 <div class="add-inline-exercise edit-exercise-in">
                                    <div class="exercise-wrap">
                                       <label for="title">Exercise Name</label>
                                       <input type="text" class="form-control" value="{{ $row }}" id="title" maxlength="25" name="exercise[]" value="" placeholder="Exercise Name 1" required="">
                                    </div>
                                    <div class="reps-wrap">
                                       <label for="title">Reps</label>
                                       <input type="text" class="form-control" value="{{ isset($reps[$key]) ? $reps[$key] : ''; }}" id="title" maxlength="25" name="reps[]" value="" placeholder="reps " required="">
                                       <button type="button" class="btn-add-field delete" data-value="{{$row}}" data-id="{{$count}}"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
                                    </div>

                                 </div>
                              </div>
                           <?php } ?>

                           <div class="form-group">
                              <div class="add_input"></div>
                           </div>
                           <div class="form-group">
                              <button type="button" class="btn-add-field" style="margin-left: 444px;" id="add"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                           </div>
                        </div>

                        <div class="text-center w-100 border-0">
                           <button type="submit" class="Default-btn">Submit</button>
                           <a href="{{url('logweight-List?desid='.$getLogweightid->description_mode_id)}}" class="cancel-btn">Cancel</a>
                        </div>

                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </section>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
   $(document).ready(function() {
      var i = 1;
      $('#add').click(function() {
         i++;
         //$('.add_input').append('<div class="add-inline-exercise"  id="row' + i + '"><input  maxlength="25" type="text" name="exercise[]" class="form-control" id="title" value="" placeholder="Exercise Name" required=""><button class="btn-add-field btn_remove" id="' + i + '"></button><input  maxlength="25" type="text" name="count[]" class="form-control" id="title" value="" placeholder="Count" required=""> <input  maxlength="25" type="text" name="reps[]" class="form-control" id="title" value="" placeholder="Reps" required=""> <button class="btn-add-field btn_remove" id="' + i + '"><i class="fa fa-minus-circle " aria-hidden="true"></i></button></div>')
         $('.add_input').append('<div class="add-inline-exercise"  id="row' + i + '"> <div class="exercise-wrap"><label for="title">Exercise Name</label><input  maxlength="25" type="text" name="exercise[]" class="form-control" id="title" value="" placeholder="Exercise Name" required=""></div><div class="reps-wrap"><label for="title">Reps</label><input  maxlength="25" type="text" name="reps[]" class="form-control" id="title" value="" placeholder="Reps" required=""> <button class="btn-add-field btn_remove" id="' + i + '"><i class="fa fa-minus-circle " aria-hidden="true"></i></button></div></div>')
      })
      $(document).on('click', '.btn_remove', function() {
         var button_id = $(this).attr("id");
         $('#row' + button_id + '').remove();
      });
   })


   $(document).on('click', '.delete', function() {
      var id = $(this).attr('data-id');
      $('.re_' + id).remove();
   })
</script>

@endsection