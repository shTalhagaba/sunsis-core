{!! Form::open([
    'url' => route('reports.visit_type'),
    'class' => 'form-horizontal',
    'method' => 'GET',
    'role' => 'form',
    'name' => 'formFilters',
]) !!}

{!! Form::hidden('role', request('role')) !!}
{!! Form::hidden('type', request('type')) !!}
{!! Form::hidden('_reset', 0) !!}

{!! $filters->render() !!}

<div class="clearfix">
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
