@php
    $already_selected_units_ids = $already_selected_units_ids ?? [];
    $already_selected_pcs = $already_selected_pcs ?? [];
@endphp

<div class="table-responsive">
    @foreach($training->portfolios AS $portfolio)
    <div class="widget-box transparent ui-sortable-handle">
        <div class="widget-header">
            <h5 class="widget-title bolder">
                <i class="fa fa-graduation-cap"></i> {{ $portfolio->qan }} {{ $portfolio->title }}
            </h5>
            <div class="widget-toolbar">
                <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-up"></i></a>
            </div>
        </div>
        <div class="widget-body">
            <div class="widget-main">
                @foreach($portfolio->units()->orderBy('unit_sequence')->get() AS $unit)
                <table class="table table-bordered table-hover">
                    <tr>
                        <th class="center" style="width: 8%;">
                            <div class="checkbox">
                                <label>
                                    <input name="chkUnit[]" id="chkUnit{{ $unit->id }}" value="{{ $unit->id }}" class="ace ace-checkbox-2 chkUnit" type="checkbox" />
                                    <span class="lbl"> </span>
                                </label>
                            </div>
                        </th>
                        <th class="brown" colspan="3">
							<i class="fa fa-folder fa-lg"></i> 
							<h5 style="display: inline;">[{{ $unit->unit_owner_ref }}, {{ $unit->unique_ref_number }}] {{ $unit->title }}</h5>
							<span class="pull-right">
                                <i class="ace-icon fa fa-chevron-{{ in_array($unit->id, $already_selected_units_ids) ? 'up' : 'down' }}" onclick="showUnitEvidencesRows('{{ $unit->id }}', this);"></i>
                            </span>
						</th>
                    </tr>
                    @foreach($unit->pcs()->orderBy('pc_sequence')->get() AS $pc)
                    <tr style="cursor: pointer; display: {{ in_array($unit->id, $already_selected_units_ids) ? '' : 'none' }};" id="RowOfUnit{{ $unit->id }}Evidence{{ $pc->id }}">
                        <td class="center" style="width: 8%;">
                            <div class="checkbox">
                                <label>
                                    <input name="elements[]" id="pc{{ $pc->id }}OfUnit{{ $unit->id }}" value="{{ $pc->id }}" class="ace ace-checkbox-2 elements" type="checkbox"
                                        {{ in_array($pc->id, $already_selected_pcs) ? 'checked="checked"' : '' }}
                                        />
                                    <span class="lbl"> </span>
                                </label>
                            </div>
                        </td>
                        <td style="width: 75%;">
                            <i class="fa fa-folder-open"></i> [{{ $pc->reference }}] {!! nl2br($pc->title) !!}
                        </td>
                        <td class="center"><h4 class="larger">{{ $pc->delivery_hours }}</h4></td>
                    </tr>
                    @endforeach
                </table>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach   
</div>

@push('after-scripts')
    <script>
        $(function(){
            
            $('input[type="checkbox"][name="elements[]"]').each(function(){
                if(this.checked)
                {
                    var unit_number = this.id.replace('pc'+this.value+'OfUnit', '');
                    $('input[type="checkbox"][id="chkUnit'+unit_number+'"]').prop('checked', true);
                }
            });

            $('input[type=checkbox][id^=chkUnit]').on('click', function(){
                var unit_number = this.id.replace('chkUnit', '');
                if(this.checked)
                {
                    $("input[type='checkbox'][id$='OfUnit"+unit_number+"']").each(function() {
                        $(this).prop('checked', true);
                    });
                }
                else
                {
                    $("input[type='checkbox'][id$='OfUnit"+unit_number+"']").each(function() {
                        $(this).prop('checked', false);
                    });
                }
            });
            
            $('input[type="checkbox"][name="elements[]"]').on('click', function(){
                var unit_number = this.id.replace('pc'+this.value+'OfUnit', '');
                if(this.checked) // if pc is clicked then check the Unit checkbox too.
                {
                    $('input[type="checkbox"][id="chkUnit'+unit_number+'"]').prop('checked', true);
                }
                else // if all pcs of a unit are unticked then untick the unit
                {
                    var allPCUnChecked = true;
                    $("input[type='checkbox'][id$='OfUnit"+unit_number+"']").each(function() {
                        if(this.checked)
                        {
                            allPCUnChecked = false;
                            return false;
                        }
                    });
                    if(allPCUnChecked)
                    {
                        $('input[type="checkbox"][id="chkUnit'+unit_number+'"]').prop('checked', false);
                    }
                }
            });

        });

        function showUnitEvidencesRows(unit_id, element)
        {
            var rows_id = 'RowOfUnit'+unit_id+'Evidence';
            $("tr[id^=" + rows_id + "]").toggle();
            $(element).toggleClass('fa-chevron-down fa-chevron-up');
        }

    </script>
@endpush