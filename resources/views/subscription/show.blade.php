@extends('admin.layout')
@section('content')
<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>View Subscription</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('subscription.index')}}">Subscription</a></li>
                  <li class="breadcrumb-item active">View Subscription</li>
               </ol>
            </div>
         </div>
      </div>
    
   </section>
  
    
     <section class="content">
       <div class="card">
        <div class="card-header color-me">
          <h3 class="card-title">View Subscription</h3>
        </div>

          <div class="card-body">
               <div class="row">
                     @if(!empty($subscription->title))
                        <div class="col-12">
                           <label>{{__('Title:')}}</label>
                           <span>{{$subscription->title}}</span>
                        </div>
                     @endif

                     @if(!empty($subscription->text ))
                        <div class="col-12">
                           <label>{{__('Description:')}}</label>
                           <span>{!! $subscription->text !!}  </span>
                        </div>
                     @endif

                     @if(!empty($subscription->price ))
                        <div class="col-4">
                           <label>{{__('Price:')}}</label>
                           <span>{{$subscription->price }}</span>
                        </div>
                     @endif

                     @if(!empty($subscription->discount ))
                        <div class="col-4">
                           <label>{{__('Discount (%):')}}</label>
                           <span>{{$subscription->discount }}</span>
                        </div>
                     @endif

                     @if(!empty($subscription->plan_for ))
                        <div class="col-4">
                           <label>Plan Type:</label>
                           <span>{{$subscription->plan_for }}</span>
                        </div>
                     @endif


                     @if(!empty($subscription->device_at_a_time ))
                        <div class="col-4">
                           <label>{{__('Device At a Time:')}}</label>
                           <span>{{$subscription->device_at_a_time }}</span>
                        </div>
                     @endif

                     @if(!empty($subscription->per_member ))
                        <div class="col-4">
                           <label>{{__('Per Member:')}}</label>
                           <span>{{$subscription->per_member }}</span>
                        </div>
                     @endif

                      @if(!empty($subscription->auto_renewal ))
                        <div class="col-4">
                           <label>{{__('Auto Renewal:')}}</label>
                           <span>{{$subscription->auto_renewal }}</span>
                        </div>
                     @endif

                      @if(!empty($subscription->discount_codes ))
                        <div class="col-4">
                           <label>{{__('Discount Codes:')}}</label>
                           <span>{{$subscription->discount_codes }}</span>
                        </div>
                     @endif

                     @if(!empty($subscription->one_month_free_trial ))
                        <div class="col-4">
                           <label>{{__('One Month Free Trial:')}}</label>
                           <span>Yes</span>
                        </div>
                     @else
                     <div class="col-4">
                           <label>{{__('One Month Free Trial:')}}</label>
                           <span>No</span>
                        </div>
                     @endif

               </div>
          </div>       
     </div>
    </section>

</div>

@endsection