<div class="widget-box">
    <div class="widget-header">
        <div class="widget-title">
            <strong>Plan {{ $_plan->plan_number }}</strong> &nbsp;
            {{ \Carbon\Carbon::parse($_plan->start_date)->format('d/m/Y') }} -
            {{ \Carbon\Carbon::parse($_plan->end_date)->format('d/m/Y') }}
        </div>
        @if($edit_button)
        <div class="widget-toolbar">
            <a href="#" data-rel="tooltip" title="Edit&nbsp;the&nbsp;dates&nbsp;of&nbsp;this&nbsp;training plan" onclick="preparePlanModalForEdit({{ json_encode($_plan) }});">
                <i class="ace-icon fa fa-edit"></i>
            </a>
        </div>
        @endif
    </div>
    <div class="widget-body">
        <div class="widget-main">
            @if (!$edit_button)
            @include('students.training.partials.entity_progress_bar', ['entity' => $_plan])
            @endif
            <div class="dd" id="nestable{{ $_plan->plan_number }}">
                <input type="hidden" name="start_date_of_plan_number{{ $_plan->plan_number }}" value="{{ $_plan->start_date }}">
                <input type="hidden" name="end_date_of_plan_number{{ $_plan->plan_number }}" value="{{ $_plan->end_date }}">
                <ol class="dd-list">
                    @php
                        $unit_ids = json_decode($_plan->plan_units);
                        $units = \App\Models\Training\PortfolioUnit::with('portfolio')->whereIn('id', $unit_ids)->get();
                    @endphp
                    @forelse($units AS $unit)
                    @php
                    if($unit->id == "empty") continue;
                    @endphp
                    <li class="dd-item" data-id="{{ $unit->id }}" style="cursor: pointer;">
                        <div class="dd-handle"
                         data-rel="popover"
                         data-trigger="hover"
                         data-original-title="QAN: {{ $unit->portfolio->qan }}, Unit: {{ $unit->unique_ref_number }}"
                         data-content="<small><strong>Q:</strong> {{ $unit->portfolio->title }}<br><strong>U:</strong> {{ $unit->title }}</small>"
                         data-placement="auto">
                            QAN: {{ $unit->portfolio->qan }}, Unit: {{ $unit->unique_ref_number }}
                            @if ($unit->getOriginal('unit_group') == 1)
                            <span data-rel="tooltip" title="Mandatory unit" class="badge badge-success">M</span>
                            @else
                            <span data-rel="tooltip" title="Optional unit" class="badge badge-info">O</span>
                            @endif
                            @if (!$edit_button)
                            <span class="btn btn-xs btn-success btn-round pull-right" data-rel="tooltip" title="Signed off PCs / Total PCs">
                                    <span class=""> {{ $unit->signedOffPCs() }}/{{ count($unit->pcs) }}</span>
                            </span>
                            @endif
                        </div>
                    </li>
                    @empty
                    <li class="dd-item" data-id="empty"></li>
                    @endforelse
                </ol>
            </div>
        </div>
    </div>
</div>
