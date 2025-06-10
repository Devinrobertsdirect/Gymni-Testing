@extends('admin.layout')
@section('content')
<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Edit Subscription</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('subscription.index')}}">Subscription List</a></li>
                  <li class="breadcrumb-item active">Edit Subscription</li>
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
                     <h3 class="card-title">Edit Subscription</h3>
                  </div>
                  <div class="card-body">
                        <form action="{{ route('subscription.update',$subscription->id) }}" method="POST">
                        @csrf
                         @method('PUT')

                        <div class="card-body">
                           <div class="form-group">
                              <label for="name">Title</label>
                              <input type="text" class="form-control" id="title" name="title" value="{{old('title')??$subscription->title}}" placeholder="Enter title" required>
                           </div>

                           <!-- <div class="form-group">
                              <label for="text">Enter Description</label>
                              <textarea class="form-control" id="text" name="text" rows="4" cols="50" required>{{old('text')??$subscription->text}}</textarea>
                          </div> -->

                          <div class="form-group">
                             <label for="text">Enter Description</label>
                             <textarea id="summernote" rows="4" cols="50" name="text" >{{old('text')??$subscription->text}}</textarea>
                          </div>


                          
                           <div class="form-group">
                              <label for="exampleInputPassword1">Price</label>
                              <input type="text" class="form-control" id="exampleInputPassword1" name="price" value="{{old('price')??$subscription->price}}" placeholder="Enter price" required>
                           </div>

                          <div class="form-group">
                              <label for="discount">Discount (%)</label>
                              <input type="text" class="form-control" id="discount" name="discount" value="{{old('discount')??$subscription->discount}}" placeholder="Enter discount" required>
                        </div>

                         <div class="form-group">
                              <label for="discount">Discount Codes</label>
                              <input type="text" class="form-control" id="discount_codes" name="discount_codes" value="{{old('discount_codes')??$subscription->discount_codes}}" placeholder="Enter discount codes">
                        </div>



                         <div class="form-group">
                           <label for="exampleSelectRounded0">Select Device At a Time</label>
                           <select name="device_at_a_time" class="custom-select rounded-0" id="exampleSelectRounded0" required>
                             <option>Select Device At a Time</option>
                             <option value="1" @if($subscription->device_at_a_time == 1) selected  @endif >1</option>
                             <option value="2" @if($subscription->device_at_a_time == 2)  selected @endif>2</option>
                             <option value="2" @if($subscription->device_at_a_time == 3) selected @endif>3</option>
                             <option value="2" @if($subscription->device_at_a_time == 4)  selected @endif>4</option>
                             <option value="2" @if($subscription->device_at_a_time == 5)selected @endif>5</option>
                             <option value="2" @if($subscription->device_at_a_time == 6) selected @endif>6</option>
                           </select>
                         </div>

                          <div class="form-group">
                           <label for="exampleSelectRounded0">Select Per Member</label>
                           <select name="per_member" class="custom-select rounded-0" id="exampleSelectRounded0" required>
                             <option>Select Per Member</option>
                             <option value="1" @if($subscription->per_member == 1) selected @endif>1</option>
                             <option value="2" @if($subscription->per_member == 2) selected @endif>2</option>
                             <option value="2" @if($subscription->per_member == 3) selected @endif>3</option>
                             <option value="2" @if($subscription->per_member == 4) selected @endif>4</option>
                             <option value="2" @if($subscription->per_member == 5) selected @endif>5</option>
                             <option value="2" @if($subscription->per_member == 6) selected @endif>6</option>
                           </select>
                         </div>

                          <div class="form-group">
                              <label for="auto_renewal">Auto Renewal</label>
                              <input type="text" class="form-control" id="auto_renewal" name="auto_renewal" value="{{old('auto_renewal')??$subscription->auto_renewal}}" placeholder="Enter auto renewal" required>
                        </div>
                      
                      <div class="form-group">
                        <div class="form-check">
                        @if(!empty($subscription->one_month_free_trial))
                          <input class="form-check-input" type="checkbox" name="one_month_free_trial" value="1" checked>
                        @else
                          <input class="form-check-input" type="checkbox" name="one_month_free_trial" value="1">
                        @endif

                          <label class="form-check-label">One Month Free Trial</label>
                        </div>
                     </div>



            @if($subscription->plan_for == "Single Plan" )
                  <div class="form-group">
                     <label for="auto_renewal">Plan Type</label>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="plan_for" value="Single Plan" checked>
                          <label class="form-check-label">Single Plan</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="plan_for" value="Family Plan">
                          <label class="form-check-label">Family Plan</label>
                        </div>
                      </div>
                  @endif




                @if($subscription->plan_for == "Family Plan" )
                     <div class="form-group">
                     <label for="auto_renewal">Plan Type</label>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="plan_for" value="Single Plan">
                        
                          <label class="form-check-label">Single Plan</label>
                        </div>
                        <div class="form-check">
                       
                          <input class="form-check-input" type="radio" name="plan_for" value="Family Plan" checked>
                       
                          <label class="form-check-label">Family Plan</label>
                        </div>
                      </div>
                @endif

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