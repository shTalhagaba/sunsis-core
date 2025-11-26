@extends('layouts.master')
@section('title', 'Add Multiple Units')
@section('page-plugin-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection
@section('breadcrumbs')
{{ Breadcrumbs::render('qualifications.units.createMultiple', $qualification) }}
@endsection
@section('page-content')
<div class="page-header"><h1>{{ $qualification->title }} </h1></div><!-- /.page-header -->
<div class="row">
    <div class="col-sm-12">
        <button class="btn btn-sm btn-white btn-default btn-round" type="button" onclick="window.location.href='{{ route('qualifications.show', $qualification) }}'">
            <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
        </button>
        <button class="btn btn-xs btn-success btn-round " type="button" onclick="submitForm();">
            <i class="ace-icon fa fa-save bigger-110"></i>
            Save Units
        </button>
        <div class="hr hr-12 hr-dotted"></div>
    </div>

    @include('partials.session_message')

    @include('partials.session_error')

    <div class="col-sm-12">
        <div class="widget-box collapsed">
            <div class="widget-header">
                <h5 class="widget-title">Existing Units</h5> &nbsp;
                <span class="badge badge-info">{{ $qualification->units->count() }}</span>
                <div class="widget-toolbar">
                    <a data-action="collapse" href="#"><i class="ace-icon fa fa-chevron-down"></i></a>
                </div>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="responsive">
                        <table class="table table-bordered small">
                            <thead>
                                <tr>
                                    <th title="Sequence Number">Seq.</th>
                                    <th>Owner Reference</th>
                                    <th>Unique Reference</th>
                                    <th>Title</th>
                                    <th>Group</th>
                                    <th>GLH</th>
                                    <th>Credit</th>
                                    <th>Learning Outcome</th>
                                    <th>PCs Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($qualification->units AS $unit)
                                    <tr>
                                        <td>{{ $unit->unit_sequence }}</td>
                                        <td>{{ $unit->unit_owner_ref }}</td>
                                        <td>{{ $unit->unique_ref_number }}</td>
                                        <td>{!! nl2br(e($unit->title)) !!}</td>
                                        <td>{{ $unit->unit_group }}</td>
                                        <td>{{ $unit->glh }}</td>
                                        <td>{{ $unit->unit_credit_value }}</td>
                                        <td>{!! nl2br(e($unit->learning_outcomes)) !!}</td>
                                        <td class="center">{{ count($unit->pcs) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8"><i>No existing units found.</i></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>                    
                </div>
            </div>
        </div>
        <div class="space-8"></div>
    </div>

    <div class="col-sm-12">
        <div class="alert alert-info">
            <i class="fa fa-info-circle"></i> Use this screen to create multiple units for the qualification.<br>
            <i class="fa fa-info-circle"></i> Please note that each unit row is only saved if <strong>Unique Reference</strong> is given for that row.<br>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th title="Sequence Number">Seq.</th>
                        <th>Owner Reference</th>
                        <th>Unique Reference</th>
                        <th>Title</th>
                        <th>Group</th>
                        <th>GLH</th>
                        <th>Credit</th>
                        <th>Learning Outcome</th>
                    </tr>
                </thead>
                <tbody>
                    {!! Form::open([
                        'name' => 'frmUnitsMultiple',
                        'id' => 'frmUnitsMultiple',
                        'url' => route('qualifications.units.storeMultiple', $qualification),
                        'class' => 'form-horizontal',
                        'method' => 'post'
                    ]) !!}
    
                    @for($i = 1; $i <= 50; $i++)
                    @php
                        $sequence = intval($qualification->units->count())+$i;
                        $prefix = 'unit_'.$sequence.'_';
                    @endphp
                    <tr>
                        <td>
                            {{ $sequence }}
                            {{ Form::hidden($prefix.'unit_sequence', $sequence) }}
                        </td>
                        <td>
                            {!! Form::text($prefix.'unit_owner_ref', 'Ref' . (intval($qualification->units->count())+$i), ['class' => 'form-control inputLimiter', 'maxlength' => '15']) !!}
                        </td>
                        <td>
                            {!! Form::text($prefix.'unique_ref_number', '', ['class' => 'form-control inputLimiter', 'maxlength' => '15']) !!}
                        </td>
                        <td>
                            {!! Form::textarea($prefix.'title', '', ['class' => 'form-control inputLimiter', 'maxlength' => '850', 'rows' => '3']) !!}
                        </td>
                        <td>
                            {!! Form::select($prefix.'unit_group', \App\Models\Qualifications\QualificationUnit::getDDLUnitGroups(false), '', ['class' => 'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number($prefix.'glh', 0, ['class' => 'form-control', 'max' => '999']) !!}
                        </td>
                        <td>
                            {!! Form::number($prefix.'unit_credit_value', 0, ['class' => 'form-control', 'max' => '100']) !!}
                        </td>
                        <td>
                            {!! Form::textarea($prefix.'learning_outcomes', '', ['class' => 'form-control inputLimiter', 'maxlength' => '250', 'rows' => '3']) !!}
                        </td>
                    </tr>
                    @endfor
                    {!! Form::close() !!}
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('page-plugin-scripts')
<script src="{{ asset('assets/js/jquery.inputlimiter.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endsection

@section('page-inline-scripts')
<script>
$(function(){
    $('.inputLimiter').inputlimiter();
});

var validationFields = {
    'unit_owner_ref': 'Unit Owner Reference',
    'title': 'Unit Title',
    'glh': 'Unit GLH',
    'unit_credit_value': 'Unit Credit Value'
};

var saved_references = @json($saved_references);

function submitForm()
{
    var valid_form = true;
    var isThereAnyUnitRef = false;
    var new_references = [];

    // unique references in this form must not be in already saved units in the database.
    // unique references cannot be duplicate in the form.
    // if unique reference is given and unique then validate owner reference and title
    $("input[name$=unique_ref_number]").each(function(index, element){
        var flag = true;
        if(element.value.trim() != '')
        {
            isThereAnyUnitRef = true;
            if(element.value.length < 4)
            {
                element.focus();
                $.alert('Unit unique reference must be at least 4 characters.', 'Unique Reference');
                return valid_form = flag = false;
            }
            if($.inArray(element.value.trim(), saved_references) !== -1) // if in the already used references
            {
                element.focus();
                $.alert('Unit reference ' + element.value.trim() + ' is not unique.', 'Unique Reference');
                return valid_form = flag = false;
            }
            else
            {
                if($.inArray(element.value.trim(), new_references) !== -1) // if in the new references
                {
                    element.focus();
                    $.alert('Unit reference ' + element.value.trim() + ' is duplicate in this form.', 'Unique Reference');
                    return valid_form = flag = false;
                }
                else
                {
                    new_references.push(element.value.trim());
                }

                var n = element.name.split('_');
                $.each(validationFields, function(i, v){
                    form_element_name = 'unit_'+n[1]+'_'+i;
                    if($("[name="+form_element_name+"]").val().trim() == '')
                    {
                        $.alert({
                            title: 'Validation Error!',
                            icon: 'fa fa-warning',
                            type: 'red',
                            content: v + ' is required for unit (reference: '+element.value+').',
                            onDestroy: function(){
                                $("[name="+form_element_name+"]").focus();
                            }
                        });
                        return flag = false;
                    }
                });
            }
        }
        valid_form = flag;
        return flag;
    });

    if(!isThereAnyUnitRef)
    {
        $.alert('There is nothing to save.', 'Blank Form');
        return;
    }
    else if(valid_form)
        document.forms["frmUnitsMultiple"].submit();
}
</script>
@endsection
