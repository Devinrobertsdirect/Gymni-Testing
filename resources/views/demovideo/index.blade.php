@extends('admin.layout')

@section('content')

<style>
   img.img-fluid {

      width: 150px;

      height: 150px;

      object-fit: cover;

   }



   .modal-body iframe {

      width: 100% !important;

   }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">





@php

use App\Models\User;

@endphp

<div class="content-wrapper">

   <section class="content-header">

      <div class="container-fluid">

         <div class="row mb-2">

            <div class="col-sm-6">

               <h1>Demo Video List</h1>

            </div>

            <div class="col-sm-6">

               <ol class="breadcrumb float-sm-right">

                  <li class="breadcrumb-item"><a href="#">Home</a></li>

                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>

                  <li class="breadcrumb-item active">Demo Video List</li>

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

            <h3 class="card-title">Demo Video List</h3>
            <a style="float:right;" class="btn btn-primary" href="{{ route('demovideo.create-video')}}">Create Demo Video</a>

         </div>



         <div class="card-body">

            <div id="message"></div>

            <div class="table-responsive">

               <table id="example" class="table table-bordered table-striped">

                  <thead>

                     <tr>

                        <th>S. No</th>



                        <th>Title Name</th>



                        <th>Demo Video Code</th>
                        <th>Tutorial Video Code</th>

                        <th>Description</th>

                        <th>Tag</th>

                        <!-- <th>Thumb Img</th> -->

                        <th>Costum Thumb Img</th>

                        <!-- <th>Category</th> -->

                        <th style="min-width: 90px">Action</th>



                     </tr>

                  </thead>

                  <tbody>



                     @foreach ($users as $index=>$w)

                     <tr>

                        <td>{{$index + 1}}</td>

                        <td> {{ $w->title }} </td>

                        <td> {{ $w->url }} </td>
                        <td> {{ $w->url2 }} </td>
                        <td> {{ $w->description }} </td>

                        <td> {{ $w->tag }} </td>

                        <!-- <td><img src="{{ $w->thum_img }}"  width="171px">     </td> -->

                        <td><img src="{{url('/costumThumbimg/'.$w->costum_thumImg)}}" alt="{{$w->title}}" width="171px">
                        </td>

                        <!-- <td> {{ $w->category }}    </td> -->

                        <td>




                           @if($w->url)
                              <a style="margin-right: 12px" href="javascript:void(0);" data-videoUrl="{{ $w->url }}"
                                 data-toggle="modal" data-videotitle="Demo Video" class="getvideo" data-target="#exampleModal">
                                 <i class="fas fa-eye" style="color:#13acb4;"></i>
                              </a>
                           @endif

                           @if($w->url2)
                              <a style="margin-right: 12px" href="javascript:void(0);" data-videoUrl="{{ $w->url2 }}"
                                 data-toggle="modal" data-videotitle="Tutorial Video" class="getvideo" data-target="#exampleModal">
                                 <i class="fas fa-eye" style="color:#13acb4;"></i>
                              </a>
                           @endif

                           <a style="margin-right: 5px" href="edit-video/{{ $w->id }}"><i class="fas fa-edit"
                                 style="color:#13acb4;"></i></a>

                           <a href='delete-video/{{ $w->id }}'> <button style="border: none;"
                                 onclick="return confirm('Are you sure to delete Video?')" type="submit"><i
                                    class="fas fa-trash" style="color:#13acb4;"></i></button></a>



                        </td>



                     </tr>

                     @endforeach

                  </tbody>

               </table>

            </div>

         </div>

      </div>



      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">

         <div class="modal-dialog" role="document">

            <div class="modal-content">

               <div class="modal-header">

                  <h5 class="modal-title" id="video_title"></h5>

                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                     <span aria-hidden="true">&times;</span>

                  </button>

               </div>

               <div class="modal-body videoMode">

                  <video controls name="media" style="width: 470px !important">

                     <source src="" type="video/mp4">

                  </video>

               </div>



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

         .current {

            color: blue;

         }
      </style>

      <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

      <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

      <script>
         $(document).on('click', '.getvideo', function(){

    var url = $(this).attr('data-videoUrl');
    var title = $(this).attr('data-videotitle');

    
    $('#video_title').html(title);
    $('.videoMode').html('<video controls name="media" style="width: 470px !important"><source src="' + url + '" type="video/mp4"></video>');

});



      

      

      

      

  $(function(){

    $("#example").dataTable();

  })

      </script>

      <script>
         $(document).ready(function(){

         $('.change_status').click(function(){

               var text = $(this).attr("data-id");

            if(text){

               $.ajax({

                        url: '{{url("demo_video_status")}}',

                        method:'POST',

                        data:{v_id:text,_token: '{{ csrf_token() }}'},

                        dataType:'JSON',

                        success:function(res){

                           if(res.status ==1){

                              $('#message').html('<div class="alert alert-danger" role="alert">Fitness de-active successfully !</div>');

                           } else{

                              $('#message').html('<div class="alert alert-success" role="alert">Fitness active successfully !</div>')

                           }

                              setTimeout(function() {

                              location.reload();

                              }, 3000);

                        }

               })

            }

         })

      })

      </script>

   </section>



</div>



@endsection