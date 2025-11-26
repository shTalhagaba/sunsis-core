<div class="table-responsive">
    <table class="table table-bordered small">
        <thead>
            <tr>
                <td>Event</td>
                <td>Timestamp</td>
                <td>User</td>
                <td>Details</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($_evi_details->audits as $audit)
            @if ($loop->first)
            @continue
            @endif
            <tr>
                <td class="center"><span class="label label-default ">{{ $audit->event }}</span></td>
                <td>{{ \Carbon\Carbon::parse($audit->created_at)->format('d/m/Y H:i:s') }}</td>
                <td>{{ $audit->user->full_name }}</td>
                <td>
                    <div class="info-div info-div-striped">
                        @foreach ($audit->old_values AS $field => $value)
                        <div class="info-div-row">
                            <div class="info-div-name"> {{ ucwords(str_replace("_", " ", $field)) }} </div>
                            <div class="info-div-value">
                                <span>
                                    @php
                                    switch ($field) {
                                        case 'evidence_status':
                                            echo \App\Models\Training\TrainingRecordEvidence::getEvidenceStatusDesc($audit->new_values[$field]);
                                            break;
                                        case 'evidence_files':
                                            // $files = explode(',', $audit->new_values[$field]);
                                            // foreach($files AS $file_name)
                                            // {
                                            //     $f_name = pathinfo($file_name, PATHINFO_FILENAME);
                                            //     $mediaItem = $_evi_details->media()->where('name', $f_name)->get()->first();
                                            //     if(is_null($mediaItem))
                                            //         echo $file_name . '<br>';
                                            //     else
                                            //     {
                                            //         echo '<a href="' . route('files.download',  $mediaItem) . '">';
                                            //         echo '<i class="fa ' . \App\Models\LookupManager::getFileIcon($mediaItem->file_name) . ' fa-2x"></i> ';
                                            //         echo $mediaItem->file_name;
                                            //         echo '</a><br>';
                                            //     }
                                            // }
                                            echo str_replace(',', '<br>', $audit->new_values[$field]);
                                            break;
                                        default:
                                            echo $audit->new_values[$field];
                                            break;
                                    }
                                    @endphp
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{-- <td>
    <ul class="unstyled-list">
        @foreach($audit->old_values AS $field => $value)
        <li>
            <span class="blue">{{ $field }}</span>
             <i>changed from </i>
             <span class="blue">{{ $value }}</span>
             <i>to</i>
             <span class="blue">{{ $audit->new_values[$field] }}</span>
        </li>
        @endforeach
    </ul>
</td><td></td> --}}
