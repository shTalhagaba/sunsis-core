@php
    $icons = [
        'danger' => 'fa fa-warning red',
        'warning' => 'fa fa-warning orange',
        'success' => 'fa fa-check green',
        'info' => 'fa fa-info-circle blue',
    ];
@endphp
<div class="row">
    <div class="col-sm-12">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
            <div class="alert alert-{{ $msg }}">
                <button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
                <i class="{{ $icons[$msg] }}"></i> {{ Session::get('alert-' . $msg) }}
            </div>
            @endif
        @endforeach
    </div>
</div>
