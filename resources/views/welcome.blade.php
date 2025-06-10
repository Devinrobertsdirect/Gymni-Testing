@extends('admin.layout')
@section('content')
@php
 use App\Models\User;
 use App\Models\VideoMode;
 use App\Models\Subscription;
@endphp
<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Dashboard</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Dashbord</li>
               </ol>
            </div>
         </div>
      </div>
      <!-- /.container-fluid -->
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-12">
               @if ($message = Session::get('success'))
               <div class="alert alert-success">
                  <p>{{ $message }}</p>
               </div>
               @endif
               <section class="content">
                  <div class="container-fluid">
                     <!-- Small boxes (Stat box) -->
                     <div class="row">
                        <div class="col-lg-3 col-6">
                           <!-- small box -->
                           <div class="small-box bg-warning">
                              <div class="inner">
                                 <h3>
                                    <?php 
                                        $countUser = User::where('role', 0)->count();
                                        if($countUser > 0){
                                           echo $countUser;
                                        }

                                    ?>
                                    
                                 </h3>
                                 <p>Total Users</p>
                              </div>
                              <div class="icon">
                                 <i class="ion ion-person-add"></i>
                              </div>
                              <a href="{{ route('users.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                           </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                           <!-- small box -->
                           <div class="small-box bg-success">
                              <div class="inner">
                                 <h3>
                                     <?php 
                                        $countVideo = VideoMode::count();
                                        if($countVideo > 0){
                                           echo $countVideo;
                                        }

                                    ?>
                                 </h3>
                                 <p>Total Videos</p>
                              </div>
                              <div class="icon">
                                 <i class="ion ion-stats-bars"></i>
                              </div>
                              <a href="{{ route('videomode.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                           </div>
                        </div>
                        <div class="col-lg-3 col-6">
                           <div class="small-box bg-info">
                              <div class="inner">
                                 <h3>
                              
                                    <?php 
                                    $total_like = DB::table('like_video_mode')
                                    ->join('video_mode', 'video_mode.id', '=', 'like_video_mode.video_mode_id')
                                    ->select(DB::raw('count(*) as total_like'))
                                    ->groupBy('video_mode_id')
                                    ->get();
                                    $array1 = array();
                                    foreach ($total_like as $row1) {
                                      $array1[] = array(
                                        'total_like' => $row1->total_like
                                      );
                                    }
                                    $numerical = array();
                                    $sep = ':';
                                    foreach ($array1 as $k => $v) {
                                      $numerical[] = $v['total_like'];
                                    }
                                   // print_r($numerical);

                                  
                                   if(!empty($numerical)){
                                    print_r(max($numerical));
                                   } else{
                                      echo 0;
                                   }
                                       //  $countVideo = VideoMode::where('like', '<>', '')->count();
                                       //  if($countVideo > 0){
                                       //     echo $countVideo;
                                       //  }

                                    ?>
                                 </h3>
                                 <p>Top Highest Liked Video</p>
                              </div>
                              <div class="icon">
                                 <i class="ion ion-bag"></i>
                              </div>
                              <a href="{{ route('videomode.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                           </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                           <!-- small box -->
                           <div class="small-box bg-danger">
                              <div class="inner">
                                 <h3>
                                     <?php 

                                    $countUser =  User::select('subs_plan', DB::raw('COUNT(subs_plan) AS occurrences'))
                                                      ->where('subs_plan', '<>', null)
                                                      ->groupBy('subs_plan')
                                                      ->orderBy('occurrences', 'DESC')
                                                      ->limit(5)
                                                      ->get()->all();
                                                      echo count($countUser);  

                                    ?>
                                    
                                 </h3>
                                 <p>Most Popular Subscription Plan</p>
                              </div>
                              <div class="icon">
                                 <i class="ion ion-pie-graph"></i>
                              </div>
                              <a href="{{ route('topplans')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                           </div>
                        </div>
                        <!-- ./col -->
                     </div>
                  </div>
               </section>
            </div>
         </div>
      </div>
   </section>
</div>
@endsection