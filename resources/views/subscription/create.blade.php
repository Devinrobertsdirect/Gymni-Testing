@extends('admin.layout')
@section('content')
<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Add Subscription</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('subscription.index')}}">Subscription List</a></li>
                  <li class="breadcrumb-item active">Add Subscription</li>
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
                     <h3 class="card-title">Add Subscription</h3>
                  </div>
                  <div class="card-body">
                     <form action="{{ route('subscription.store') }}" method="POST" id="myform">
                        @csrf
                        <div class="card-body">
                           <div class="form-group">
                              <label for="name">Title</label>
                              <input type="text" class="form-control" id="title" name="title" value="{{old('title')}}" placeholder="Enter Title" required>
                           </div>

                           <!-- <div class="form-group">
                              <label for="text">Enter Description</label>
                              <textarea class="form-control" id="text" name="text" rows="4" cols="50" required>{{old('text')}}</textarea>
                          </div> -->
                          <div class="form-group">
                             <label for="text">Enter Description</label>
                             <textarea id="summernote" rows="4" cols="50" name="text" >{{old('text')}}</textarea>
                          </div>

                          
                           <div class="form-group">
                              <label for="exampleInputPassword1">Price</label>
                              <input type="text" class="form-control" id="exampleInputPassword1" name="price" value="{{old('price')}}" placeholder="Enter Price" required>
                           </div>

                          <div class="form-group">
                              <label for="discount">Discount (%)</label>
                              <input type="text" class="form-control" id="discount" name="discount" value="{{old('discount')}}" placeholder="Enter Discount" required>
                        </div>

                         <div class="form-group">
                              <label for="discount">Discount Codes</label>
                              <input type="text" class="form-control" id="discount_codes" name="discount_codes" value="{{old('discount_codes')}}" placeholder="Enter Discount Codes">
                        </div>



                         <div class="form-group">
                           <label for="exampleSelectRounded0">Select Device At a Time</label>
                           <select name="device_at_a_time" class="custom-select rounded-0" id="exampleSelectRounded0" required>
                             <option>Select Device At a Time</option>
                             <option value="1">1</option>
                             <option value="2">2</option>
                             <option value="2">3</option>
                             <option value="2">4</option>
                             <option value="2">5</option>
                             <option value="2">6</option>
                           </select>
                         </div>

                          <div class="form-group">
                           <label for="exampleSelectRounded0">Select Per Member</label>
                           <select name="per_member" class="custom-select rounded-0" id="exampleSelectRounded0" required>
                             <option>Select Per Member</option>
                             <option value="1">1</option>
                             <option value="2">2</option>
                             <option value="2">3</option>
                             <option value="2">4</option>
                             <option value="2">5</option>
                             <option value="2">6</option>
                           </select>
                         </div>

                          <div class="form-group">
                              <label for="auto_renewal">Auto Renewal</label>
                              <input type="text" class="form-control" id="auto_renewal" name="auto_renewal" value="{{old('auto_renewal')}}" placeholder="Enter Auto Renewal" required>
                        </div>
                      
                      <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="one_month_free_trial" value="1">
                          <label class="form-check-label">One Month Free Trial</label>
                        </div>
                     </div>



                     <div class="form-group">
                     <label for="auto_renewal">Plan Type</label>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="plan_for" checked="" value="Single Plan">
                          <label class="form-check-label">Single Plan</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="plan_for" checked="" value="Family Plan">
                          <label class="form-check-label">Family Plan</label>
                        </div>
                      </div>
            
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                           <button type="submit" id="mysubmit" class="btn btn-primary">Submit</button>
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

<!-- <script>
$(function () {
        $("#mysubmit").click(function () {
            if ($.trim($("#summernote").val()) == "") {
                alert("Please enter description!");
            }
        });
    });
</script> -->
@endsection