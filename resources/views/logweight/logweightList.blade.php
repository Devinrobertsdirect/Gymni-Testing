@extends('admin.layout')
@section('content')
<style>
   img.img-fluid {
      width: 150px;
      height: 150px;
      object-fit: cover;
   }
</style>
@php
use App\Models\User;
@endphp
<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">

               <a href="{{ route('desmode.edit',request()->desid) }}"><button type="button" class="btn btn-secondary btn-lg btn-block">Edit Description Mode</button></a>

            </div>
            <div class="col-sm-6">
               <a href="javascript:void(0)"><button type="button" class="btn btn-primary btn-lg btn-block">Log Weight</button></a>
            </div>
         </div>
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Logweight List</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item active">Logweight List</li>
               </ol>
            </div>
         </div>
      </div>

   </section>


   <section class="content">
      <div class="card">
         @if ($message = Session::get('success'))
         <div class="alert alert-success">
            <p>{{ $message }}</p>
         </div>
         @endif
         @if ($message = Session::get('error'))
         <div class="alert alert-success">
            <p>{{ $message }}</p>
         </div>
         @endif

         <div class="card-header">
            <h3 class="card-title">Logweight List</h3>
            <a style="float:right;" class="btn btn-primary" href="{{ url('add-logweight?desid='.request()->desid)}}">Create logweight </a>
         </div>
         <div class="table-responsive">
            <div class="card-body">
               <table id="example" class="table table-bordered table-striped">
                  <thead>
                     <tr>
                        <th>S. No</th>
                        <th style="min-width: 20px">Video Mode Title</th>
                        <th>Description Mode Title</th>
                        <th>Category Name</th>

                        <th>Circuit</th>
                        <th>Round</th>
                        <th>Exercise</th>
                      
                        <th>Reps</th>
                        <th style="min-width: 90px">Action</th>
                     </tr>
                  </thead>
                  <tbody>

                     @foreach($getLogweight as $index=>$row)
                     <tr>
                        <td>{{$index+1}}</td>
                        <td>{{ $row->video_title}}</td>
                        <td>@if(!empty($id->img_title)){{ $id->img_title}} @endif</td>
                        <td>{{ $row->category }}</td>
                        <td>{{ $row->circuit_type}}</td>
                        <td>{{ $row->round }}</td>
                        <td>{{ $row->exercise}}</td>
                        
                        <td>{{ $row->reps}}</td>
                        <td>
                           <a href="{{url('edit-logweight/'.$row->id)}}"><i class="fas fa-edit" style="color:#13acb4;"></i></a>
                           <a href="{{('deleteLogwight/'.$row->id)}}" onclick="return confirm('Are you sure want to delete?')"><i class="fas fa-trash" style="color:#13acb4;"></i></a>


                           <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal_{{$row->id}}"><i class="fas fa-copy" style="color:#13acb4;"></i></a>

                           <!----------------copy ex------>
                           <div class="modal fade" id="myModal_{{$row->id}}">
                              <div class="modal-dialog modal-lg">
                                 <div class="modal-content">

                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                       <h4 class="modal-title">Add/Edit Logweight</h4>
                                       <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <!-- Modal body -->
                                    <div class="modal-body">
                                       <div class="card">
                                          <div class="card-header color-me">
                                             <h3 class="card-title">Add/Edit logweight</h3>

                                          </div>

                                          <div class="card-body">
                                             <div class="row">
                                                <!-- <h1>Add logweight</h1>   -->

                                                <div class="card-body">
                                                
                                                   <form method="post" action="{{url('addLogweight')}}">
                                                      @csrf
                                                      <div class="row">
                                                         <input type="hidden" value="{{ request()->desid }}" name="desId">
                                                         <div class="col-lg-6">
                                                            <div class="form-group">
                                                               <label for="title">Title Of the Workout</label>
                                                               <select class="form-control" id="title" name="workout_title" value="" placeholder="Title Of The Workout" required="">
                                                                  <option value="">Title Of The Workout</option>
                                                                  @foreach($videomode as $row1)
                                                                  <option value="{{$row1->id}}" <?php if ($row->workout_title == $row1->id) {
                                                                                                   echo "selected";
                                                                                                } ?>>{{ $row1->video_title }}</option>
                                                                  @endforeach
                                                               </select>
                                                            </div>
                                                            <div class="form-group mb-2">
                                                               <label for="title">Circuit</label>
                                                               <label for="staticEmail2" class="sr-only">Circuit</label>
                                                               <select class="form-control" name="circuit_type" id="circuit_type" required="">
                                                                  <option value="">Select Circuit</option>
                                                                  <option value="Circuit-1" <?php if ($row->circuit_type == 'Circuit-1') {
                                                                                                echo "selected";
                                                                                             } ?>>Circuit-1</option>
                                                                  <option value="Circuit-2" <?php if ($row->circuit_type == 'Circuit-2') {
                                                                                                echo "selected";
                                                                                             } ?>>Circuit-2</option>
                                                                  <option value="Circuit-3" <?php if ($row->circuit_type == 'Circuit-3') {
                                                                                                echo "selected";
                                                                                             } ?>>Circuit-3</option>
                                                                  <option value="Circuit-4" <?php if ($row->circuit_type == 'Circuit-4') {
                                                                                                echo "selected";
                                                                                             } ?>>Circuit-4</option>
                                                                  <option value="Circuit-5" <?php if ($row->circuit_type == 'Circuit-5') {
                                                                                                echo "selected";
                                                                                             } ?>>Circuit-5</option>
                                                                  <option value="Circuit-6" <?php if ($row->circuit_type == 'Circuit-6') {
                                                                                                echo "selected";
                                                                                             } ?>>Circuit-6</option>
                                                               </select>
                                                            </div>
                                                            <div class="form-group mb-2">
                                                               <label for="title">Round</label>
                                                               <label for="staticEmail2" class="sr-only">Round</label>
                                                               <select class="form-control" name="round" id="round" required="">
                                                                  <option value="">Select Round</option>
                                                                  <option value="Round-1" <?php if ($row->round == 'Round-1') {
                                                                                             echo "selected";
                                                                                          } ?>>Round-1</option>
                                                                  <option value="Round-2" <?php if ($row->round == 'Round-2') {
                                                                                             echo "selected";
                                                                                          } ?>>Round-2</option>
                                                                  <option value="Round-3" <?php if ($row->round == 'Round-3') {
                                                                                             echo "selected";
                                                                                          } ?>>Round-3</option>
                                                                  <option value="Round-4" <?php if ($row->round == 'Round-4') {
                                                                                             echo "selected";
                                                                                          } ?>>Round-4</option>
                                                                  <option value="Round-5" <?php if ($row->round == 'Round-5') {
                                                                                             echo "selected";
                                                                                          } ?>>Round-5</option>
                                                                  <option value="Round-6" <?php if ($row->round == 'Round-6') {
                                                                                             echo "selected";
                                                                                          } ?>>Round-6</option>
                                                               </select>
                                                            </div>
                                                         </div>

                                                         <div class="col-lg-6">
                                                            <?php
                                                            $ex =  (explode(",", $row->exercise));
                                                            //$exCount =  (explode(",", $row->count));
                                                            //print_r($exCount);
                                                            $reps =  (explode(",", $row->reps));
                                                            $i = 1;
                                                            foreach ($ex as $key1 => $row) {
                                                               $count = $i++;
                                                               // print_r($exCount[$key1]) ;
                                                            ?>
                                                               <div class="form-group  re_{{$count}}">
                                                                  
                                                                  <div class="add-inline-exercise copy-modal-wrap">
                                                                     <div class="exercise-modal-wrap">
                                                                     <label for="title">Exercise Name</label>
                                                                  <input type="text" class="form-control" value="{{ $row }}" id="title" maxlength="25" name="exercise[]" value="" placeholder="Exercise Name 1" required="">
                                                                     </div>
                                                                     <div class="reps-modal-wrap">
                                                                     <label for="title">Reps</label>
                                                                  <input type="number" class="form-control" value="{{ isset($reps[$key1]) ? $reps[$key1] : '' }}" id="title" maxlength="25" name="reps[]" value="" placeholder="reps " required="">
                                                                  <!-- <button type="button" class="btn-add-field delete" data-value="{{$row}}" data-id="{{$count}}"><i class="fa fa-minus-circle" aria-hidden="true"></i></button> -->
                                                                  <button type="button" class="copy-modal-plus-btn btn-add-field add" id="add" style="float: right;"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                                                                  </div>
                                                                     </div>
                                                                  
                                                               </div>
                                                            <?php } ?>
                                                            <!-- <div class="copy-modal-plus-btn">
                                                               <button type="button" class="btn-add-field add" id="add" style="float: right;"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                                                            </div>
                                                            <br><br> -->
                                                            <div class="form-group">
                                                               <div class="add_input"></div>
                                                            </div>
                                                         </div>

                                                         <div class="text-center w-100 border-0 copy-modal-btn">
                                                            <button type="submit" class="Default-btn">Submit</button>
                                                            <a href="javascript:void(0);" class="cancel-btn"  data-dismiss="modal">Cancel</a>
                                                         </div>

                                                      </div>
                                                   </form>
                                                </div>


                                             </div>
                                          </div>
                                       </div>
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                       <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>

                                 </div>
                              </div>
                           </div>

                           <!-- Copy-End-->



                           <!-- <a style="margin-right: 15px" href="javascript:void(0)"><i class="fas fa-eye" style="color:#13acb4;"></i></a>
</td> -->
                     </tr>
                     @endforeach



                  </tbody>
               </table>
            </div>



         </div>
      </div>
      <style>
         input[type=search] {
            height: calc(2.2rem + 2px);
            padding: 0.25rem 0.7rem;
            font-size: .875rem;
            font-weight: 400;
            line-height: 1.5;
            color: #3d4246;
            background-color: #f5f5f5;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.2rem;
         }

         select[name="example_length"] {
            height: calc(2.2rem + 2px);
            padding: 0.25rem 0.7rem;
            font-size: .875rem;
            font-weight: 400;
            line-height: 1.5;
            color: #3d4246;
            background-color: #f5f5f5;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.2rem;
         }

         .paging_simple_numbers>a,
         .paging_simple_numbers>span a {
            background: #dfdfdf;
            display: inline-block;
            min-width: 40px;
            text-align: center;

            padding: 10px;
            margin: 0 2px !important;
         }

         .dataTables_length,
         .dataTables_filter,
         .dataTables_info,
         .dataTables_paginate {
            display: inline-block;
         }

         .dataTables_filter,
         .dataTables_paginate {
            float: right;
         }
      </style>
      <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
      <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
      <script>
         $(document).ready(function() {

         })
      </script>



      <script>
         $(function() {
            $("#example").dataTable();
         })
      </script>

      <script>
         $(document).ready(function() {
            var i = 1;
            $('.add').click(function() {
               i++;
               $('.add_input').append('<div class="add-inline-exercise copy-modal-wrap new-added"  id="row' + i + '"> <div class="exercise-modal-wrap"><label for="title">Exercise Name</label><input  maxlength="25" type="text" name="exercise[]" class="form-control" id="title" value="" placeholder="Exercise Name" required=""></div><div class="reps-modal-wrap"><label for="title">Reps</label> <input  maxlength="25" type="number" name="reps[]" class="form-control" id="title" value="" placeholder="Reps" required=""> <button class="btn-add-field btn_remove" id="' + i + '"><i class="fa fa-minus-circle " aria-hidden="true"></i></button></div>')
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


   </section>

</div>

@endsection