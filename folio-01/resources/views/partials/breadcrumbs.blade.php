<div class="breadcrumbs ace-save-state" id="breadcrumbs">

    @if (count($breadcrumbs))

    <ul class="breadcrumb">

        @foreach ($breadcrumbs as $breadcrumb)

            @if ($breadcrumb->url && !$loop->last)

                <li class="breadcrumb-item ace-icon">{!! isset($breadcrumb->icon) ? $breadcrumb->icon : '' !!} <a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>

            @else

                <li class="breadcrumb-item active">{!! isset($breadcrumb->icon) ? $breadcrumb->icon : '' !!} {{ $breadcrumb->title }}</li>

            @endif

        @endforeach

        {{-- <div class="nav-search" id="nav-search">
            <form class="form-search">
                <span class="input-icon">
                    <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input"
                        autocomplete="off" />
                    <i class="ace-icon fa fa-search nav-search-icon"></i>
                </span>
            </form>
        </div><!-- /.nav-search --> --}}

    @endif

</div>


