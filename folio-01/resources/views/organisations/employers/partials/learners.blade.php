<table class="table table-bordered">
    <thead>
        <tr><th>Name</th><th>Email</th><th>Location</th><th>Work Contact</th><th>Home Contact</th><th>Training Records Count</th></tr>
    </thead>
    @forelse ($organisation->students()->orderBy('surname')->get() as $student)
    <tr>
        <td>{{ $student->surname }}, {{ $student->firstnames }}</td>
        <td><a href="mailto:{{ $student->primary_email }}">{{ $student->primary_email }}</a></td>
        <td>{{ $student->location->title }}</td>
        <td>
            {!! $student->workAddress()->telephone != '' ? '<i class="fa fa-phone light-orange bigger-110"></i> <span>' .
                $student->workAddress()->telephone . '</span><br>' : '' !!}
            {!! $student->workAddress()->mobile != '' ? '<i class="fa fa-mobile light-orange bigger-110"></i> <span>' .
                $student->workAddress()->mobile . '</span><br>' : '' !!}
        </td>
        <td>
            {!! $student->homeAddress()->telephone != '' ? '<i class="fa fa-phone light-orange bigger-110"></i> <span>' .
                $student->homeAddress()->telephone . '</span><br>' : '' !!}
            {!! $student->homeAddress()->mobile != '' ? '<i class="fa fa-mobile light-orange bigger-110"></i> <span>' .
                $student->homeAddress()->mobile . '</span><br>' : '' !!}
        </td>
        <td>{{ $student->training_records->count() }}</td>
    </tr>
    @empty
    <tr><td colspan="5"><i>No learners have been created for this employer.</i></td></tr>
    @endforelse
</table>
