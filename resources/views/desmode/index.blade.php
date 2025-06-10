@extends('admin.layout')

@section('content')

<div class="content-wrapper">

  <section class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1>Description Mode List</h1>

        </div>

        <div class="col-sm-6">

          <ol class="breadcrumb float-sm-right">

            <li class="breadcrumb-item"><a href="#">Home</a></li>

            <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>

            <li class="breadcrumb-item active">Description Mode</li>

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

        <h3 class="card-title">Description Mode List</h3>

        <a style="float:right;" class="btn btn-primary" href="{{ route('desmode.create')}}">Add Workout</a>

      </div>



      <div class="card-body">

        <table id="example" class="table table-bordered table-striped">

          <thead>

            <tr>

              <th>S. No</th>

              <th>Description Title</th>

              <th>Video mode Title</th>

              <th>Category</th>

              <!--   <th >Duration</th> -->

              <th>Muscle Group</th>

              <th>Equipment</th>

              <th>Instructor</th>

              <!--   <th >Demo Video</th> -->

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

              <td>{{ $des->video_title }}</td>

              <td>{{ $des->category }}</td>

              <!--   <td>{{ $des->duration }}</td> -->

              <td>{{ $des->muscle_group }}</td>

              <td>{{ $des->equipment }}</td>

              <td>{{ $des->instructor }}</td>

              <td>{{ $des->total_like }}</td>

              <td>{{ $des->total_share }}</td>

              <td>
                <form class="table-form" action="{{ route('desmode.destroy',$des->id) }}" method="POST">
                  <a style="margin-right: 15px" href="{{ route('desmode.show',$des->id) }}"><i class="fas fa-eye" style="color:#13acb4;"></i></a>
                  <a style="margin-right: 15px" href="{{ route('desmode.edit',$des->id) }}"><i class="fas fa-edit" style="color:#13acb4;"></i></a>
                  @csrf
                  @method('DELETE')
                  <button style="border: none;" onclick="return confirm('Are you sure to delete Video?')" type="submit"><i class="fas fa-trash" style="color:#13acb4;"></i></button>
                  {{-- <a style="margin-right: 15px" href="{{ url('add-logweight?desid='.$des->id)}}"><i class="fa fa-plus-circle" style="color:#13acb4;"></i></a> --}}
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

    $(function() {

      $("#example").dataTable();

    })

  </script>



</div>



@endsection