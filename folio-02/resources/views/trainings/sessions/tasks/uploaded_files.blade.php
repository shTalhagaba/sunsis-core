<div class="row">
    <div class="col-xs-12">
        <div class="widget-box transparent">
            <div class="widget-header">
                <h5 class="widget-title">
                    Task Evidences ({{ $task->media->count() }})
                </h5>                
            </div>
            <div class="widget-body">
                <div class="widget-main table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>File Name</th>
                                <th>File Type</th>
                                <th>File Size</th>
                                <th>Uploaded By</th>
                                <th>Uploaded At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($task->media()->where('collection_name', '!=', 'tr_task_files')->get() as $media)
                                <tr>
                                    <td>
                                        {{ \Str::limit($media->name, 45) }}
                                        <br>
                                        @if($media->getCustomProperty('feedback_file'))
                                        <span class="label label-info "><i class="fa fa-info-circle"></i> Feedback File</span>
                                        @endif
                                    </td>
                                    <td>{{ File::extension($media->file_name) }}</td>
                                    <td>{{ $media->human_readable_size }}</td>
                                    <td>
                                        @php
                                            $mediaUploadedBy = App\Models\User::find($media->getCustomProperty('uploaded_by'));
                                            echo $mediaUploadedBy ? $mediaUploadedBy->full_name : 'Unknown';
                                        @endphp
                                    </td>
                                    <td>{{ $media->updated_at->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        @if(auth()->user()->isStudent() || auth()->user()->can('download-evidence'))
                                        <a class="btn btn-info btn-round btn-xs" href="{{ route('files.download', encrypt($media->id)) }}"
                                            target="_blank" style="cursor: pointer;">
                                            <i class="fa fa-cloud-download"></i> 
                                        </a>  
                                        @else
                                        {{ \Str::limit($media->name, 45) }}
                                        @endif  

                                        @if (auth()->user()->isAdmin())
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'url' => route('media.remove', ['model' => $media->id, 'media' => $media]),
                                            'style' => 'display: inline;',
                                            'class' => 'form-inline',
                                            'id' => 'frmDeleteMedia',
                                        ]) !!}
                                        {!! Form::button('<i class="ace-icon fa fa-trash-o"></i>', [
                                            'class' => 'btn btn-xs btn-danger btn-round btnDeleteMedia',
                                            'type' => 'submit',
                                            'style' => 'display: inline',
                                        ]) !!}
                                        {!! Form::close() !!}
                                        <br>
                                    @endif      
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6"><i>No evidences have been uploaded for this task yet.</i></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
    <script>
    if (typeof window.btnDeleteMediaInitialized === 'undefined') {
        window.btnDeleteMediaInitialized = true;

        $('.btnDeleteMedia').on('click', function(e) {
            e.preventDefault();
            var form = this.closest('form');
            bootbox.confirm({
                title: 'Confirm Delete?',
                message: 'This action is irreversible, are you sure you want to continue?',
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: 'btn-xs btn-round'
                    },
                    confirm: {
                        label: '<i class="fa fa-trash-o"></i> Yes Delete',
                        className: 'btn-danger btn-xs btn-round'
                    }
                },
                callback: function(result) {
                    if (result) {
                        $(form).find(':submit').attr("disabled", true);
                        $(form).find(':submit').html('<i class="fa fa-spinner fa-spin"></i>');
                        form.submit();
                    }
                }
            });
        });
    }
    </script>
@endpush
