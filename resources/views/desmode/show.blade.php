@extends('admin.layout')

@section('content')

<style>
   .img-fluidrounded {
      overflow: hidden;
   }

   .container1 {
      padding: 10px;
      border: 1px solid #d9e1e1;
      background-color: #ebf1f1;
   }

   img {
      transition: transform 0.25s ease;
   }

   img:hover {
      -webkit-transform: scale(1.5);
      transform: scale(1.5);
   }

   #myModal {
      width: 40%;
      /*display: block;*/
      left: 50%;
      top: 60%;
      transform: translate(-50%, -50%);
   }

   .modal-body iframe{
      width: 100% !important;
   }

   .description {
      display: flex;
      gap: 4px;
      align-items: center;
   }
</style>

<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>View Description Mode</h1>
            </div>

            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('desmode.index')}}">Description Mode</a></li>
                  <li class="breadcrumb-item active">View Description Mode</li>
               </ol>
            </div>

         </div>
      </div>
   </section>

   <section class="content">
      <div class="card">
         <div class="card-header color-me">
            <h3 class="card-title">View Description Mode</h3>
         </div>

         <div class="card-body">
            <div class="row">
               @if(!empty($desmode->img_title))
               <div class="col-4">
                  <label>{{__('Title:')}}</label>
                  <span>{{$desmode->img_title}}</span>
               </div>
               @endif

               @if(!empty($desmode->category))
               <div class="col-4">
                  <label>{{__('Category:')}}</label>
                  <span>{{$desmode->category}}</span>
               </div>
               @endif

               @if(!empty($desmode->duration))
               <div class="col-4">
                  <label>{{__('Duration:')}}</label>
                  <span>{{$desmode->duration}}</span>
               </div>
               @endif

               @if(!empty($desmode->muscle_group))
               <div class="col-4">
                  <label>{{__('Muscle Group:')}}</label>
                  <span>{{$desmode->muscle_group}}</span>
               </div>
               @endif

               @if(!empty($desmode->equipment))
               <div class="col-4">
                  <label>{{__('Equipment:')}}</label>
                  <span>{{$desmode->equipment}}</span>
               </div>
               @endif

               @if(!empty($desmode->rating))
               <div class="col-4">
                  <label>{{__('Rating:')}}</label>
                  <span>{{$desmode->rating}}</span>
               </div>
               @endif

               @if(!empty($desmode->intensity))
               <div class="col-4">
                  <label>{{__('Intensity:')}}</label>
                  <span>{{$desmode->intensity}}</span>
               </div>
               @endif

               @if(!empty($desmode->instructor))
               <div class="col-4">
                  <label>{{__('Instructor:')}}</label>
                  <span>{{$desmode->instructor}}</span>
               </div>
               @endif

               @if(!empty($desmode->demo_video))
               <div class="col-4">
                  <label>{{__('Demo Video:')}}</label>
                  <span id="myBtn" style="color:blue;cursor: pointer;">
                     Click here
                  </span>
               </div>
               @endif

               @if(!empty($desmode->like))
               <div class="col-4">
                  <label>{{__('Like:')}}</label>
                  <span>{{$desmode->like}}</span>
               </div>
               @endif

               @if(!empty($desmode->share))
               <div class="col-4">
                  <label>{{__('Share:')}}</label>
                  <span>{{$desmode->share}}</span>
               </div>
               @endif

               {{-- Need To Remove this section as it is not required --}}
               @if(!empty($desmode->description))
               <div class="col-12">
                  <label>{{__('Description:')}}</label>
                  <span>{!! $desmode->description !!}</span>
               </div>
               @endif
               {{--  --}}

               {{-- This one is Updated --}}
               @if(!empty($exerciseDescription))
                  @php
                     $exercises = json_decode($exerciseDescription, true);
                     $groupedExercises = [];
                     foreach ($exercises as $exercise) {
                           $groupedExercises[$exercise['exercise_title']][] = $exercise;
                     }
                  @endphp

                  <div class="mt-3 description">
                     <label for="">Description:</label>
                     @foreach ($groupedExercises as $title => $exercises)
                           <div class="card my-3">
                              <div class="card-header bg-primary text-white">
                                 <h5 class="mb-0">{{ $title }}</h5>
                              </div>
                              <div class="card-body">
                                 <div class="d-flex flex-wrap gap-3">
                                       @foreach ($exercises as $exercise)
                                          <div class="p-2 border rounded bg-light">
                                             <strong>{{ $exercise['exercise_name'] }}</strong> - 
                                             Sets: {{ $exercise['sets'] }}, 
                                             Reps: {{ $exercise['reps'] }}, 
                                             Weight: {{ $exercise['weight'] }}, 
                                             RPE: {{ $exercise['rpe'] }}
                                          </div>
                                       @endforeach
                                 </div>
                              </div>
                           </div>
                     @endforeach
                  </div>
               @endif
               {{-- This one is Updated --}}

               <div class=""><b> Demo Video:</b></div>
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

                     <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal_<?php print_r($datas->id); ?>"> <?php print_r($datas->tag) ?><?php echo $comma; ?></a>
                     <div class="modal fade" id="myModal_<?php print_r($datas->id); ?>" role="dialog">
                        <div class="modal-dialog">
                           <!-- Modal content-->
                           <div class="modal-content">
                              <div class="modal-header">
                                 <h4 class="modal-title">Demo Video</h4>
                                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>
                              <div class="modal-body">
                                 <video controls=""  name="media" style="width: 470px !important"><source src="{{$datas->url}}" type="video/mp4" ></video>
                              </div>
                           </div>
                        </div>
                     </div>
                  <?php } ?>
               </div>

               <?php
               if (!empty($desmode->round_description)) {
                  $round_description = json_decode($desmode->round_description);
               } ?>

               @if(!empty($round_description))
               @php $k =1; @endphp
               @foreach($round_description as $rkey => $rval )
               <div class="col-12 container1">
                  <label>
                     <h1>Round {{$k}}</h1>
                  </label><br>
                  <label>Round Description:</label>
                  <span>{{ $rval->description }}</span>

                  @if(!empty($rval->images))
                  <div class="row">
                     @foreach($rval->images as $img)
                     <div class="col-2">
                        <div class="img-fluidrounded">
                           <img src="{{ asset('./public/images/'.$img) }}" class="img-fluid" alt="description_mode">
                        </div>
                     </div>
                     @endforeach
                  </div>
                  @endif
               </div>

               @php $k++; @endphp
               @endforeach
               @endif

               <!-- The Modal -->
               <div id="myModal" class="modal">
                  <!-- Modal content -->
                  <div class="modal-content">
                     <span class="close" style="text-align: right;    padding-right: 7px;cursor: pointer;">&nbsp;&nbsp;&nbsp;&times;</span>
                     <p style="text-align: center;">
                        <video width="500" controls>
                           <source src="{{ asset('./public/demo_video/'.$desmode->demo_video) }}" type="video/mp4">
                        </video>
                     </p>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>

<script>
   // Get the modal
   var modal = document.getElementById("myModal");

   // Get the button that opens the modal
   var btn = document.getElementById("myBtn");

   // Get the <span> element that closes the modal
   var span = document.getElementsByClassName("close")[0];

   // When the user clicks on the button, open the modal
   btn.onclick = function() {
      modal.style.display = "block";
   }

   // When the user clicks on <span> (x), close the modal
   span.onclick = function() {
      modal.style.display = "none";
   }

   // When the user clicks anywhere outside of the modal, close it
   window.onclick = function(event) {
      if (event.target == modal) {
         modal.style.display = "none";
      }
   }
</script>

@endsection