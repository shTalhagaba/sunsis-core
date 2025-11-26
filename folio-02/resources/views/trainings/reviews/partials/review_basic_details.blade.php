<div class="row">
    <div class="col-sm-8 col-sm-offset-2">
        <div class="widget-box transparent">
            <div class="widget-header"><h5 class="widget-title center">Review</h5></div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="info-div info-div-striped">
                        <div class="info-div-row">
                            <div class="info-div-name"> Title </div>
                            <div class="info-div-value">{{ $review->title }}</div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Type of Review </div>
                            <div class="info-div-value">
                                <span>{{ !is_null($review->type_of_review) ? App\Models\LookupManager::getTrainingReviewTypes($review->type_of_review) : '' }}</span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Scheduled Date & Time </div>
                            <div class="info-div-value">
                                <span>{{ $review->due_date->format('d/m/Y') }} @ {{ $review->start_time . ' - ' }}{{ $review->end_time }}</span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Assessor </div>
                            <div class="info-div-value">
                                {{ optional(App\Models\User::find($review->assessor))->full_name }}
                            </div>
                        </div>
                        @if(!is_null($review->comments))
                        <div class="info-div-row">
                            <div class="info-div-name"> Comments </div>
                            <div class="info-div-value">
                                {!! nl2br(e($review->comments)) !!}
                            </div>
                        </div>
                        @endif  
                        <div class="info-div-row">
                            <div class="info-div-name"> File/Resource </div>
                            <div class="info-div-value">
                                @if($review->media->count() > 0)
                                <div class="col-xs-12">
                                    @include('partials.model_media_items', ['mediaFiles' => $review->media, 'model' => $review])
                                </div>
                                @endif
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-8 col-sm-offset-2">
        <div class="widget-box transparent">
            <div class="widget-header"></div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="info-div info-div-striped">
                        <div class="info-div-row">
                            <div class="info-div-name"> Last Review Actual Date</div>
                            <div class="info-div-value">
                                {{ $formData['svLastReviewActualDate'] ?? '' }}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Total Contracted Hours on Programme</div>
                            <div class="info-div-value">
                                {{ $formData['svTotalContrcatedHours'] ?? '' }}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Total OTJ Hours</div>
                            <div class="info-div-value">
                                {{ $formData['svTotalOtj'] ?? '' }}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Target Percentage of OTJ Hours</div>
                            <div class="info-div-value">
                                {{ $formData['svTargetPercentageOfOtj'] ?? 0 }}%
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Actual OTJ Hours to Date</div>
                            <div class="info-div-value">
                                {{ $formData['svActualOtjToDate'] ?? '' }}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Expected OTJ Hours to Date</div>
                            <div class="info-div-value">
                               {{ $formData['svExpectedOtjToDate'] ?? '' }}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Expected OTJ Hours Deviation</div>
                            <div class="info-div-value">
                                {{ $formData['svExpectedOtjDeviation'] ?? '' }}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Last OTJ Activity</div>
                            <div class="info-div-value">
                                @if (isset($formData['svLastOtjActivity']['id']))
                                    {{ 'Type: ' . \App\Models\LookupManager::getOtjDdl($formData['svLastOtjActivity']['type']) }}<br>
                                    {{ 'Date: ' . Carbon\Carbon::parse($formData['svLastOtjActivity']['date'])->format('d/m/Y') }}<br>
                                    {{ 'Duration: ' . $formData['svLastOtjActivityDurationFormatted'] }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-8 col-sm-offset-2 table-responsive">
        @foreach ($training->portfolios as $portfolio)
        <table class="table table-bordered">
            <tr>
                <th colspan="4">
                    <h5 class="bolder">{{ $portfolio->title }}</h5>
                    <span class="blue bolder">Expected Completion Date: </span> {{ $portfolio->planned_end_date }}
                </th>
            </tr>
            <tr>
                <th>Unit</th>
                <th>Signoff Progress</th>
                <th>Changes since last review (%)</th>
            </tr>
            @foreach ($portfolio->units as $unit)
            <tr>
                <td>
                    {{ $unit->title }}
                </td>
                <td class="center">
                    @if (isset($formData['svPortfolioUnits']) && array_key_exists($unit->id, $formData['svPortfolioUnits']))
                        {{ $formData['svPortfolioUnits'][$unit->id]->progress }}%
                    @endif
                </td>
                <td class="center">
                    @if (isset($formData['svPortfolioUnits']) && array_key_exists($unit->id, $formData['svPortfolioUnits']))
                        {{ $formData['svPortfolioUnits'][$unit->id]->progress - $formData['svPortfolioUnits'][$unit->id]->progress_on_last_review }}%
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
        @endforeach
    </div>
</div>
