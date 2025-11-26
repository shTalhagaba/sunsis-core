<table id="{{ $tableId }}" class="table table-bordered small" title="{{ $tableHeader ?? 'List of training records' }}">
    <div class="table-header">
        {{ $tableHeader ?? 'List of training records' }}
    </div>
    <thead>
        <tr>
            <th>Reference</th>
            <th>Name</th>
            <th>Programme</th>
            <th>Employer</th>
            <th>Start Date</th>
            <th>Planned End Date</th>
            <th>Status</th>
            <th>Primary Assessor</th>
            <th>Secondary Assessor</th>
            <th>IQA</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($trainingsList as $record)
            <tr>
                <td>{{ $record->learner_ref }}</td>
                <td>{{ $record->student->full_name }}</td>
                <td>{{ $record->programme->title }}</td>
                <td>{{ optional($record->employer)->legal_name }}</td>
                <td>{{ $record->start_date->format('d/m/Y') }}</td>
                <td>{{ $record->planned_end_date->format('d/m/Y') }}</td>
                <td>{{ App\Models\Lookups\TrainingStatusLookup::getDescription($record->status_code) }}</td>
                <td>{{ $record->primaryAssessor->full_name }}</td>
                <td>{{ optional($record->secondaryAssessor)->full_name }}</td>
                <td>{{ optional($record->verifierUser)->full_name }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9">No records found</td>
            </tr>
        @endforelse                                                    
    </tbody>
</table>
