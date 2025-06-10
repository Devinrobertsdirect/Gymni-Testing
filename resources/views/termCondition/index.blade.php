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
               <h1>Term & Policy</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item active">Terms & Policies</li>
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
            <div class="col-md-10">
               <div class="card card-primary">
                  @if ($message = Session::get('success'))
                  <div class="alert alert-success">
                     <p>{{ $message }}</p>
                  </div>
                  @endif
                  <div class="card-header color-me">
                     <h3 class="card-title">Terms & Policies</h3>
                  </div>
               
               </div>


          <div class="card card-primary">
             <div class="card-header">
                 <a style="float:right;" class="btn btn-primary" href="{{ route('termCondition.create')}}">Create Terms & Policies</a>
              </div>
              <div class="table-responsive">
             <table id="example" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th >S. No</th>
                    <!--<th >Image</th>-->
                    <th >Title</th>
                    <th >Url</th>
                    <th style="min-width: 90px">Action</th>
                  </tr>
                  </thead>
                  <tbody>

                 @foreach($termConditionMulti as $index=> $term)

                  <tr>
                    <td>{{ $index+1 }}</td>
                    <!--<td>-->
                    <!--  @if(!empty($term->image))-->
                    <!--    <img src="{{ asset('./public/termImage/'.$term->image) }}" class="img-fluid" alt="about_image">-->
                    <!--  @endif-->
                    <!--</td>-->
                    <td>{{ $term->title  }}</td>
                    <td>{{ $term->content  }}</td>
                     <td>
                      <form action="{{ route('termCondition.destroy',$term->id) }}" method="POST">
                       <a  style="margin-right: 18px" href="{{ route('termCondition.show',$term->id) }}"><i class="fas fa-eye" style="color:#13acb4;"></i></a>
                        <a style="margin-right: 10px"  href="{{ route('termCondition.edit',$term->id) }}"><i class="fas fa-edit" style="color:#13acb4;"></i></a>
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





               </div>
            </div>
            <div class="col-md-2"></div>
         </div>
      </div>
   </section>
</div>
@endsection