@extends('admin.layout')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create New Workout</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.workouts') }}">Workouts</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Workout Details</h3>
                        </div>
                        <form action="{{ route('admin.workouts.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title">Workout Title *</label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                                   id="title" name="title" value="{{ old('title') }}" required>
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="category">Category *</label>
                                            <select class="form-control @error('category') is-invalid @enderror" 
                                                    id="category" name="category" required>
                                                <option value="">Select Category</option>
                                                <option value="strength" {{ old('category') == 'strength' ? 'selected' : '' }}>Strength</option>
                                                <option value="cardio" {{ old('category') == 'cardio' ? 'selected' : '' }}>Cardio</option>
                                                <option value="hiit" {{ old('category') == 'hiit' ? 'selected' : '' }}>HIIT</option>
                                                <option value="mobility" {{ old('category') == 'mobility' ? 'selected' : '' }}>Mobility</option>
                                                <option value="yoga" {{ old('category') == 'yoga' ? 'selected' : '' }}>Yoga</option>
                                                <option value="pilates" {{ old('category') == 'pilates' ? 'selected' : '' }}>Pilates</option>
                                                <option value="crossfit" {{ old('category') == 'crossfit' ? 'selected' : '' }}>CrossFit</option>
                                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('category')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="difficulty">Difficulty Level *</label>
                                            <select class="form-control @error('difficulty') is-invalid @enderror" 
                                                    id="difficulty" name="difficulty" required>
                                                <option value="">Select Difficulty</option>
                                                <option value="beginner" {{ old('difficulty') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                                <option value="intermediate" {{ old('difficulty') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                                <option value="advanced" {{ old('difficulty') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                            </select>
                                            @error('difficulty')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="duration">Duration (minutes)</label>
                                            <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                                                   id="duration" name="duration" value="{{ old('duration') }}" min="1">
                                            @error('duration')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="equipment">Equipment Needed</label>
                                            <input type="text" class="form-control @error('equipment') is-invalid @enderror" 
                                                   id="equipment" name="equipment" value="{{ old('equipment') }}" 
                                                   placeholder="e.g., Dumbbells, Resistance Bands, None">
                                            @error('equipment')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="calories_burned">Estimated Calories Burned</label>
                                            <input type="number" class="form-control @error('calories_burned') is-invalid @enderror" 
                                                   id="calories_burned" name="calories_burned" value="{{ old('calories_burned') }}" min="1">
                                            @error('calories_burned')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="sets">Sets</label>
                                            <input type="number" class="form-control @error('sets') is-invalid @enderror" 
                                                   id="sets" name="sets" value="{{ old('sets') }}" min="1">
                                            @error('sets')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="reps">Reps</label>
                                            <input type="number" class="form-control @error('reps') is-invalid @enderror" 
                                                   id="reps" name="reps" value="{{ old('reps') }}" min="1">
                                            @error('reps')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="weight">Weight (lbs)</label>
                                            <input type="number" step="0.1" class="form-control @error('weight') is-invalid @enderror" 
                                                   id="weight" name="weight" value="{{ old('weight') }}" min="0">
                                            @error('weight')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="instructions">Instructions</label>
                                    <textarea class="form-control @error('instructions') is-invalid @enderror" 
                                              id="instructions" name="instructions" rows="5" 
                                              placeholder="Step-by-step instructions for the workout...">{{ old('instructions') }}</textarea>
                                    @error('instructions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="image_file">Workout Image</label>
                                            <input type="file" class="form-control @error('image_file') is-invalid @enderror" 
                                                   id="image_file" name="image_file" accept="image/*">
                                            @error('image_file')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="video_file">Workout Video</label>
                                            <input type="file" class="form-control @error('video_file') is-invalid @enderror" 
                                                   id="video_file" name="video_file" accept="video/*">
                                            @error('video_file')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="is_public" name="is_public" 
                                               value="1" {{ old('is_public') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_public">
                                            Make this workout public (available to all users)
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create Workout
                                </button>
                                <a href="{{ route('admin.workouts') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Workouts
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
