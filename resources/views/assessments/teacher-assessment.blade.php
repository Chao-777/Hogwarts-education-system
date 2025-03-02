@extends('layouts.master')

@section('title', 'Teacher Assessment Details')

@section('content')
<div class="container">

    <!-- Display Assessment Details -->
    <div class="card mt-4">
        <div class="card-body">
            <h2 class="card-title">{{ $assessment->assessment_title }}</h2>
            <p><strong>Due Date:</strong> {{ $assessment->due_date->format('d M Y H:i') }}</p>
            <p><strong>Instructions:</strong> {{ $assessment->instruction }}</p>
            <p><strong>Points:</strong> {{ $assessment->max_score }}</p>
            <p><strong>Assessment Type:</strong> {{ ucfirst(str_replace('-', ' ', $assessment->type)) }}</p>
        </div>
    </div>

    <!-- Students List and Scores -->
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Students in this Course</h3>
            <span class="text-muted">{{ $students->total() }} Students</span>
        </div>
        @if ($assessment->type === 'teacher-assign' && auth()->user()->is_teacher)
        <div class="text-right mb-4">
            <form action="{{ route('assessments.assign-groups', $assessment->id) }}" method="GET">
                @csrf
                <button type="submit" class="btn btn-primary"> Assign Groups</button>
                <a href="{{ route('assessments.groups', $assessment->id) }}" class="btn btn-secondary">
            Group Lists
                </a>
            </form>
        </div>
        @endif

                <!-- Group Lists Button -->



        <div class="card-body">
            @if($students->isEmpty())
                <p class="text-muted">No students enrolled in this course yet.</p>
            @else
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Reviews Submitted</th>
                            <th>Reviews Received</th>
                            <th>Score</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            <tr>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->submitted_reviews_count }}</td>
                                <td>{{ $student->received_reviews_count }}</td>
                                <td>
                                    @if($student->assessmentScores->isNotEmpty())
                                        {{ $student->assessmentScores->first()->score }}
                                    @else
                                        <span class="text-warning">Not assigned</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('teacher.student-reviews', ['assessment' => $assessment->id, 'student' => $student->id]) }}" class="btn btn-primary">
                                        View & Score
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination Links -->
                <div class="mt-4 d-flex justify-content-center">
                    {{ $students->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>

@endsection