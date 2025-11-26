<div class="widget-box collapsed" style="border-radius:2px">
    <div class="widget-header widget-header-large">
        <div class="row">
            <div class="col-sm-12">
                <strong title="{{ $unit->id }}"></strong>
                <span class="widget-title">
                    <h4 class="bolder">
                        [{{ $unit->unit_owner_ref }}] [{{ $unit->unique_ref_number }}]
                        {{ $unit->title }} 
                        @if ($unit->isMandatory())
                            <span data-rel="tooltip" title="Mandatory unit" class="badge badge-success">M</span>
                        @else
                            <span data-rel="tooltip" title="Optional unit" class="badge badge-info">O</span>
                        @endif
                    </h4>
                </span>
            </div>
            <div class="col-xs-12">
                @include('trainings.partials.entity_progress_bar', ['entity' => $unit])
            </div>
        </div>

	<h4 class="widget-title">
	    @if (\Auth::user()->getOriginal('user_type') != \App\Models\Lookups\UserTypeLookup::TYPE_STUDENT)	
            @can('iqa-assessment')
                <button type="button"
                        class="btn btn-xs btn-primary btn-round"
                        onclick="window.location.href='{{ route('trainings.unit.iqa.show', [$training, $unit]) }}'">
                    <i class="ace-icon fa fa-check bigger-110"></i> IQA Check &nbsp;
                </button> &nbsp;
            @endcan
        @endif
        @if (\Auth::user()->getOriginal('user_type') == \App\Models\Lookups\UserTypeLookup::TYPE_ASSESSOR && in_array($unit->iqa_status, ['1', '2']))
            <button type="button"
                    class="btn btn-xs btn-primary btn-round"
                    onclick="window.location.href='{{ route('trainings.unit.iqa.reply.show', [$training, $unit]) }}'">
                <i class="ace-icon fa fa-check bigger-110"></i> View/Reply IQA Assessment &nbsp;
            </button> &nbsp;
        @endif
	    @if(\Auth::user()->getOriginal('user_type') == \App\Models\Lookups\UserTypeLookup::TYPE_EQA)
                <button type="button"
                        class="btn btn-xs btn-info btn-round"
                        onclick="window.location.href='{{ route('trainings.unit.eqa.show', [$training, $unit]) }}'">
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
                            '<span class="label label-md label-danger arrowed-in arrowed-in-right">IQA Referred</span>')
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
                    @include('trainings.partials.pc', ['pcBelongsToUnit' => $unit, 'pc' => $pc])
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
