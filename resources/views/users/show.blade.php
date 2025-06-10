@extends('admin.layout')
@section('content')
@php
use App\Models\Subscription;
@endphp


<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>View User</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('users.index')}}">Users</a></li>
                  <li class="breadcrumb-item active">View User</li>
               </ol>
            </div>
         </div>
      </div>
    
   </section>
  
    
     <section class="content">
       <div class="card">
        <div class="card-header color-me">
          <h3 class="card-title">View User</h3>
        </div>

          <div class="card-body">
               <div class="row">
                     @if(!empty($user->name))
                        <div class="col-4">
                           <label>{{__('Name:')}}</label>
                           <span>{{$user->name}}</span>
                        </div>
                     @endif

                     @if(!empty($user->email ))
                        <div class="col-4">
                           <label>{{__('Email:')}}</label>
                           <span>{{$user->email }}</span>
                        </div>
                     @endif

                     @if(!empty($user->phone ))
                        <div class="col-4">
                           <label>{{__('Phone:')}}</label>
                           <span>{{$user->phone }}</span>
                        </div>
                     @endif

                     @if(!empty($user->subs_plan ))
                        <div class="col-4">
                           <label>{{__('Subscription Plan:')}}</label>
                           <span>
                              <?php $s = Subscription::where('id',$user->subs_plan)->first();
                              echo $s->title; ?>
                           </span>
                        </div>
                     @endif

                     @if(!empty($user->subs_plan_start ))
                        <div class="col-4">
                           <label>{{__('Subscription Plan Start Date:')}}</label>
                           <span>{{ date("m-d-Y", strtotime($user->subs_plan_start));  }}</span>
                        </div>
                     @endif

                     @if(!empty($user->subs_plan_end ))
                        <div class="col-4">
                           <label>{{__('Subscription Plan End Date:')}}</label>
                           <span>{{ date("m-d-Y", strtotime($user->subs_plan_end));  }}</span>
                        </div>
                     @endif


                     @if(!empty($user->dob ))
                        <div class="col-4">
                           <label>{{__('DOB:')}}</label>
                           <span>{{$user->dob }}</span>
                        </div>
                     @endif

                     @if(!empty($user->gender ))
                        <div class="col-4">
                           <label>{{__('Gender:')}}</label>
                           <span>{{$user->gender }}</span>
                        </div>
                     @endif

                      @if(!empty($user->weight ))
                        <div class="col-4">
                           <label>{{__('Weight:')}}</label>
                           <span>{{$user->weight }}</span>
                        </div>
                     @endif

                     @if(!empty($user->gols ))
                        <div class="col-4">
                           <label>{{__('Gols:')}}</label>
                           <span>{{$user->gols }}</span>
                        </div>
                     @endif

                     @if(!empty($user->profile_bio ))
                        <div class="col-12">
                           <label>{{__('Profile Bio:')}}</label>
                           <span>{{$user->profile_bio }}</span>
                        </div>
                     @endif

                      @if(!empty($user->created_at ))
                        <div class="col-12">
                           <label>{{__('Created At:')}}</label>
                           <span>{{ date("m-d-Y", strtotime($user->created_at));  }} <br> {{ date('h:i:s a', strtotime($user->created_at)); }} </span>
                        </div>
                     @endif

                   
               </div>
          </div>       
     </div>
    </section>

</div>

@endsection