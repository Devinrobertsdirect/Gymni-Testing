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
               <h1>View About Us</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('aboutus.index')}}">About Us</a></li>
                  <li class="breadcrumb-item active">View About Us</li>
               </ol>
            </div>
         </div>
      </div>
    
   </section>
  
    
     <section class="content">
       <div class="card">
        <div class="card-header color-me">
          <h3 class="card-title">View About Us</h3>
        </div>
          
          <!--  <div class="card-body">
               <div class="row">
                     @if(!empty($aboutus->Title))
                        <div class="col-4">
                           <label>{{__('Title:')}}</label>
                           <span>{{$aboutus->Title}}</span>
                        </div>
                     @endif

                     @if(!empty($aboutus->content ))
                        <div class="col-4">
                           <label>{{__('Content:')}}</label>
                           <span>{{$aboutus->content }}</span>
                        </div>
                     @endif

                     @if(!empty($aboutusmulti->content ))
                        <div class="col-4">
                           <label>{{__('Content:')}}</label>
                           <span>{{$aboutusmulti->content }}</span>
                        </div>
                     @endif           
               </div>
          </div>  
 -->


          <div class="card-body">
               <div class="row">
                     @if(!empty($aboutusmulti->image))
                        <div class="col-4">
                           <label>{{__('Image:')}}</label>
                           <span><img src="{{ asset('./public/aboutImage/'.$aboutusmulti->image) }}" class="img-fluid" alt="about_image"></span>
                        </div>
                     @endif

                     @if(!empty($aboutusmulti->title ))
                        <div class="col-12">
                           <label>{{__('Title:')}}</label>
                           <span>{{$aboutusmulti->title }}</span>
                        </div>
                     @endif
                      
                     @if(!empty($aboutusmulti->content ))
                        <div class="col-12">
                           <label>{{__('Content:')}}</label>
                           <span><?php echo  $description = str_replace(["\r\n", "\r", "\n"], "<br/>", $aboutusmulti->content)  ?> </span>
                        </div>
                     @endif  
               </div>
          </div>       
     </div>
    </section>

</div>

@endsection