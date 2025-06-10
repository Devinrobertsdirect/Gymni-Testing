@extends('admin.layout')
@section('content')
<style>
  img.img-fluid {
    width: 100px;
    height: 100px;
    object-fit: cover;
}
.card-header {
    background: white!important;
}
div#example1_filter {
    padding-top: 14px;
}
</style>
<style>
  /* #example {
    width: 100%;
    table-layout: fixed;
    white-space: nowrap;
  }

  #example th, 
  #example td {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .table-responsive {
    overflow-x: auto;
    white-space: nowrap;
  } */
</style>

<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Report Management</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item active">Report Management</li>
               </ol>
            </div>
         </div>
      </div>
   </section>

   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-12">
               
          <div class="table-responsive">
            <table id="example" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S. No</th>
                    <th>Report By</th>
                    <th>Report By Email</th>
                    <th>Reported username</th>
                    <th>Reported Email</th>
                    <th>Content</th>
                    <th>Img/Video</th>
                    <th>Post Date</th>
                    <th>Reported Date</th>
                  </tr>
                  </thead>
                  <tbody>

               
                 @foreach($user_feedback_arr as $index => $feedback)
                  
                  <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $feedback->username  }}</td>
                    <td>{{ $feedback->email  }}</td>
                    <td>{{ $feedback->post_creator_name  }}</td>
                    <td>{{ $feedback->post_creator_email  }}</td>
                    <td>{{ $feedback->comments  }}</td>
                    <td>
                     @if(!empty($feedback->post_image))
                        @php
                           $fileExtension = pathinfo($feedback->post_image, PATHINFO_EXTENSION);
                           $videoExtensions = ['mp4', 'webm', 'ogg'];
                           $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
                        @endphp
                        @if(in_array($fileExtension, $imageExtensions))
                           <img src="{{ asset('public/'.$feedback->post_image) }}" class="img-fluid" alt="post_image" style="max-width: 100px; height: auto;">
                        @elseif(in_array($fileExtension, $videoExtensions))
                        
                        <div class="video-thumbnail" onclick="openVideoModal('{{ asset('public/' . $feedback->post_image) }}')">
                           <i class="fas fa-eye" style="color:#13acb4;"></i>
                        </div>
                        @endif
                     @endif
                    </td>
                    
                    <td>{{ date('m-d-Y', strtotime($feedback->post_created_at)) }}</td>
                    <td>{{ date('m-d-Y', strtotime($feedback->created_at)) }}</td>
                    
                    
                  </tr>

                  @endforeach
                 </tbody> 
                </table>

             



               </div>
            </div>
            <div class="col-md-2"></div>
         </div>
      </div>
      <!-- Video Modal -->
      <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="videoModalLabel">Video Preview</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <div class="modal-body text-center">
                     <video id="modalVideo" width="100%" controls>
                        <source src="" type="video/mp4">
                        Your browser does not support the video tag.
                     </video>
                  </div>
            </div>
         </div>
      </div>
      
      <style>
         input[type=search] {
            height: calc(2.2rem + 2px);
    padding: 0.25rem 0.7rem;
    font-size: .875rem;
    font-weight: 400;
    line-height: 1.5;
    color: #3d4246;
    background-color: #f5f5f5;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.2rem;
}
select[name="example_length"] {
   height: calc(2.2rem + 2px);
    padding: 0.25rem 0.7rem;
    font-size: .875rem;
    font-weight: 400;
    line-height: 1.5;
    color: #3d4246;
    background-color: #f5f5f5;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.2rem;
}
.paging_simple_numbers>a, .paging_simple_numbers>span a {
    background: #dfdfdf;
    display: inline-block;
    min-width: 40px;
    text-align: center;
    
    padding: 10px;
    margin: 0 2px !important;
}
.dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate {
    display: inline-block;
}
.dataTables_filter, .dataTables_paginate {
   float: right;
}
.dataTables_wrapper {
   margin-top: 6px;
}
.paging_simple_numbers{
   padding: 6px;
}
.dataTables_info{
   padding: 6px;
}
.dataTables_wrapper {
   padding: 7px;
}


    /* Restrict video size in modal */
    #modalVideo {
        max-width: 100%;
        max-height: 400px; /* Default max height */
    }

    /* Adjust for small screens (mobile) */
    @media (max-width: 768px) {
        #modalVideo {
            max-height: 250px; /* Smaller height on mobile */
        }
    }

  
   
      </style>
     <script src="https://code.jquery.com/jquery-3.5.1.js"></script>  
     <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
     <script>
      function confirmDelete() {
         return confirm("Are you sure you want to delete this feedback?");
      }

      function openVideoModal(videoSrc) {
         $("#modalVideo source").attr("src", videoSrc);
         $("#modalVideo")[0].load(); // Reload video
         $("#videoModal").modal("show");
      }

      // Stop video when modal is closed
      $("#videoModal").on("hidden.bs.modal", function () {
         $("#modalVideo")[0].pause();
      });
     </script>
 <script>
  $(document).ready(function(){
    $("#example").DataTable({
      "order": [[0, "desc"]] // First column (index 0) in descending order
    });
  });
</script>
   </section>
</div>
@endsection