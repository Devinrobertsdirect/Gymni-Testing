@extends('admin.layout')
@section('content')
<style>
   img.img-fluid {
    width: 150px;
    height: 150px;
    object-fit: cover;
}
.card-header {
    background: white!important;
}
div#example1_filter {
    padding-top: 14px;
}
</style>

<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Workout</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Workout</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item active">Workout</li>
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
            <div class="col-md-12">
               <div class="card card-primary">
                 
                  <div class="card-header color-me">
                     <h3 class="card-title">Privacy Policy</h3>
                  </div>
                  <div class="card-body">
                  @if ($message = Session::get('success'))
                  <div class="alert alert-success">
                     <p>{{ $message }}</p>
                  </div>
                  @endif
                     <?php if(!empty($_GET['edit_id'])){ ?>
            
                    <form class="form-inline" method="post" action="{{ route('update_workout') }}">
                        @csrf
                        <input type="hidden" class="form-control" name="id" value="<?php if(!empty($edit_workout[0]->id)){ echo $edit_workout[0]->id;} ?>" id="Workout Type" placeholder="Enter Workout Type">
                    <div class="form-group mb-2">
                    <label for="staticEmail2" class="sr-only">Email</label>
                    <select class="form-control" name="goal_type" id="goal_type" required>
                        <option value="">Select Goal Type</option>
                        <option value="Weekly" <?php if($edit_workout[0]->goal_type == 'Weekly'){ echo "selected"; } ?>>Weekly</option>
                        <option value="Monthly" <?php if($edit_workout[0]->goal_type == 'Monthly'){ echo "selected"; } ?>>Monthly</option>

                    </select>
                    </div>
                    <div class="form-group mx-sm-3 mb-2">
                    <label for="inputPassword2" class="sr-only">Workout Type</label>


                   
                    <select class="form-control" required name="workout_type" id="Workout_Type1" required>
                       <option value="">Select a option</option>
                       <?php if($edit_workout[0]->goal_type == 'Weekly') { ?>
                        <option value="Complete X workouts"  <?php if($edit_workout[0]->workout_type == 'Complete X workouts'){ echo "selected"; } ?>>Complete X workouts</option>
                        <option value="Hit my step"  <?php if($edit_workout[0]->workout_type == 'Hit my step'){ echo "selected"; } ?>>Hit my step</option>
                        <option value="Reach my water intake"  <?php if($edit_workout[0]->workout_type == 'Reach my water intake'){ echo "selected"; } ?>>Reach my water intake</option>
                        <option value="Restful"  <?php if($edit_workout[0]->workout_type == 'Restful'){ echo "selected"; } ?>>Restful</option>
                        <option value="Feeling loose"  <?php if($edit_workout[0]->workout_type == 'Feeling loose'){ echo "selected"; } ?>>Feeling loose</option>
                        <?php } else{ ?>
                            <option value="Slim down"  <?php if($edit_workout[0]->workout_type == 'Slim down'){ echo "selected"; } ?>>Slim down</option>
                            <option value="Bulking season"  <?php if($edit_workout[0]->workout_type == 'Bulking season'){ echo "selected"; } ?>>Bulking season</option>
                            <option value="Challenge accepted"  <?php if($edit_workout[0]->workout_type == 'Challenge accepted'){ echo "selected"; } ?>>Challenge accepted</option>
                        <?php } ?> 
                    </select>
                  


                 
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Add</button>
                    </form>
 
                     <?php } else{ ?>
                        <form class="form-inline" method="post" action="{{ route('add-workout-data') }}">
                        @csrf
                    <div class="form-group mb-2">
                    <label for="staticEmail2" class="sr-only">Email</label>
                    <select class="form-control" name="goal_type" id="goal_type" required>
                        <option value="">Select Goal Type</option>
                        <option value="Weekly">Weekly</option>
                        <option value="Monthly">Monthly</option>

                    </select>
                    </div>
                    <div class="form-group mx-sm-3 mb-2">
                    <label for="inputPassword2" class="sr-only">Workout Type</label>
                    <select class="form-control" required name="workout_type" id="Workout_Type" required>
                       <option value="">Select a option</option>
                    </select>
                    <!-- <input type="text" class="form-control" name="workout_type" id="Workout_Type" placeholder="Enter Workout Type" required> -->
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Add</button>
                    </form>
                    <?php } ?>
                  </div>
               </div>


          <!---------------datatable here code------------------------->
          <div class="card card-primary">
             <!-- <div class="card-header">
                 <a style="float:right;" class="btn btn-primary" href="{{ route('privacypolicy.create')}}">Create Privacy Policy</a>
              </div> -->
              <div class="table-responsive">
             <table id="example" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th >S. No</th>
                    <th >Goal Type</th>
                    <th >workout</th>
                    <th style="min-width: 90px">Action</th>
                  </tr>
                  </thead>
                  <tbody>

               
                 @foreach($workout as $index=> $term)

                  <tr> 
                    <td>{{ $index+1 }}</td>
                    <td>{{ $term->goal_type  }}</td>
                    <td width="100%">{{ $term->workout_type  }}</td>
                     <td>
                    
                       <a  style="margin-right: 18px" href="{{ route('delete_goal') }}?id={{$term->id}}" onclick="return confirm('Are you sure to delete this?')"><i class="fas fa-trash" style="color:#13acb4;"></i></a>
                        <a style="margin-right: 10px"  href="{{ route('update_data') }}?edit_id={{$term->id}}"><i class="fas fa-edit" style="color:#13acb4;"></i></a>
                        
                    </td>
                  </tr>

                  @endforeach
                 </tbody> 
                </table>
              </div>


               </div>
            </div>
            <div class="col-md-2"></div>
         </div>
          <!-----------------------end code datatable---------------->
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
.paging_simple_numbers>a, .paging_simple_numbers>span a {
    background: #dfdfdf;
    display: inline-block;
    min-width: 40px;
    text-align: center;
    
    padding: 10px;
    margin: 0 2px !important;
}
.dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate {
    display: inline-block;
}
.dataTables_filter, .dataTables_paginate {
   float: right;
}
.dataTables_wrapper {
   margin-top: 6px;
}
.paging_simple_numbers{
   padding: 6px;
}
.dataTables_info{
   padding: 6px;
}
.dataTables_wrapper {
   padding: 7px;
}
   
      </style>
     <script src="https://code.jquery.com/jquery-3.5.1.js"></script>  
     <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
     <script>

                var lookup = {
                'Weekly': ['Complete X workouts', 'Hit my step','Reach my water intake','Restful','Feeling loose'],
                'Monthly': ['Slim down', 'Bulking season', 'Challenge accepted'],
                
                };


          $(document).ready(function(){
            $('#goal_type').on('change', function() {
                $('#Workout_Type').empty();
                var selectValue = $(this).val();
              
                for (i = 0; i < lookup[selectValue].length; i++) {
            // Output choice in the target field
            $('#Workout_Type').append("<option value='" + lookup[selectValue][i] + "'>" + lookup[selectValue][i] + "</option>");
            }
            
 
            })
          })


          var lookup = {
                'Weekly': ['Complete X workouts', 'Hit my step','Reach my water intake','Restful','Feeling loose'],
                'Monthly': ['Slim down', 'Bulking season', 'Challenge accepted'],
                
                };


          $(document).ready(function(){
            $('#goal_type').on('change', function() {
                $('#Workout_Type1').empty();
                var selectValue = $(this).val();
              
                for (i = 0; i < lookup[selectValue].length; i++) {
            // Output choice in the target field
            $('#Workout_Type1').append("<option value='" + lookup[selectValue][i] + "'>" + lookup[selectValue][i] + "</option>");
            }
            
 
            })
          })

           
           

            // For each chocie in the selected option
          

     </script>
      <script>
  $(function(){
    $("#example").dataTable();
  })
  </script>
   </section>
</div>
@endsection