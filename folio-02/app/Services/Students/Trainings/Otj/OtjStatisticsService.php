<?php

namespace App\Services\Students\Trainings\Otj;

use App\Helpers\AppHelper;
use App\Models\Training\TrainingRecord;
use Carbon\Carbon;


class OtjStatisticsService 
{
    protected $trainingRecord;
    protected $completedOtjSeconds;

    public function __construct(TrainingRecord $trainingRecord)
    {
        $this->trainingRecord = $trainingRecord;
        $this->completedOtjSeconds = $this->calculateCompletedOtjSeconds();
    }

    public function calculateCompletedOtjSeconds()
    {
        $otjEntries = $this->trainingRecord->otj()->isOtj()->get();

        return $otjEntries->reduce(function ($carry, $otj) {
            list($hours, $minutes) = explode(":", $otj->duration);
            return $carry + ($hours * 3600) + ($minutes * 60);
        }, 0);
    }

    public function getStatistics()
    {
        $totalOtjSeconds = max($this->trainingRecord->otj_hours * 3600, 1);
        $targetProgress = $this->trainingRecord->otj_target_progress;

        $expectedOtjHours = $targetProgress['expected_otj_hours'] ?? 0;
        $completedOtjHours = round($this->completedOtjSeconds / 3600);

        $expectedOtjPercentage = $targetProgress['expected_progress'] ?? 0;
        $completedOtjPercentage = round(($this->completedOtjSeconds / $totalOtjSeconds) * 100);

        $progressStatus = $completedOtjPercentage >= $expectedOtjPercentage ? 1 : 0;

        $expectedOtjSeconds = $expectedOtjHours * 3600;
        $diff = $this->completedOtjSeconds - $expectedOtjSeconds;
        $symbol = $completedOtjHours > $expectedOtjHours ? '<i class="fa fa-plus"></i>' : '<i class="fa fa-minus"></i>';
        $diffFormatted = AppHelper::convertSecondsToHoursMinutes($diff);

        $lastOtjActivity = $this->trainingRecord->otj()->isOtj()->latest()->first();
        $lastOtjActivityDate = $lastOtjActivity ? Carbon::parse($lastOtjActivity->date)->format('d/m/Y') : '';

        return [
            'totalOtjSeconds' => $totalOtjSeconds,
            'completedOtjSeconds' => $this->completedOtjSeconds,
            'expectedOtjHours' => $expectedOtjHours,
            'completedOtjHours' => $completedOtjHours,
            'expectedOtjPercentage' => $expectedOtjPercentage,
            'completedOtjPercentage' => $completedOtjPercentage,
            'progressStatus' => $progressStatus,
            'symbol' => $symbol,
            'diffFormatted' => $diffFormatted,
            'lastOtjActivity' => $lastOtjActivityDate,
            'completedOtjHoursFormatted' => $this->getFormattedOtjHours($this->completedOtjSeconds),
        ];
    }

    public function getFormattedOtjHours($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        return "{$hours} hours and {$minutes} minutes";
    }
}