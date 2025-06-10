@extends('admin.layout')
@section('content')

<style>
   .select2-container--default .select2-selection--multiple .select2-selection__choice {
       background-color: #13acb4!important;
   }
</style>

<style>
   i.fas.fa-times-circle {
    position: absolute;
    top: 0;
    right: 0;
    color: red;
}
.hide{
      display:none;
   }
</style>

<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Edit Group</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashbord</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('groups.index')}}">Group</a></li>
                  <li class="breadcrumb-item active">Edit Group</li>
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
                     <h3 class="card-title">Edit Group</h3>
                  </div>
                  <div class="card-body">
                    
                     <form action="{{ route('groups.update',$group->id) }}" method="POST" enctype="multipart/form-data">
                         @csrf
                        @method('PUT')
<!-- 
                      <div class="form-group">
                            <label for="exampleInputFile">Upload Group Images</label>
                          <div class="input-group">
                            <div class="custom-file">
                              <input type="file" name="file" value="{{old('file')??$group->image}}" multiple class="custom-file-input exampleInputFile" id="exampleInputFile" accept="image/*" data-type='image'>
                              <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                            </div>
                              <div class="input-group-append">
                              <span class="input-group-text">Upload</span>
                              </div>
                            </div>
                         </div> -->

                         <!-- <div class="form-group">
                                <label for="exampleInputFile">Upload Image</label><br>
                                <input type="file" name="file" value="{{$group->image}}"  accept="image/*" data-type='image'>
                         </div> -->

                         <div class="form-group hide" id="up">
                                <label for="exampleInputFile">Upload Image</label><br>
                                <input type="file" name="file" value="{{$group->image}}"  accept="image/*" data-type='image'>
                        </div>

                        <div class="form-group" id="up1">
                              <button type="button" class="mybutton btn btn-default" onclick="showMsg()">Upload Image</button>
                              <p style="font-size: 14px;">{{$group->image}}</p>
                        </div>

                        <div class="form-group">
                              @if(!empty($group->image))
                              <div class="col-2">
                                    <div class="img-fluidrounded">
                                       <img src="{{ asset('./public/group/'.$group->image) }}" class="img-fluid" alt="description_mode">
                                             <a href="{{route('delImgGrp',['Id'=>$group->id])}}" onclick="return confirm('Are you sure to delete Image?')"><i class="fas fa-times-circle"></i></a>
                                    </div> 
                                 </div>
                              @endif
                               
                         </div>



                        <div class="form-group">
                              <label for="group_name">Group Name</label>
                              <input type="text" class="form-control" id="group_name" name="group_name" value="{{old('group_name')??$group->group_name}}" placeholder="Enter Group Name" required>
                        </div>
 
                       
                        <div class="form-group">
                              <label for="content">Group Description</label>
                              <textarea   onkeypress="onTestChange();" class="form-control" value="{{old('group_description'??$group->group_description)}}" id="group_description" name="group_description" rows="8" cols="50" required>{{ $group->group_description }}</textarea>
                        </div>


                      <div class="form-group">
                        <label>Members</label>
                           <select class="select2" multiple="multiple" required data-placeholder="Select Group Members" name="members[]" style="width: 100%;">
                              @foreach($user as $k => $v)
                                 <option value="{{ $v['id'] }}"   @foreach($memb as $mm){{$mm == $v['id'] ? 'selected': ''}}   @endforeach> {{ $v['name'] }}</option>

                                 
                              @endforeach
                           </select>
                      </div>

                      <div class="form-group">
                        <label>Created By: </label>
                        Admin
                        <input type="hidden" name="created_by" value="{{$group->created_by == 1}}">
                        <!-- <select name="created_by" class="form-control" required>
         
                              @foreach ($user as $skey => $val)
                                 <option class="form-control" {{ old('created_by')??$group->created_by == $val?'selected':'' }} value="{{ $v['id'] }}">{{$v['name']}}</option>
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

<script>
   function showMsg (){
      $( "#up" ).removeClass( "hide" );
      $("#up1").addClass("hide");
   }
</script>
@endsection