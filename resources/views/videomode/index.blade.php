@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Video Mode List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Video Mode</li>
                    </ol>
                </div>
            </div>
            <hr />
            <div class="row mb-2">
                <div class="col-sm-6">
                    <button class="btn btn-info bg-danger" id="video-mode-btn">Video Mode</button>
                    <!-- <button class="btn btn-info ml-1" id="description-mode-btn">Description Mode</button> -->
                </div>
                <div class="col-sm-6 text-right">
                    <a style="float:right;" class="btn btn-primary" href="{{ route('videomode.create')}}">Upload Video</a>
                </div>
            </div>
            <hr />
        </div>
    </section>

    <section class="content">
        <div class="card" id="video-box">
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
            @endif

            <div class="card-body">
                <div id="message"></div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>S. No</th>
                            <th>Video Title</th>
                            <th>Category</th>
                            <th>Workout Duration</th>
                            <th>Muscle Group</th>
                            <th>Equipment</th>
                            <th>Instructor</th>
                            <th>Like</th>
                            <th>Share</th>
                            <th style="min-width: 90px">Action</th>
                            <th>Flag</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($videomode as $index=>$videom)

                        <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ $videom->video_title }}</td>
                            <td>{{ $videom->category }}</td>
                            <td>{{ $videom->duration }}</td>
                            <td>{{ $videom->muscle_group }}</td>
                            <td>{{ $videom->equipment }}</td>
                            <td>{{ $videom->instructor }}</td>
                            <td>{{ $videom->total_like }}</td>
                            <td>{{ $videom->total_share }}</td>
                            <td style="display: flex; align-items: center; gap: 15px;">

                                <form action="{{ route('videomode.destroy',$videom->id) }}" method="POST" style="margin: 0;">
                                    <!-- <a style="margin-right: 15px" target="_blank" type="video/mp4" href="{{ asset('./public/videos/'.$videom->video_path) }}">
                                        <i class="fas fa-eye" style="color:#13acb4;"></i>
                                    </a> -->

                                    <a href="{{ route('videomode.show',$videom->id) }}"><i class="fas fa-eye" style="color:#13acb4;"></i></a>

                                    <a href="{{ route('videomode.edit',$videom->id) }}"><i class="fas fa-edit" style="color:#13acb4;"></i></a>
                                    @csrf
                                    @method('DELETE')
                                    <button style="border: none;" onclick="return confirm('Are you sure to delete Video?')" type="submit"><i class="fas fa-trash" style="color:#13acb4;"></i></button>
                                </form>
                                
                                <button type="submit" style="border: none; background: none; padding: 0;" class="toggle-status" data-id="{{ $videom->id }}">
                                    @if($videom->show_status == 1)
                                        <i class="fas fa-toggle-on" style="color: green; font-size: 20px;" onclick="return confirm('Are you sure to deactivate?')"></i> 
                                    @else
                                        <i class="fas fa-toggle-off" style="color: gray; font-size: 20px;" onclick="return confirm('Are you sure to activate?')" ></i>
                                    @endif
                                </button>
                                
                                
                            </td>
                            <td data-order="{{ $videom->status }}">
                                @if($videom->status == 1)
                                <i class="fa fa-circle change_status" onclick="return confirm('Are you sure to deactive?')" style="color:#13acb4;" data-id="{{ $videom->id }}"></i>
                                
                                @else
                             
                                <i class="fa fa-ban change_status" onclick="return confirm('Are you sure to active?')"  style="color:#13acb4;" data-id="{{ $videom->id }}"></i>
                                
                                @endif
                            </td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card" id="description-box" style="display: none;">
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
            @endif

            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>S. No</th>
                            <th>Image Title</th>
                            <th>Category</th>
                            <th>Muscle Group</th>
                            <th>Equipment</th>
                            <th>Instructor</th>
                            <th>Like</th>
                            <th>Share</th>
                            <th style="min-width: 90px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($desmode as $index=> $des)
                        <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ $des->img_title }}</td>
                            <td>{{ $des->category }}</td>
                            <td>{{ $des->muscle_group }}</td>
                            <td>{{ $des->equipment }}</td>
                            <td>{{ $des->instructor }}</td>
                            <td>{{ $des->like }}</td>
                            <td>{{ $des->share }}</td>
                            <td>
                                <form action="{{ route('desmode.destroy',$des->id) }}" method="POST">
                                    <a style="margin-right: 15px" href="{{ route('desmode.show',$des->id) }}"><i class="fas fa-eye" style="color:#13acb4;"></i></a>
                                    <a style="margin-right: 15px" href="{{ route('desmode.edit',$des->id) }}"><i class="fas fa-edit" style="color:#13acb4;"></i></a>
                                    @csrf
                                    @method('DELETE')
                                    <button style="border: none;" onclick="return confirm('Are you sure to delete Video?')" type="submit"><i class="fas fa-trash" style="color:#13acb4;"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

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
    $(document).ready(function(){
        $('.change_status').click(function(){
            var text = $(this).attr("data-id");
           if(text){
              $.ajax({
                     url: '{{url("fitness_status")}}',
                     method:'GET',
                     data:{v_id:text},
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
    });

    $(document).ready(function(){
        $('.toggle-status').click(function()
        {
            var videoId = $(this).data("id");
            var button = $(this);
           
            $.ajax({
                    url: '{{url("video_mode_status")}}',
                    method:'POST',
                    data:{v_id:videoId,_token: '{{ csrf_token() }}'},
                    dataType:'JSON',
                    success:function(res){
                        if(res.status ==1){
                        $('#message').html('<div class="alert alert-danger" role="alert">Video de-activate successfully !</div>');
                        } else{
                        $('#message').html('<div class="alert alert-success" role="alert">Video activate successfully !</div>')
                        }
                        setTimeout(function() {
                        location.reload();
                        }, 2000);
                    }
            });
           
        });
    });
</script>



    <script>
        $(function() {
            $(".table").dataTable();
        })

        $('#description-mode-btn').click(function() {
            $('#video-mode-btn').removeClass('bg-danger');
            $('#description-mode-btn').addClass('bg-danger');

            $('#video-box').hide();
            $('#description-box').show();
        })

        $('#video-mode-btn').click(function() {
            $('#video-mode-btn').addClass('bg-danger');
            $('#description-mode-btn').removeClass('bg-danger');

            $('#video-box').show();
            $('#description-box').hide();
        })
    </script>
</div>

@endsection