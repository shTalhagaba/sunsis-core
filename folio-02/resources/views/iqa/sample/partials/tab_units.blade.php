@if(!$plan->isCompleted())
<span class="btn btn-primary btn-sm btn-round"
    onclick="window.location.href='{{ route('iqa_sample_plans.units.manage', $plan) }}'">
    <i class="fa fa-edit"></i> Add Units
</span>
@endif
<div class="hr hr-12 hr-dotted"></div>

<h5 class="text-primary bolder text-center">
    {{ $plan->units->count() }} {{ Str::plural('Unit', $plan->units->count()) }} in this plan
</h5>
<table class="table table-bordered">
    @foreach ($plan->units as $unit)
        @if($loop->first)
        <tr>
            <th><i class="fa fa-graduation-cap"></i> {{ $unit->qual_qan }}: {{ $unit->qual_title }}</th>
        </tr>
        @endif
        <tr>
            <td>
                <span style="margin-left: 3%">
                    [{{ $unit->unique_ref_number }}] [{{ $unit->unit_owner_ref }}] {!! nl2br(e($unit->title)) !!}
                </span>
                @if($plan->isScheduled())
                {!! Form::open([
                    'method' => 'DELETE',
                    'url' => route('iqa_sample_plans.units.delete', [$plan, $unit]),
                    'style' => 'display: inline;',
                    'class' => 'form-inline frmDeleteUnit',
                ]) !!}
                {!! Form::button('<i class="ace-icon fa fa-trash"></i>', [
                    'class' => 'btn btn-danger btn-xs pull-right btn-round btnDeleteUnit',
                    'id' => 'btnDeleteUnit' . $unit->id,
                    'type' => 'submit',
                    'style' => 'display: inline',
                ]) !!}
                {!! Form::close() !!}
                @endif
            </td>
        </tr>
        @if(!$loop->last && $plan->units[$loop->index+1]->qual_title != $unit->qual_title)
        <tr>
            <th><i class="fa fa-graduation-cap"></i> {{ $plan->units[$loop->index+1]->qual_qan }}: {{ $plan->units[$loop->index+1]->qual_title }}</th>
        </tr>
        @endif
    @endforeach
</table>

@push('after-scripts')
    <script>
        $('.btnDeleteUnit').on('click', function(e) {
            e.preventDefault();
            var form = this.closest('form');
            bootbox.confirm({
                title: 'Confirm Remove?',
                message: 'Are you sure you want to remove this unit from the plan?',
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: 'btn-default btn-xs btn-round'
                    },
                    confirm: {
                        label: '<i class="fa fa-trash-o"></i> Yes Remove',
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
        

    </script>
@endpush