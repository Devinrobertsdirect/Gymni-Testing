@extends('admin.layout')
@section('content')
<style>
   img.img-fluid {
    height: 100px;
    width: 100px;
}
.modal-body iframe{
      width: 100% !important;
   }

</style>

<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>View Video Mode</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('videomode.index')}}">Video Mode</a></li>
                  <li class="breadcrumb-item active">View Video Mode</li>
               </ol>
            </div>
         </div>
      </div>
    
   </section>
  
    
     <section class="content">
       <div class="card">
        <div class="card-header color-me">
          <h3 class="card-title">View Video Mode</h3>
        </div>

          <div class="card-body">
               <div class="row">
              
                     @if(!empty($mode->video_path))
                        <div class="col-12" style="padding-bottom: 30px;">
                          <center>
                           <span>
                                <video width="300" height="200" controls>
                                   <source src="{{ asset('./public/videos/'.$mode->video_path) }}" type="video/mp4">
                                </video>
                           </span>
                           </center>
                        </div>
                     @endif

                      @if(!empty($mode->video_title ))
                        <div class="col-12">
                           <label>{{__('Video Title:')}}</label>
                           <span>{{$mode->video_title }}</span>
                        </div>
                     @endif
                   
                      
                     @if(!empty($mode->description ))
                        <div class="col-12">
                           <label>{{__('Description:')}}</label>
                           <span>{!! $mode->description !!}</span>
                        </div>
                     @endif

                     @if(!empty($mode->category ))
                        <div class="col-4">
                           <label>{{__('Category:')}}</label>
                           <span>{!! $mode->category !!}</span>
                        </div>
                     @endif

                     @if(!empty($mode->duration ))
                        <div class="col-4">
                           <label>{{__('Duration:')}}</label>
                           <span>{!! $mode->duration !!}</span>
                        </div>
                     @endif

                     @if(!empty($mode->muscle_group ))
                        <div class="col-4">
                           <label>{{__('Muscle Group:')}}</label>
                           <span>{!! $mode->muscle_group !!}</span>
                        </div>
                     @endif

                     @if(!empty($mode->equipment ))
                        <div class="col-4">
                           <label>{{__('Equipment:')}}</label>
                           <span>{!! $mode->equipment !!}</span>
                        </div>
                     @endif

                     @if(!empty($mode->rating ))
                        <div class="col-4">
                           <label>{{__('Rating:')}}</label>
                           <span>{!! $mode->rating !!}</span>
                        </div>
                     @endif

                     @if(!empty($mode->intensity ))
                        <div class="col-4">
                           <label>{{__('Intensity:')}}</label>
                           <span>{!! $mode->intensity !!}</span>
                        </div>
                     @endif

                     @if(!empty($mode->instructor ))
                        <div class="col-4">
                           <label>{{__('Instructor:')}}</label>
                           <span>{!! $mode->instructor !!}</span>
                        </div>
                     @endif

                     @if(!empty($mode->like ))
                        <div class="col-4">
                           <label>{{__('Like:')}}</label>
                           <span>{!! $mode->like !!}</span>
                        </div>
                     @endif

                     @if(!empty($mode->share ))
                        <div class="col-4">
                           <label>{{__('Share:')}}</label>
                           <span>{!! $mode->share !!}</span>
                        </div>
                     @endif
                     
                     

               </div>
             
               <div class="row">
                 
                        <div class="col-3"><b> Demo Video</b></div>
                     <div class="col-3">
                     <?php 
                  $count = count($data) - 1;
                  $i = 0;
                  foreach ($data as $datas) {
                     $i++;
                     if ($i > $count) {
                        $comma ='';
                     } else {
                        $comma =',';
                     } ?>
                       <a href="javascript:void(0)"  data-toggle="modal" data-target="#myModal_<?php print_r($datas->id); ?>">  <?php print_r($datas->tag) ?><?php echo $comma; ?></a>
                       <div class="modal fade" id="myModal_<?php print_r($datas->id); ?>" role="dialog">
            <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title">Demo Video</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          
            </div>
            <div class="modal-body">
            <video controls="" autoplay="" name="media" style="width: 470px !important"><source src="{{$datas->url}}" type="video/mp4" ></video>
           
            </div>
            
            </div>

            </div>
            </div>
                       <?php } ?>
                     </div>
                    
                     </div>
            
                   
          </div>       
     </div>
     <!----------------model------------------>
   
  
</div>
    </section>
    
    <!----------------------------------------->

</div>


@endsection