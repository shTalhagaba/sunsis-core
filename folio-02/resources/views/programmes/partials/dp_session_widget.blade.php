<div class="widget-box collapsed">
    <div class="widget-header">
        <h5 class="widget-title bold">
            Session {{ $dpSession->session_number }}
            ({{ is_array(json_decode($dpSession->session_pcs)) ? count(json_decode($dpSession->session_pcs)) : 0 }}
            Criteria)
        </h5>
        <div class="widget-toolbar">
            @can('update-programme')
                @if($dpSession->is_template == 0)
                    <button type="button" class="btn btn-xs btn-info btn-round"
                            data-toggle="modal" data-target="#taskModal{{ $dpSession->id }}">
                        <i class="fa fa-list"></i> Tasks
                        ({{ $dpSession->tasks->count() + $dpSession->templateTasks->count() }})
                    </button>
                @endif

                <button type="button"
                        onclick="document.location.href='{{ route('programmes.sessions.edit', [$programme, $dpSession]) }}{{ $dpSession->is_template == 1 ? '?is_template=1' : '' }}'"
                        class="btn btn-xs btn-purple btn-round"><i class="fa fa-edit"></i> Edit
                </button>    &nbsp;
                {!! Form::open([
                    'method' => 'DELETE',
                    'url' => route('programmes.sessions.destroy', [$programme, $dpSession]),
                    'style' => 'display: inline;',
                    'class' => 'form-inline',
                ]) !!}
                {!! Form::hidden('dp_session_id_to_del', $dpSession->id) !!}
                {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-110"></i> Delete', [
                    'data-rel' => 'tooltip',
                    'class' => 'btn btn-danger btn-xs btn-round btnDeleteDpSession',
                    'type' => 'click',
                ]) !!}
                {!! Form::close() !!}
            @endcan
            |
            <a href="#" data-action="collapse">
                <i class="ace-icon fa fa-chevron-down"></i>
            </a>
        </div>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            <div class="row">
                <div class="col-xs-12">
                    <blockquote class="small">
                        <p>
                            {!! nl2br($dpSession->session_details_1) !!}
                        </p>
                    </blockquote>
                </div>
                <div class="col-xs-12">
                    <blockquote class="small">
                        <p>
                            {!! nl2br($dpSession->session_details_2) !!}
                        </p>
                    </blockquote>
                </div>
                <div class="col-xs-12">
                    @php
                        $hoursTotal = 0;
                        $elements = !is_array(json_decode($dpSession->session_pcs))
                            ? collect([])
                            : App\Models\Programmes\ProgrammeQualificationUnitPC::whereIn(
                                    'id',
                                    json_decode($dpSession->session_pcs),
                                )
                                ->with('unit:id,unit_sequence,unique_ref_number')
                                ->orderBy('pc_sequence')
                                ->get();
                        echo '<h4 class="text-info">Criteria (' . count($elements) . ')</h4>';
                        foreach ($elements as $element) {
                            echo '[' . $element->unit->unique_ref_number .'] ' . nl2br(e($element->title)) . '<hr style="margin-top: 10px; margin-bottom: 10px">';
                            $hoursTotal += $element->delivery_hours;
                        }
                    @endphp
                    <p><span class="bolder text-info">Total OTJ Hours: </span>{{ $hoursTotal }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@if($dpSession->is_template == 0)
    <div class="modal fade" id="taskModal{{ $dpSession->id }}" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Tasks (Session {{ $dpSession->session_number }})</h4>
                </div>
                <div class="modal-body">
                    @if(auth()->user()->isAdmin())
                        <div>
                            <button class="btn btn-sm btn-primary btn-round" type="button"
                                    onclick="window.location.href='{{ route('programmes.sessions.tasks.create', [$programme, $dpSession]) }}'">
                                <i class="ace-icon fa fa-plus bigger-110"></i> Add Task
                            </button>
                            <button class="btn btn-sm btn-primary btn-round pull-right" type="button"
                                    onclick="window.location.href='{{ route('programmes.sessions.tasks.create', [$programme, $dpSession]) }}?is_template=1'">
                                <i class="ace-icon fa fa-plus bigger-110"></i> Add Task Template
                            </button>
                        </div>
                    @endif
                    <div class="widget-box collapsed">
                        <div class="widget-header">
                            <h5 class="widget-title bold">
                                Tasks ({{ $dpSession->tasks->count() }})
                            </h5>
                            <div class="widget-toolbar">
                                | <a href="#" data-action="collapse">
                                    <i class="ace-icon fa fa-chevron-down"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 3%;">#</th>
                                            <th>Details</th>
                                            <th>Status</th>
                                            <th>Criteria</th>
                                            <th style="width: 2%;">Action</th>
                                        </tr>
                                        @foreach($dpSession->tasks as $task)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <span class="text-info bolder">Title: </span> {{ $task->title }}<br>
                                                    <span class="text-info bolder">Details: </span> {!! \Illuminate\Support\Str::limit($task->details,50) !!}
                                                </td>
                                                <td>
                                                    @if($task->status)
                                                        <span class="label label-success arrowed-in arrowed-in-right">Active</span>
                                                    @else
                                                        <span class="label label-danger arrowed-in arrowed-in-right">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>{{ $task->pcs->count() }}</td>
                                                <td>
                                                    <div class="text-nowrap">
                                                        <a class="btn btn-xs btn-default btn-round inline"
                                                           type="button"
                                                           href="{{ route('programmes.sessions.tasks.show', [$programme, $dpSession, $task]) }}">
                                                            <i class="ace-icon fa fa-eye"></i>
                                                        </a>
                                                        <a class="btn btn-xs btn-info btn-round inline" type="button"
                                                           href="{{ route('programmes.sessions.tasks.edit', [$programme, $dpSession, $task]) }}">
                                                            <i class="ace-icon fa fa-edit bigger-110"></i>
                                                        </a>
                                                        <div class="inline">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'url' => route('programmes.sessions.tasks.destroy', [$programme, $dpSession, $task]),
                                                                'id' => 'frmDeleteSessionTask',
                                                            ]) !!}
                                                            {!! Form::hidden('task_id_to_del', $task->id) !!}
                                                            {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-110"></i>', [
                                                                'data-rel' => 'tooltip',
                                                                'class' => 'btn btn-danger btn-xs btn-round',
                                                                'type' => 'click',
                                                                'id' => 'btnDeleteSessionTask',
                                                            ]) !!}
                                                            {!! Form::close() !!}
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="widget-box collapsed">
                        <div class="widget-header">
                            <h5 class="widget-title bold">
                                Template Tasks ({{ $dpSession->templateTasks->count() }})
                            </h5>
                            <div class="widget-toolbar">
                                | <a href="#" data-action="collapse">
                                    <i class="ace-icon fa fa-chevron-down"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 3%;">#</th>
                                            <th>Details</th>
                                            <th>Status</th>
                                            <th>Criteria</th>
                                            <th style="width: 2%;">Action</th>
                                        </tr>
                                        @foreach($dpSession->templateTasks as $task)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <span class="text-info bolder">Title: </span> {{ $task->title }}<br>
                                                    <span class="text-info bolder">Details: </span> {!! \Illuminate\Support\Str::limit($task->details,50) !!}
                                                </td>
                                                <td>
                                                    @if($task->status)
                                                        <span class="label label-success arrowed-in arrowed-in-right">Active</span>
                                                    @else
                                                        <span class="label label-danger arrowed-in arrowed-in-right">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>{{ $task->pcs->count() }}</td>
                                                <td>
                                                    <div class="text-nowrap">
                                                        <a class="btn btn-xs btn-default btn-round inline"
                                                           type="button"
                                                           href="{{ route('programmes.sessions.tasks.show', [$programme, $dpSession, $task]) }}">
                                                            <i class="ace-icon fa fa-eye"></i>
                                                        </a>
                                                        <a class="btn btn-xs btn-info btn-round inline" type="button"
                                                           href="{{ route('programmes.sessions.tasks.edit', [$programme, $dpSession, $task]) }}">
                                                            <i class="ace-icon fa fa-edit bigger-110"></i>
                                                        </a>
                                                        <div class="inline">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'url' => route('programmes.sessions.tasks.destroy', [$programme, $dpSession, $task]),
                                                                'id' => 'frmDeleteSessionTask',
                                                            ]) !!}
                                                            {!! Form::hidden('task_id_to_del', $task->id) !!}
                                                            {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-110"></i>', [
                                                                'data-rel' => 'tooltip',
                                                                'class' => 'btn btn-danger btn-xs btn-round',
                                                                'type' => 'click',
                                                                'id' => 'btnDeleteSessionTask',
                                                            ]) !!}
                                                            {!! Form::close() !!}
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endif