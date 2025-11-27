<table class="table table-bordered">
    <thead>
        <tr><th>Title</th><th>Name</th><th>Job Title</th><th>Department</th><th>Contact</th></tr>
    </thead>
    @forelse ($organisation->contacts()->orderBy('surname')->get() as $contact)
    <tr>
        <td>{{ $contact->title }}</td>
        <td>{{ $contact->surname }}, {{ $contact->firstnames }}</td>
        <td>{{ $contact->job_title }}</td>
        <td>{{ $contact->department }}</td>
        <td>
            {!! $contact->email != '' ? '<i class="fa fa-envelope light-orange bigger-110"></i> <span><a href="mailto:' . $contact->email . '">' . $contact->email . '</a><br>' : '' !!}
            {!! $contact->telephone != '' ? '<i class="fa fa-phone light-orange bigger-110"></i> <span>' . $contact->telephone . '</span><br>' : '' !!}
            {!! $contact->mobile != '' ? '<i class="fa fa-mobile light-orange bigger-110"></i> <span>' . $contact->mobile . '</span><br>' : '' !!}
        </td>
    </tr>
    @empty
    <tr><td colspan="5"><i>No contacts have been created for this employer.</i></td></tr>
    @endforelse
</table>
