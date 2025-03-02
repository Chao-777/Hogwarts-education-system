@extends('layouts.master')

@section('title', 'Group Lists')

@section('content')
<div class="container">
    <h2>Group Lists for {{ $assessment->assessment_title }}</h2>

    @foreach ($groups as $group)
        <div class="card mt-4">
            <div class="card-header">
                <h4>{{ $group->group_name }}</h4>
            </div>
            <div class="card-body">
                @if (isset($groupMembers[$group->group_name]) && $groupMembers[$group->group_name]->isNotEmpty())
                    <ul class="list-group">
                        @foreach ($groupMembers[$group->group_name] as $member)
                            <li class="list-group-item">
                                {{ $member->user->name }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No members in this group yet.</p>
                @endif
            </div>
        </div>
    @endforeach
</div>

<!-- Script to toggle visibility of group members -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Add event listeners to all group toggles
        document.querySelectorAll('.group-toggle').forEach(function (toggle) {
            toggle.addEventListener('click', function () {
                const groupName = this.getAttribute('data-group');
                const membersList = document.getElementById('members-' + groupName);

                // Toggle visibility
                if (membersList.style.display === 'none') {
                    membersList.style.display = 'block';
                } else {
                    membersList.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection