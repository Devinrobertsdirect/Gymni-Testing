@extends('admin.layout')
@section('content')
<style>
  img.img-fluid {
    width: 100px;
    height: 100px;
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
               <h1>User Feedback Details</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item active">User Feedback Details</li>
               </ol>
            </div>
         </div>
      </div>
   </section>

   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-12">
               
          <div class="card card-primary">
            <table id="example" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S. No</th>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Content</th>
                    <th>Created Date</th>
                    <th style="min-width: 90px">Action</th>
                  </tr>
                  </thead>
                  <tbody>

               
                 @foreach($user_feedback_arr as $index => $feedback)
                  
                  <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $feedback->username  }}</td>
                    <td>{{ $feedback->email  }}</td>
                    <td>{{ $feedback->phone  }}</td>
                    <td>{{ $feedback->content  }}</td>
                    <td>{{ date('m-d-Y', strtotime($feedback->created_at)) }}</td>
                    <td>
                        <form action="{{ route('feedback.delete', $feedback->id) }}" method="POST" onsubmit="return confirmDelete()">
                              @csrf
                              @method('DELETE')
                              <button type="submit" style="border: none; background: none; cursor: pointer;">
                                 <i class="fas fa-trash" style="color:#13acb4;"></i>
                              </button>
                        </form>
                    </td>

                  </tr>

                  @endforeach
                 </tbody> 
                </table>

             



               </div>
            </div>
            <div class="col-md-2"></div>
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
      function confirmDelete() {
         return confirm("Are you sure you want to delete this feedback?");
      }
     </script>
   <script>
   $(document).ready(function(){
      $("#example").DataTable({
         "order": [[0, "desc"]] // First column (index 0) in descending order
      });
   });
   </script>
   </section>
</div>
@endsection