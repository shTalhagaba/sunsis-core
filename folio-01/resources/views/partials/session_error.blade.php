@if ($errors->any())
<div class="row">
    <div class="col-sm-12">
        <div class="alert alert-danger">
            <i class="ace-icon fa fa-exclamation-circle"></i> Validation Errors
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif
