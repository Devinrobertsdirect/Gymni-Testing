@extends('admin.layout')
@section('content')
<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Add User</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('users.index')}}">Users</a></li>
                  <li class="breadcrumb-item active">Add Users</li>
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
                     <h3 class="card-title">Add User</h3>
                  </div>
                  <div class="card-body">
                     <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                           <div class="form-group">
                              <label for="name">Name</label>
                              <input type="name" class="form-control" id="name" name="name" value="{{old('name')}}" placeholder="Enter name" required>
                           </div>

                           <div class="form-group">
                             <label for="exampleInputEmail1">Email</label>
                             <input type="email" class="form-control" id="exampleInputEmail1" value="{{old('email')}}" placeholder="Enter email" name="email" required>
                           </div>

                           <div class="form-group">
                             <label for="exampleInputPhone">Phone</label>
                             <input type="phone" class="form-control" max="12" id="exampleInputPhone" value="{{old('phone')}}" placeholder="Enter phone" name="phone" required>
                           </div>

                           <div class="form-group">
                              <label for="exampleInputPassword1">Password</label>
                              <input type="password" class="form-control" id="exampleInputPassword1" name="password" value="{{old('password')}}" placeholder="Password" required>
                           </div>

                          <div class="form-group">
                           <label>Date Of Birth:</label>
                             <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                 <input  value="{{old('dob')}}" type="text" name="dob" class="form-control datetimepicker-input" data-target="#reservationdate" required/>
                                 <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                     <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                 </div>
                             </div>
                         </div>

                         <div class="form-group">
                           <label for="exampleSelectRounded0">Gender</label>
                           <select name="gender" class="custom-select rounded-0" id="exampleSelectRounded0" required>
                             <option>Select Gender</option>
                             <option value="Male">Male</option>
                             <option value="Female">Female</option>
                           </select>
                         </div>

                         <div class="form-group">
                              <label for="weight">Weight</label>
                              <input type="text" class="form-control" id="weight" name="weight" value="{{old('weight')}}" placeholder="Enter Weight" required>
                        </div>

                         <div class="form-group">
                           <label for="gols">Gols</label>
                           <select name="gols" class="custom-select rounded-0" id="gols" required>
                             <option>Select Gols</option>
                             <option value="Daily">Daily</option>
                             <option value="Weekly">Weekly</option>
                             <option value="Monthly">Monthly</option>
                           </select>
                         </div>


                        <div class="form-group">
                              <label for="profile_bio">Profile Bio</label>
                              <textarea class="form-control" id="profile_bio" name="profile_bio" rows="4" cols="50" required>{{old('profile_bio')}}</textarea>
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