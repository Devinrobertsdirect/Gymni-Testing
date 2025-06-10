@extends('admin.layout')
@section('content')
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
               <h1>Edit Term & Policies</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('termCondition.index')}}">Term & Policies</a></li>
                  <li class="breadcrumb-item active">Edit Term & Policies</li>
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
                     <h3 class="card-title">Edit Term & Policies</h3>
                  </div>
                  <div class="card-body">
                     <form action="{{ route('termCondition.update',$termCondition->id) }}" method="POST" enctype="multipart/form-data">
                         @csrf
                        @method('PUT')
                        <div class="card-body">
                           <div class="form-group">
                              <label for="title">Title</label>
                              <input type="text" class="form-control" id="title" name="title" value="{{old('title')??$termCondition->title}}" placeholder="Enter title" required>
                           </div>

                        <div class="form-group">
                              <label for="content">Url</label>
                              <!--<textarea onkeypress="onTestChange();" class="form-control" id="content" name="content" rows="4" cols="50" required>{{old('content')??$termCondition->content}}</textarea>-->
                               <input type="text" class="form-control" id="content" name="content" value="{{old('content')??$termCondition->content}}" placeholder="Enter Url" required>
                        </div>

                        <!-- <div class="form-group">
                            <label for="exampleInputFile">Upload Images</label>
                          <div class="input-group">
                            <div class="custom-file">
                              <input type="file" name="file" value="{{$termCondition->image}}" multiple class="custom-file-input exampleInputFile" id="exampleInputFile" accept="image/*" data-type='image'>
                              <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                            </div>
                              <div class="input-group-append">
                              <span class="input-group-text">Upload</span>
                              </div>
                            </div>
                         </div> -->

                         <!-- <div class="form-group">
                                <label for="exampleInputFile">Upload Image</label><br>
                                <input type="file" name="file" value="{{$termCondition->image}}"  accept="image/*" data-type='image'>
                         </div> -->

                        <!-- <div class="form-group hide" id="up">-->
                        <!--        <label for="exampleInputFile">Upload Image</label><br>-->
                        <!--        <input type="file" name="file" value="{{$termCondition->image}}"  accept="image/*" data-type='image'>-->
                        <!--</div>-->

                        <!--<div class="form-group" id="up1">-->
                        <!--      <button type="button" class="mybutton btn btn-default" onclick="showMsg()">Upload Image</button>-->
                        <!--      <p style="font-size: 14px;">{{$termCondition->image}}</p>-->
                        <!--</div>-->

                        <div class="form-group">
                              @if(!empty($termCondition->image))
                              <div class="col-2">
                                    <div class="img-fluidrounded">
                                       <img src="{{ asset('./public/termImage/'.$termCondition->image) }}" class="img-fluid" alt="description_mode">
                                             <a href="{{route('delImgTerm',['Id'=>$termCondition->id])}}" onclick="return confirm('Are you sure to delete Image?')"><i class="fas fa-times-circle"></i></a>
                                    </div> 
                                 </div>
                              @endif
                               
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

<script>
   function showMsg (){
      $( "#up" ).removeClass( "hide" );
      $("#up1").addClass("hide");
   }
</script>
@endsection