@extends('admin.layout')
@section('content')
<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Upload Demo Video</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('desmode.index')}}">View Description Mode</a></li>
                  <li class="breadcrumb-item active">Add Demo Video</li>
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
            <div class="col-md-8">
               <div class="card card-primary">
                  @if ($message = Session::get('success'))
                  <div class="alert alert-success">
                     <p>{{ $message }}</p>
                  </div>
                  @endif
                  <div class="card-header color-me">
                     <h3 class="card-title">Add Demo Video</h3>
                  </div>
                  <div class="card-body">
                     <form action="{{ route('demoVideo',$id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">

                         <div class="form-group">
                             <label for="exampleInputFile">Upload Demo Video</label>
                             <div class="input-group">
                               <div class="custom-file">
                                 <input type="file" name="file" class="custom-file-input" id="exampleInputFile" required>
                                 <input type="hidden" name="id" value={{$id}}>
                                 <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                               </div>
                               <div class="input-group-append">
                                 <span class="input-group-text">Upload</span>
                               </div>
                             </div>
                           </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                           <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
            <div class="col-md-4"></div>
         </div>
      </div>
   </section>
</div>
@endsection