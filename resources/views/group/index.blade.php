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
               <h1>Group List</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashbord</a></li>
                  <li class="breadcrumb-item active">Group List</li>
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
          <h3 class="card-title">Group List</h3>
           <a style="float:right;" class="btn btn-primary" href="{{ route('groups.create')}}">Create Group</a>
        </div>
        <div class="table-responsive">
         <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th >S. No</th>
                    <th style="min-width: 20px">Image</th>
                    <th >Group Name</th>
                    <th >Total Members of Group</th>
                    <th >Created By</th>
                    <th style="min-width: 90px">Action</th>
                  </tr>
                  </thead>
                  <tbody>

                
                 @foreach($group as $index=>$subs)

                  <tr>
                    <td>{{ $index+1 }}</td>
                    <td>
                     @if(!empty($subs->image))
                        <img src="{{ asset('./public/group/'.$subs->image) }}" class="img-fluid" alt="group_image">
                      @endif
                    </td>
                    <td>{{ $subs->group_name }}</td>
                    <td>
                        @php
                        if(!empty($subs->members)){
                           $members = explode(',' , $subs->members);
                           echo $names = count($members);
                        }
                      @endphp
                    </td>
                   

                     <td>
                   Admin
                     </td>
                   
                   
                     <td>
                      <form action="{{ route('groups.destroy',$subs->id) }}" method="POST">

                       <a  style="margin-right: 15px"  href="{{ route('groups.show',$subs->id) }}">
                           <i class="fas fa-eye" style="color:#13acb4;"></i>
                       </a>

                        <a style="margin-right: 15px"  href="{{ route('groups.edit',$subs->id) }}"><i class="fas fa-edit" style="color:#13acb4;"></i></a>
                        @csrf
                        @method('DELETE')
                        <button style="border: none;" onclick="return confirm('Are you sure to delete this?')" type="submit"><i class="fas fa-trash" style="color:#13acb4;"></i></button>
                      </form>
                    </td>
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