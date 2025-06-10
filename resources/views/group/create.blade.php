@extends('admin.layout')
@section('content')

<style>
   .select2-container--default .select2-selection--multiple .select2-selection__choice {
       background-color: #13acb4!important;
   }
</style>

<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Add Group</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashbord</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('groups.index')}}">Group</a></li>
                  <li class="breadcrumb-item active">Add Group</li>
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
                     <h3 class="card-title">Add Group</h3>
                  </div>
                  <div class="card-body">
                     <form action="{{ route('groups.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                      <!-- <div class="form-group">
                            <label for="exampleInputFile">Upload Group Images</label>
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

                        <div class="form-group">
                              <label for="group_name">Group Name</label>
                              <input type="text" class="form-control" id="group_name" name="group_name" value="{{old('group_name')}}" placeholder="Enter Group Name" required>
                        </div>

                        <div class="form-group">
                              <label for="content">Group Description</label>
                              <textarea onkeypress="onTestChange();"  class="form-control" id="group_description" name="group_description" rows="8" cols="50" required>{{old('group_description')}}</textarea>
                        </div>


                      <div class="form-group">
                        <label>Members</label>
                           <select class="select2" multiple="multiple" required data-placeholder="Select Group Members" name="members[]" style="width: 100%;">
                              @foreach($user as $k => $v)
                                  <option value="{{$v['id']}}">{{$v['name']}}</option>
                              @endforeach
                           </select>
                      </div>

                      <div class="form-group">
                        <label>Created By :</label>
                       Admin
                        <input type="hidden" name="created_by" value="1">
                        <!-- <select name="created_by" class="form-control" required>
                          @foreach($user as $k => $v)
                              <option value="{{$v['id']}}">{{$v['name']}}</option>
                          @endforeach
                        </select> -->
                      </div>


                     </div>


                        <div class="card-footer">
                           <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                     </form>
                  
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
        document.getElementById("group_description").value = document.getElementById("group_description").value + "\n";
        return false;
    }
    else {
        return true;
    }
}
</script>

@endsection