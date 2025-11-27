<div class="widget-box collapsed ">
    <div class="widget-header widget-header-large">
        <div class="row">
            <div class="col-sm-12">
                <strong title="{{ $unit->id }}">[{{ $unit->unit_owner_ref }}, {{ $unit->unique_ref_number }}]</strong>
                @if ($unit->getOriginal('unit_group') == 1)
                    <span data-rel="tooltip" title="Mandatory unit" class="badge badge-success">M</span>
                @else
                    <span data-rel="tooltip" title="Optional unit" class="badge badge-info">O</span>
                @endif
            </div>
            <div class="col-sm-12">
                {{ $unit->title }}
            </div>
            <div class="col-sm-12">
                @include('students.training.partials.entity_progress_bar', ['entity' => $unit])
            </div>
        </div>

	<h4 class="widget-title lighter">
            {{-- @if (\Auth::user()->getOriginal('user_type') != \App\Models\User::TYPE_STUDENT && $unit->isSignedOff()) --}}
	    @if (\Auth::user()->getOriginal('user_type') != \App\Models\User::TYPE_STUDENT)	
                @can('iqa-assessment')
                    <button type="button"
                            class="btn btn-xs btn-primary btn-round"
                            onclick="window.location.href='{{ route('students.training.unit.iqa.show', [$student, $training_record, $unit]) }}'">
                        <i class="ace-icon fa fa-check bigger-110"></i> IQA Check &nbsp;
                    </button> &nbsp;
                @endcan
            @endif
            @if (\Auth::user()->getOriginal('user_type') == \App\Models\User::TYPE_ASSESSOR && in_array($unit->iqa_status, ['1', '2']))
                <button type="button"
                        class="btn btn-xs btn-primary btn-round"
                        onclick="window.location.href='{{ route('students.training.unit.iqa.history', [$student, $training_record, $unit]) }}'">
                    <i class="ace-icon fa fa-check bigger-110"></i> View/Reply IQA Assessment &nbsp;
                </button> &nbsp;
            @endif
	    @if(\Auth::user()->getOriginal('user_type') == \App\Models\User::TYPE_EQA)
                <button type="button"
                        class="btn btn-xs btn-info btn-round"
                        onclick="window.location.href='{{ route('students.training.unit.eqa.show', [$student, $training_record, $unit]) }}'">
                    <i class="ace-icon fa fa-eye bigger-110"></i> View IQA &nbsp;
                </button> &nbsp;
            @endif
        </h4>

        <div class="widget-toolbar">

	    @if($unit->iqa_status != '')
                @can('view-iqa-feedback')
                    {!! $unit->iqa_completed == '1' ?
                        '<span class="label label-md label-success arrowed-in arrowed-in-right small">IQA Fully Completed</span>' :
                        ($unit->iqa_status == 1 ?
                            '<span class="label label-md label-success arrowed-in arrowed-in-right small">IQA Accepted</span>' :
                            '<span class="label label-md label-danger arrowed-in arrowed-in-right">IQA Rejected</span>')
                         !!}
                @endcan
            @endif

            <span class="btn btn-app btn-xs btn-success no-hover" data-rel="tooltip" title="Signed off PCs / Total PCs">
                <span class="line-height-1"> {{ $unit->signedOffPCs() }}/{{ count($unit->pcs) }}</span>
            </span>
            <a href="#" data-action="collapse">
                <i title="Expand this unit" class="ace-icon fa fa-chevron-down fa-lg" onclick="$('.UnitPanel{{ $unit->id }}').widget_box('show');"></i>
            </a>
        </div>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            <div class="table-responsive">
                <table class="table">
                @foreach($unit->pcs AS $pc)
                    @if($loop->first || $loop->iteration  % 2 != 0)
                    <tr>
                    @endif
                    <td style="width: 50%;">
                    @include('students.training.partials.pc', ['pcBelongsToUnit' => $unit, 'pc' => $pc])
                    </td>
                    @if($loop->last || $loop->iteration  % 2 == 0)
                    </tr>
                    @endif
                @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
