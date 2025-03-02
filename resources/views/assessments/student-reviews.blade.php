@extends('layouts.master')

@section('title', 'Student Reviews')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container">
    <div class="card mt-4">
        <div class="card-body">
            <h2 class="card-title mb-4">Reviews for {{ $student->name }}</h2>

            <!-- Display submitted reviews -->
            <div class="mb-4">
                <h3 class="card-subtitle mb-3">Submitted Reviews</h3>
                @if($submittedReviews->isEmpty())
                    <p class="text-muted">No reviews submitted by this student yet.</p>
                @else
                    <ul class="list-group">
                        @foreach($submittedReviews as $review)
                            <li class="list-group-item">
                                <strong>Reviewee:</strong> {{ $review->reviewee->name }}<br>
                                <strong>Review:</strong> {{ $review->review_text }}<br>
                                <strong>Rating:</strong> {{ $review->rating }} / 5
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Display received reviews -->
            <div class="mb-4">
                <h3 class="card-subtitle mb-3">Received Reviews</h3>
                @if($receivedReviews->isEmpty())
                    <p class="text-muted">This student has not received any reviews yet.</p>
                @else
                    <ul class="list-group">
                        @foreach($receivedReviews as $review)
                            <li class="list-group-item">
                                <strong>Reviewer:</strong> {{ $review->reviewer->name ?? 'Anonymous' }}<br>
                                <strong>Review:</strong> {{ $review->review_text }}<br>
                                <strong>Rating:</strong> {{ $review->rating }} / 5
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Display assigned score -->
            <div class="mb-4">
                <h3 class="card-subtitle mb-3">Assigned Score</h3>
                <p class="lead">
                    <strong>Score:</strong> 
                    @php
                        $score = $student->assessmentScores->firstWhere('assessment_id', $assessment->id);
                    @endphp

                    @if($score)
                        {{ $score->score }} / {{ $assessment->max_score }}
                    @else
                        <span class="text-warning">No score assigned yet.</span>
                    @endif
                </p>
            </div>

            <!-- Form to assign or update the score -->
            <div class="mb-4">
                <h3 class="card-subtitle mb-3">Assign or Update Score</h3>
                <form action="{{ route('teacher.assign-score', ['assessment' => $assessment->id, 'student' => $student->id]) }}" method="POST" class="form-inline">
                    @csrf
                    <div class="input-group">
                        <input type="number" name="score" class="form-control" placeholder="Enter Score" 
                               min="0" max="{{ $assessment->max_score }}" 
                               value="{{ old('score', $student->assessmentScores->first()->score ?? 0) }}" required>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-success">Submit Score</button>
                        </div>
                    </div>
                    <small class="form-text text-muted">Score should be between 0 and {{ $assessment->max_score }}.</small>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection