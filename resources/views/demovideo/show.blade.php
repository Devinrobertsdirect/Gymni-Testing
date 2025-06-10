@extends('admin.layout')
@section('content')
<style>
    img.img-fluid {
    width: 300px;
    height: 300px;
     display: block;
    margin: 0 auto;
}
</style>
@php 
  use App\Models\User;
@endphp
<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>View Group</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('groups.index')}}">Group</a></li>
                  <li class="breadcrumb-item active">View Group</li>
               </ol>
            </div>
         </div>
      </div>
    
   </section>
  
    
     <section class="content">
       <div class="card">
        <div class="card-header color-me">
          <h3 class="card-title">View group</h3>
        </div>
          
          <div class="card-body">
               <div class="row">
                     @if(!empty($group->image))
                        <div class="col-12">
                          <!--  <label>{{__('Image:')}}</label> -->
                           <span><img src="{{ asset('./public/group/'.$group->image) }}" class="img-fluid center" alt="group_image"></span>
                        </div>
                     @endif

                     @if(!empty($group->group_name ))
                        <div class="col-12">
                           <label>{{__('Group Name:')}}</label>
                           <span>{{$group->group_name }}</span>
                        </div>
                     @endif

                      @if(!empty($group->members ))
                        <div class="col-12">
                           <label>{{__('Members:')}}</label>
                           <span>
                               @php
                                    if(!empty($group->members)){
                                       $members = explode(',' , $group->members);
                                         foreach($members as $m){
                                             $users = User::select('name')->where('id', $m)->get()->first();
                                             $uNames[] = $users['name'];
                                         }
                                         
                                        

                                         echo $names = implode(', ' , $uNames);
                                    }
                               @endphp
                           </span>
                        </div>
                     @endif  

                      @if(!empty($group->created_by ))
                        <div class="col-12">
                           <label>{{__('Created By:')}}</label>
                           <span>
                               @php
                                 if(!empty($group->created_by)){
                                    $users = User::select('name')->where('id', $group->created_by)->get()->first();
                                    echo $users['name'];
                                 }
                               @endphp
                           </span>
                        </div>
                     @endif   


                     @if(!empty($group->group_description ))
                        <div class="col-12">
                           <label>{{__('Description:')}}</label>
                           <span><?php echo  $description = str_replace(["\r\n", "\r", "\n"], "<br/>", $group->group_description)  ?> </span>
                        </div>
                     @endif   


               </div>
          </div>       
     </div>
    </section>

</div>

@endsection