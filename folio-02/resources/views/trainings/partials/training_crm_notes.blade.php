<div class="row">
    <div class="col-sm-12">
        <h4 class="lighter">Notes<small>
                <i class="ace-icon fa fa-angle-double-right"></i> Here you can manage crm notes.</small>
        </h4>
    </div>
</div>

@if (auth()->user()->isStaff() && auth()->user()->can('update-training-record'))
    <div class="row">
        <div class="col-sm-12">
            <span class="btn btn-primary btn-sm btn-round"
                onclick="window.location.href='{{ route('crm_notes.create', ['trainings', $training->id]) }}'">
                <i class="fa fa-plus"></i> Add New Note
            </span>
            <div class="hr hr-12 hr-dotted"></div>
        </div>
    </div>
@endif

<div class="row">
    <div class="col-xs-12 table-responsive">
        @if ($training->crmNotes->count() > 0)
            <h4 class="bigger blue text-center">{{ $training->crmNotes->count() }}
                {{ \Str::plural('Note', $training->crmNotes->count()) }}</h4>
        @endif
        <table class="table table-bordered">
            <tr>
                <th></th>
                <th>Created By</th>
                <th>Type of Contact</th>
                <th>Subject</th>
                <th>Date of Contact</th>
                <th>Time of Contact</th>
                <th>By Whom</th>
                <th>Attachments</th>
                <th style="width: 20%;">Details</th>
            </tr>
            @foreach ($training->crmNotes as $crmNote)
                <tr>
                    <td>
                        <span class="btn btn-info btn-round btn-xs btn-white"
                            onclick="window.location.href='{{ route('crm_notes.show', ['trainings', $training->id, $crmNote->id]) }}'">
                            <i class="fa fa-folder-open green"></i> View Details
                        </span> &nbsp;
                    </td>
                    <td>{{ optional($crmNote->creator)->full_name }}</td>
                    <td>{{ !is_null($crmNote->type_of_contact) ? \App\Models\LookupManager::getCrmTypeOfContacts($crmNote->type_of_contact) : '' }}</td>
                    <td>{{ !is_null($crmNote->subject) ? \App\Models\LookupManager::getCrmSubjects($crmNote->subject) : '' }}</td>
                    <td>{{ optional($crmNote->date_of_contact)->format('d/m/Y') }} </td>
                    <td>{{ $crmNote->time_of_contact }} </td>
                    <td>{{ $crmNote->by_whom }} </td>
                    <td>{{ $crmNote->media->count() }} </td> 
                    <td>{!! nl2br(e( Str::limit($crmNote->details, 300, '...') )) !!} </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
