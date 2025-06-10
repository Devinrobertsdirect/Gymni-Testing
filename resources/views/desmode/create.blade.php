@extends('admin.layout')

@section('content')

<style>
    .modal-body iframe {

        width: 100% !important;

    }

    button.btn-add-exercise-new {
        background: #13acb4;
        border: none;
        padding: 5px 10px 5px 8px;
        border-radius: 5px;
        margin-top: 34px;
        height: fit-content;
    }

    input.form-control.description-input-new {
        display: block;
        width: 60%;
        height: calc(2.25rem + 2px);
        padding: .375rem .75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: .25rem;
        box-shadow: inset 0 0 0 transparent;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }

    input::placeholder {
        font-size: 14px; /* or 16px, adjust as needed */
        color: #aaa;     /* optional: make it more visible */
    }

    
</style>

<div class="content-wrapper">

    <section class="content-header">

        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-6">

                    <h1>Add Description Mode</h1>

                </div>

                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-right">

                        <li class="breadcrumb-item"><a href="#">Home</a></li>

                        <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>

                        <li class="breadcrumb-item"><a href="{{ route('desmode.index')}}">Descriptio Mode</a></li>

                        <li class="breadcrumb-item active">Add Description Images</li>

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

                <div class="col-md-12">

                    <div class="card card-primary my-select">

                        @if ($message = Session::get('success'))

                        <div class="alert alert-success">

                            <p>{{ $message }}</p>

                        </div>

                        @endif

                        @if ($message = Session::get('error'))

                            <div class="alert alert-danger col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                <p>{{ $message }}</p>

                            </div>

                        @endif

                        <div class="card-header color-me">
                            <h3 class="card-title">Add Description Mode</h3>
                        </div>

                        <div class="card-body">

                            <div class="messages"></div>

                            <form enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="img_title">Title</label>
                                        <input type="img_title" class="form-control" id="img_title" name="img_title"
                                            value="{{old('img_title')}}" placeholder="Enter Name" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="img_title">Select Video Mode</label>
                                        <select class="form-control get_id" name="video_mode_lastid">
                                            <option>Select Video Title</option>
                                            @foreach ($getvideo_mode_title as $row)
                                            <option value="{{$row->id}}">{{ $row->video_title }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="text">Description</label>
                                        {{-- <textarea id="summernote" rows="4" cols="50"
                                            name="description">{{old('video_title')}}</textarea> --}}
                                        <section class="content">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="card-body">
                                                            <div class="messagebox"></div>
                                                            <div id="sections-container">
                                                                <!-- Initial Section -->
                                                                <div class="video-section">
                                                                    <div class="row align-description-mode">
                                                                        <div class="left-from">
                                                                            <div class="form-group">
                                                                                <label for="title">Title of the Exercise</label>
                                                                                <div class="category-description-add">
                                                                                    <input maxlength="15" type="text" name="exercise_title[]" class="form-control description-input-new" placeholder="Title of the Exercise" required="">
                                                                                    <button type="button" class="btn-add-section">
                                                                                        <i class="fa fa-plus-circle"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="right-from">
                                                                            <div class="exercise-group" style="display: flex;">
                                                                                <div class="col-lg-3">
                                                                                    <div class="form-group">
                                                                                        <label for="exercise">Exercise Name</label>
                                                                                        <div class="category-description-add">
                                                                                            <input type="text" maxlength="15" name="exercise[]" class="form-control" style="width: 123px;" placeholder="Exercise Name" required>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-lg-2">
                                                                                    <div class="form-group">
                                                                                        <label for="sets">Sets <input type="checkbox" name="sets_toggle[]" class="toggle-input" data-target="#reps-input"></label>
                                                                                        <!-- <span>Visiblity</span> -->
                                                                                        <!-- <select class="form-control toggle-input" name="sets_toggle[]" data-target="#sets-input">
                                                                                            <option value="yes">Yes</option>
                                                                                            <option value="no">No</option>
                                                                                        </select> -->
                                                                                        <input type="text" oninput="this.value = this.value.slice(0, 13)" name="sets[]" class="form-control" placeholder="Sets" required>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-lg-2">
                                                                                    <div class="form-group">
                                                                                        <label for="reps">Reps <input type="checkbox" name="reps_toggle[]" class="toggle-input" data-target="#reps-input"></label>
                                                                                        <!-- <select class="form-control toggle-input" name="reps_toggle[]" data-target="#reps-input">
                                                                                            <option value="yes">Yes</option>
                                                                                            <option value="no">No</option>
                                                                                        </select> -->
                                                                                        <input type="text" oninput="this.value = this.value.slice(0, 13)" name="reps[]" class="form-control" placeholder="Reps" required>
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="col-lg-2">
                                                                                    <div class="form-group">
                                                                                        <label for="weight">Weight <input type="checkbox" class="toggle-input" name="weight_toggle[]" data-target="#reps-input"></label>
                                                                                        <!-- <span>Visiblity</span> -->
                                                                                        <!-- <select class="form-control toggle-input" name="weight_toggle[]" data-target="#weight-input">
                                                                                            <option value="yes">Yes</option>
                                                                                            <option value="no">No</option>
                                                                                        </select> -->
                                                                                        <input type="text" oninput="this.value = this.value.slice(0, 13)"  name="weight[]" class="form-control" placeholder="Weight" required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-2">
                                                                                    <div class="form-group">
                                                                                        <label for="rpe">RPE <input type="checkbox" class="toggle-input" name="rpe_toggle[]" data-target="#reps-input"></label>
                                                                                        <!-- <span>Visiblity</span> -->
                                                                                        <!-- <select class="form-control toggle-input" name="rpe_toggle[]" data-target="#rpe-input">
                                                                                            <option value="yes">Yes</option>
                                                                                            <option value="no">No</option>
                                                                                        </select> -->
                                                                                        <input type="text" oninput="this.value = this.value.slice(0, 13)" name="rpe[]" class="form-control" placeholder="RPE" required>
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <button type="button" class="btn-add-exercise-new"><i class="fa fa-plus-circle"></i>
                                                                                    <!-- Add exercise -->
                                                                                </button>
                                                                                
                                                                            </div>
                                                                        </div>
                                                                        <div>
                                                                            <div class="exercise-group" style="display: flex;">
                                                                                <div class="col-lg-12">
                                                                                    <div class="form-group">
                                                                                        <label for="text">Notes</label>
                                                                                        <textarea class="form-control" rows="1" cols="40" name="notes" placeholder="Notes"></textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </div>

                                    <div class="form-group">
                                        <label for="category">Category</label>
                                        <input type="text" class="form-control" readonly required id="category" value=""
                                            placeholder="" name="category" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="muscle_group">Muscle Group</label>
                                        <select name="muscle_group" class="custom-select rounded-0" id="muscle_group">
                                            <option value="">Select Muscle Group</option>
                                            @foreach ($muscle_group as $skey => $val)
                                            <option class="form-control" {{ (old('muscle_group')==$skey)?'selected':''
                                                }} value="{{ $skey }}">
                                                {{$val}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="equipment">Equipment</label>
                                        <input type="text" class="form-control" id="equipment"
                                            value="{{old('equipment')}}" placeholder="Enter Muscle Group"
                                            name="equipment" maxlength="25">
                                    </div>

                                    <div class="form-group">
                                        <label for="instructor">Instructor</label>
                                        <select name="instructor" class="custom-select rounded-0" id="instructor">
                                            <option value="">Select Instructor</option>
                                            @foreach ($instructor as $skey => $val)
                                            <option class="form-control" {{ (old('instructor')==$skey)?'selected':'' }}
                                                value="{{ $skey }}">{{$val}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="instructor"> Intensity rating</label>
                                        <select name="intensity_rating" class="custom-select rounded-0"
                                            id="intensity_rating">
                                            <option value="">Select Intensity rating</option>
                                            @foreach ($intensityrating as $skey => $val)
                                            <option class="form-control" {{
                                                (old('intensity_rating')==$skey)?'selected':'' }} value="{{ $skey }}">
                                                {{$val}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <?php
                                    $unique_countries = array();
                                    foreach ($get_demo_tag as $result) {
                                        $result_countries = explode(',', $result->tag);
                                        foreach ($result_countries as $country) {
                                            if (!in_array($country, $unique_countries)) {
                                                $unique_countries[] = $country;
                                            }
                                        }
                                    }
                                    ?>

                                    <div class="form-group">
                                        <label>Select Demo Video</label>
                                        <select class="select2 get_val" id="get_val" multiple="multiple"
                                            data-placeholder="Select Demo Video" name="tag[]" style="width: 100%;">
                                            <?php
                                                foreach ($unique_countries as $unique_countriess) {
                                            ?>

                                            <option value="<?php echo $unique_countriess; ?>" class="form-control">
                                                <?php echo $unique_countriess; ?>
                                            </option>

                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="row" id="video">
                                        <table class="table table-bordered" id="someTableID">
                                        </table>
                                    </div>

                                </div>

                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-primary addSub">Submit</button>
                                        </div>
                                    </div>
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

<script>
    var f = 1;
</script>

<style>
    .select2-selection__choice {

        background: #00644de3 !important;

        color: black !important;

    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.5.1/chosen.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.5.1/chosen.jquery.min.js"></script>

// <script>
    // $(document).ready(function(){

//     $('#img_title').on('keyup', function(){

//         var inputText = $(this).val();

//         var cleanedText = inputText.replace(/[^\w\s]/gi, '');

//         $(this).val(cleanedText);

//     });

// });

// 
</script>

<script>
    $(".get_id").chosen();

$(document).on('change', '.get_id', function() {

    var id = $(this).val();

    if (id) {

        $.ajax({

            type: 'GET',

            url: "{{ route('getid.get_cate_video') }}",

            data: {

                id: id

            },

            dataType: "json",

            success: function(res) {

                $('#category').val(res.cat)

                if (res.cat == 'Cardio') {

                    // $('#equipment').removeAttr('required');

                    //$('#muscle_group').removeAttr('required');

                } else {

                    // $('#equipment').prop('required',true);

                    //  $('#muscle_group').prop('required',true);

                }

            }

        });

    } else {



    }

});
</script>

<script>
    $(document).ready(function() {

    $('#category').on('change', function() {

        field = $(this).val();

        if (field == 'Cardio') {

            $('#equipment').removeAttr('required');

            $('#muscle_group').removeAttr('required');

        } else {

            $('#equipment').prop('required', true);

            $('#muscle_group').prop('required', true);

        }

    })

})
</script>

<script>
    $("#someTableID").on('click', '.btnDelete', function() {

    $(this).closest('tr').remove();

});
</script>

<script>
    $(document).ready(function() {

    $.ajaxSetup({

        headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        }

    });



    $('.get_val').on('change', function() {

        var selected = $(".get_val").val();



        if (selected) {

            $.ajax({

                type: 'POST',

                url: "{{ route('videomode.add_video') }}",

                data: {

                    selected: selected

                },

                dataType: "json",

                success: function(data) {

                    $('#someTableID').html(data);

                }

            });

        } else {

            $('#someTableID').html("");

        }

    });

})





//   let checkid = [];



//    $(document).on('change', '.get_id', function() {

//    var selected = $(".get_val").val();



//    console.log(selected); return;

//    if (selected.length > 0) {

//       $.ajax({

//          type: 'POST',

//          url: "{{ route('videomode.add_video') }}",

//          data: {

//             selected: selected,

//             id : checkid,

//          },

//          headers: {

//         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

//       },

//          dataType: "json",

//          success: function(data) {

//             $('#someTableID').html(data);

//          }

//       });

//    } else {

//       $('#someTableID').html('');

//    }

// });





function getCheckval(val) {





    checkid.push(val);



    console.log(val);

    console.log(checkid);



}







$(document).on('change', '.get_id', function() {

    var value = $(this).val();

    if (value) {

        $.ajax({

            url: "{{route('checkVideoMode')}}",

            method: 'POST',

            data: {
                value: value
            },

            dataType: 'json',

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            },

            success: function(resp) {

                if (resp.status == 1) {

                    $('.messages').html('<div class="alert alert-warning"><strong>Info!</strong> ' +
                        resp.message + '.</div>');

                    $(".addSub").prop('disabled', true);

                } else {

                    $(".addSub").prop('disabled', false);

                    $('.messages').html('');

                }

            }



        })



    } else {



    }

})
</script>

<script>
    $(document).ready(function() {
        // Function to add a new section
        $(document).on("click", ".btn-add-section", function() {
            var newSection = `
                <hr>
                <div class="video-section">
                    <div class="row align-description-mode">
                        <div class="left-from">
                            <div class="form-group">
                                <label for="title">Title of the Exercise</label>
                                <div class="category-description-add">
                                    <input maxlength="15" type="text" name="exercise_title[]" class="form-control description-input-new" placeholder="Title of the Exercise" required="">
                                    <button type="button" class="btn-remove-exercise">
                                        <i class="fa fa-minus-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="right-from">
                            <div class="exercise-group" style="display: flex;">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="exercise">Exercise Name</label>
                                        <div class="category-description-add">
                                            <input type="text" maxlength="15" name="exercise[]" class="form-control" style="width: 123px;" placeholder="Exercise Name" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="sets">Sets <input type="checkbox" class="toggle-input" name="sets_toggle[]" data-target="#reps-input"></label>
                                        
                                        <input type="text" oninput="this.value = this.value.slice(0, 13)" name="sets[]" class="form-control" placeholder="Sets" required>
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="reps">Reps <input type="checkbox" class="toggle-input" name="reps_toggle[]" data-target="#reps-input"></label>
                                        
                                        <input type="text" oninput="this.value = this.value.slice(0, 13)" name="reps[]" class="form-control" placeholder="Reps" required>
                                    </div>
                                </div>
                                
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="weight">Weight <input type="checkbox" class="toggle-input" name="weight_toggle[]" data-target="#reps-input"></label>
                                        
                                        <input type="text" oninput="this.value = this.value.slice(0, 13)"  name="weight[]" class="form-control" placeholder="Weight" required>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="rpe">RPE <input type="checkbox" class="toggle-input" name="rpe_toggle[]" data-target="#reps-input"></label>
                                        
                                        <input type="text" oninput="this.value = this.value.slice(0, 13)" name="rpe[]" class="form-control" placeholder="RPE" required>
                                    </div>
                                </div>

                                <button type="button" class="btn-add-exercise-new"><i class="fa fa-plus-circle"></i>
                                    <!-- Add exercise -->
                                </button>
                            </div>
                        </div>
                        <div>
                            <div class="exercise-group" style="display: flex;">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="text">Notes</label>
                                        <textarea class="form-control" rows="1" cols="40"
                                            name="notes" placeholder="Notes"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $("#sections-container").append(newSection);
        });

        // Function to add new exercise fields inside a section
        $(document).on("click", ".btn-add-exercise-new", function() {
            var newExercise = `
                <div class="exercise-group" style="display: flex;">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <div class="category-description-add">
                                <input type="text" maxlength="15" name="exercise[]" style="width: 123px;" class="form-control" placeholder="Exercise Name" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-2">
                        <div class="form-group">
                            <input type="text" oninput="this.value = this.value.slice(0, 13)" name="sets[]" class="form-control" placeholder="Sets" required>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <input type="text" oninput="this.value = this.value.slice(0, 13)" name="reps[]" class="form-control" placeholder="Reps" required>
                        </div>
                    </div>
                    
                    <div class="col-lg-2">
                        <div class="form-group">
                            <input type="text" oninput="this.value = this.value.slice(0, 13)"  name="weight[]" class="form-control" placeholder="Weight" required>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <input type="text" oninput="this.value = this.value.slice(0, 13)" name="rpe[]" class="form-control" placeholder="RPE" required>
                        </div>
                    </div>

                     <button type="button" class="btn-remove-exercise">
                        <i class="fa fa-minus-circle"></i>
                     </button>
                </div>
            `;
            $(this).closest(".right-from").append(newExercise);
        });

        // Function to remove an exercise field
        $(document).on("click", ".btn-remove-exercise", function() {
            $(this).closest(".exercise-group").remove();
        });

        // Function to remove an exercise field
        $(document).on("click", ".btn-remove-exercise", function() {
            $(this).closest(".video-section").remove();
        });
    });
</script>

<script>
    $('.addSub').click(function(e) {
        e.preventDefault();

        let descriptionData = [];

        $('.video-section').each(function() {
            let exerciseTitle = $(this).find('input[name="exercise_title[]"]').val();
            let notes = $(this).find('textarea[name="notes"]').val();
            let exercises = [];

            $(this).find('.exercise-group').each(function() {
                let exerciseName = $(this).find('input[name="exercise[]"]').val();
                let sets = $(this).find('input[name="reps[]"]').val();
                //let setsStatus = $(this).find('select[name="reps_toggle[]"]').val();
                let setsStatus = $(this).find('input[name="reps_toggle[]"]').is(':checked') ? 'yes' : 'no';
                let reps = $(this).find('input[name="sets[]"]').val();
                //let repsStatus = $(this).find('select[name="sets_toggle[]"]').val();
                let repsStatus = $(this).find('input[name="sets_toggle[]"]').is(':checked') ? 'yes' : 'no';

                let weight = $(this).find('input[name="weight[]"]').val();
                //let weightStatus = $(this).find('select[name="weight_toggle[]"]').val();
                let weightStatus = $(this).find('input[name="weight_toggle[]"]').is(':checked') ? 'yes' : 'no';

                let rpe = $(this).find('input[name="rpe[]"]').val();
                //let rpeStatus = $(this).find('select[name="rpe_toggle[]"]').val();
                let rpeStatus = $(this).find('input[name="rpe_toggle[]"]').is(':checked') ? 'yes' : 'no';

                exercises.push({
                    exercise_name: exerciseName,
                    sets: { value: sets, status: setsStatus },
                    reps: { value: reps, status: repsStatus },
                    weight: { value: weight, status: weightStatus },
                    rpe: { value: rpe, status: rpeStatus }
                });
            });

            descriptionData.push({
                exercise_title: exerciseTitle,
                notes: notes,
                exercises: exercises
            });
        });

        let formData = {
            img_title: $('input[name="img_title"]').val(),
            video_mode_lastid: $('select[name="video_mode_lastid"]').val(),
            description: descriptionData,
            category: $('input[name="category"]').val(),
            muscle_group: $('select[name="muscle_group"]').val(),
            equipment: $('input[name="equipment"]').val(),
            instructor: $('select[name="instructor"]').val(),
            intensity_rating: $('select[name="intensity_rating"]').val(),
            tag: $('#get_val').val() || []
        };

        $.ajax({
            url: "{{ route('desmode.store') }}",
            type: "POST",
            data: JSON.stringify(formData),
            contentType: "application/json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log(response);
                // location.reload();
                window.location.href = "{{ route('desmode.index') }}";
            },
            error: function(response) {
                console.log(response);
            }
        });
    });
</script>

@endsection