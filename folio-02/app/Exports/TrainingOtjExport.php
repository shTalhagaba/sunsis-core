<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class TrainingOtjExport implements FromCollection, WithHeadings
{
    private $filters;
    private $training;

    public function __construct($filters, $training)
    {
        $this->filters = $filters;
        $this->training = $training;
    }

    public function headings(): array
    {
        return [
            'Status',
            'Title',
            'Type',
            'Date and Time',
            'Details',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $filteredOtjQuery = $this->filters->apply($this->training->otj()->getQuery());

        // Add default sorting (if none applied)
        if (!isset($this->filters->filters()['sort_by'])) {
            $filteredOtjQuery->orderBy('date', 'desc')->orderBy('start_time', 'desc');
        }

        $filteredOtj = $filteredOtjQuery->get();

        $result = collect();
        foreach ($filteredOtj as $otj) {
            $date = \Carbon\Carbon::parse($otj->date)->format('d/m/Y');
            $startTime = \Carbon\Carbon::parse($otj->start_time)->format('H:i');
            $duration = !is_null($otj->duration)
                ? \App\Helpers\AppHelper::formatMysqlTimeToHoursAndMinutes($otj->duration)
                : '';

            // Combine into one column
            $dateTimeString = "Date: {$date}\nStart Time: {$startTime}";
            if ($duration !== '') {
                $dateTimeString .= "\nDuration: {$duration}";
            }

            $result->push([
                $otj->title,
                $otj->status,
                \App\Models\LookupManager::getOtjDdl($otj->type),
                $dateTimeString,
                nl2br(e($otj->details))
            ]);
        }

        return $result;
    }
}
