@extends('admin.layout')
@section('content')
<style>
   .avatar {
  vertical-align: middle;
  width: 75px;
  height: 75px;
  border-radius: 50%;
}
</style>
<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Users List</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item active">Users</li>
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

        <div class="card-header">
          <h3 class="card-title">Users List</h3>
           <!-- <a style="float:right;" class="btn btn-primary" href="{{ route('users.create')}}">Create User</a> -->
        </div>

         <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th >S. No</th>
                    <th>Profile Img</th>
                    <th >Name</th>
                    <th >Email</th>
                    <th style="min-width: 100px">Date Of Birth</th>
                    <th >Gender</th>
                    <th >Weight</th>
                    <th >Gols</th>
                    <!-- <th >Profile Bio</th> -->
                    <th >Created At</th>
                    <th style="min-width: 90px">Action</th>

                  </tr>
                  </thead>
                  <tbody>
                
                 @foreach($users as $index=>$user)
                    

                  <tr>
                    <td>{{ $index+1 }}</td>
                    <td>
                    <?php if($user->profile_img){?>
                    <img src="<?php echo $user->profile_img ?> " class="avatar">
                  <?php } else { ?>
                  <img src="img_avatar.png" class="avatar">
                  <?php } ?>
                  </td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email  }}</td>
                    <td>{{ $user->dob }}</td>
                    <td>{{ $user->gender  }}</td>
                    <td>{{ $user->weight  }}</td>
                    <td>{{ $user->gols  }}</td>
                <!--     <td>{{ $user->profile_bio  }}</td> -->
                    <td>
                     <?php 
                        $old_date_timestamp = strtotime($user->created_at );
                     ?>
                     {{ $new_date = date('m-d-Y H:i:s', $old_date_timestamp)  }}
                  </td>

                     <td>
                      <form action="{{ route('users.destroy',$user->id) }}" method="POST">
                       <a  style="margin-right: 15px" href="{{ route('users.show',$user->id) }}"><i class="fas fa-eye" style="color:#13acb4;"></i></a>
                       <!--  <a style="margin-right: 15px"  href="{{ route('users.edit',$user->id) }}"><i class="fas fa-edit" style="color:#13acb4;"></i></a> -->
                        @csrf
                        @method('DELETE')
                        <button style="border: none;" onclick="return confirm('Are you sure to delete User?')" type="submit"><i class="fas fa-trash" style="color:#13acb4;"></i></button>
                      </form>
                    </td>
                  </tr>

                  @endforeach
                 </tbody> 
                </table>

            

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
  $(function(){
    $("#example").dataTable();
  })
  </script>
    </section>

</div>

@endsection