@extends('admin.layout')
@section('content')
<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Add About Us</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('aboutus.index')}}">About Us</a></li>
                  <li class="breadcrumb-item active">Add About Us</li>
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
                     <h3 class="card-title">Add About Us</h3>
                  </div>
                  <div class="card-body">
                     <form action="{{ route('aboutus.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                           <div class="form-group">
                              <label for="title">Title</label>
                              <input type="text" class="form-control" id="title" name="title" value="{{old('title')}}" placeholder="Enter Title" required>
                           </div>

                        <div class="form-group">
                              <label for="content">Content</label>
                              <textarea onkeypress="onTestChange();" class="form-control" id="content" name="content" rows="4" cols="50" required>{{old('content')}}</textarea>
                        </div>

                        <!-- <div class="form-group">
                            <label for="exampleInputFile">Upload Images</label>
                          <div class="input-group">
                            <div class="custom-file">
                              <input type="file" name="file"  multiple class="custom-file-input exampleInputFile" id="exampleInputFile" required accept="image/*" data-type='image'>
                              <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                            </div>
                              <div class="input-group-append">
                              <span class="input-group-text">Upload</span>
                              </div>
                            </div>
                         </div> -->


                         <div class="form-group">
                                <label for="exampleInputFile">Upload Image</label><br>
                                <input type="file" name="file" accept="image/*" data-type='image'>
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
<script>
function onTestChange() {
 
    var key = window.event.keyCode;

    // If the user has pressed enter
    if (key === 13) {
        document.getElementById("content").value = document.getElementById("content").value + "\n";
        return false;
    }
    else {
        return true;
    }
}
</script>
@endsection