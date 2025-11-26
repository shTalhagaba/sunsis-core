{!! Form::open([
    'url' => route('iqa_sample_plans.index'), 
    'class' => 'form-horizontal', 
    'method' => 'GET',
    'role' => 'form',
    'name' => 'formFilters',
    ]) !!}

{!! Form::hidden('_reset', 0) !!}

{!! $filters->render() !!}

<div class="clearfix" style="margin-top: 5px;">
    <button class="btn btn-sm btn-round btn-primary" type="submit">
        <i class="ace-icon fa fa-search bigger-110"></i>
        Search
    </button>
    &nbsp; &nbsp; &nbsp;
    <button class="btn btn-sm btn-round btn-default" type="button" onclick="resetViewFilters(this);">
        <i class="ace-icon fa fa-undo bigger-110"></i>
        Reset
    </button>
</div>

{!! Form::close() !!}
<hr>

