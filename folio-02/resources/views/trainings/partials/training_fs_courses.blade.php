<div class="row">
    <div class="col-sm-12">
        <h4 class="lighter">
            Functional Skills Courses
        </h4>
    </div>
</div>

@if (auth()->user()->isStaff() && auth()->user()->can('update-training-record'))
    <div class="row">
        <div class="col-sm-12">
            <span class="btn btn-primary btn-sm btn-round"
                onclick="window.location.href='{{ route('trainings.fs_tests.create', ['training' => $training]) }}'">
                <i class="fa fa-plus"></i> Add/Remove Courses
            </span>
            <div class="hr hr-12 hr-dotted"></div>
        </div>
    </div>
@endif

<div class="row">
    <div class="col-xs-12 table-responsive">
        @if ($training->fsTestSessions->count() > 0)
            <h4 class="bigger blue text-center">{{ $training->fsTestSessions->count() }}
                {{ \Str::plural('Test', $training->fsTestSessions->count()) }}</h4>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered">
                @forelse ($training->fsTestSessions as $fsTest)
                    <tr>
                        <td style="width: 120px">
                            <a href="{{ $fsTest->course->video_link }}" target="_blank">
                                <img class="img img-sm" width="150" height="110" alt="{{ $fsTest->course->title }}"
                                    src="{{ $fsTest->course->getThumbnail() }}" alt="Course Video/Image"
                                    class="object-cover w-full h-full">
                            </a>
                        </td>
                        <td>
                            <span class="bolder">Status: </span> {{ $fsTest->status }}<br>
                            <span class="bolder">Title: </span> {{ $fsTest->course->title }}<br>
                            <span class="bolder">Attempt Number: </span> {{ $fsTest->attempt_no }}<br>
                            <span class="bolder">Complete By: </span>
                            {{ optional($fsTest->complete_by)->format('d/m/Y') }}<br>
                            <span class="bolder">Score: </span> {{ $fsTest->score }} {{ !is_null($fsTest->score) ? ' (' . $fsTest->percentage() . '%)' : '' }}<br>
                            <span class="bolder">Started At: </span>
                            {{ optional($fsTest->started_at)->format('d/m/Y H:i:s') }}<br>
                            <span class="bolder">Completed At: </span>
                            {{ optional($fsTest->completed_at)->format('d/m/Y H:i:s') }}<br>
                        </td>
                        <td style="width: 70px">
                            <p>
                                <span class="btn btn-xs btn-info btn-round btn-white"
                                    onclick="window.location.href='{{ route('trainings.fs_tests.show', [$training, $fsTest]) }}'">
                                    <i class="fa fa-folder-open"></i> View Details </span>
                            </p>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td>No functional skills test have been created for this learner yet.</td>
                    </tr>
                @endforelse
            </table>
        </div>
    </div>
</div>
