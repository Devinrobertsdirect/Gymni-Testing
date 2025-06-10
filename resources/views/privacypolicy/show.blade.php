@extends('admin.layout')
@section('content')
<style>
   img.img-fluid {
    height: 100px;
    width: 100px;
}
</style>

<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>View Privacy Policy</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('privacypolicy.index')}}">Privacy Policy</a></li>
                  <li class="breadcrumb-item active">View Privacy Policy</li>
               </ol>
            </div>
         </div>
      </div>
    
   </section>
  
    
     <section class="content">
       <div class="card">
        <div class="card-header color-me">
          <h3 class="card-title">View Privacy Policy</h3>
        </div>
          
          <div class="card-body">
               <div class="row">
                     @if(!empty($privacypolicyMulti->image))
                        <div class="col-4">
                           <label>{{__('Image:')}}</label>
                           <span><img src="{{ asset('./public/PrivacyPolicyImage/'.$privacypolicyMulti->image) }}" class="img-fluid" alt="about_image"></span>
                        </div>
                     @endif

                     @if(!empty($privacypolicyMulti->title ))
                        <div class="col-12">
                           <label>{{__('Title:')}}</label>
                           <span>{{$privacypolicyMulti->title }}</span>
                        </div>
                     @endif

                     @if(!empty($privacypolicyMulti->content ))
                        <div class="col-12">
                           <label>{{__('Content:')}}</label>
                           <span><?php echo  $description = str_replace(["\r\n", "\r", "\n"], "<br/>", $privacypolicyMulti->content)  ?> </span>
                        </div>
                     @endif           
               </div>
          </div>       
     </div>
    </section>

</div>

@endsection