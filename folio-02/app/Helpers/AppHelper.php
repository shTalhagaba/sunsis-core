<?php

namespace App\Helpers;

use App\Facades\AppConfig;
use App\Models\License;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Training\TrainingRecord;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class AppHelper
{
    public static function generatePassword()
    {
        return Str::random();
    }

    /**
     * Generate a username from firstname and surname
     * @param string $firstname
     * @param string $surname
     * @return string
     */
    public static function generateUsername($firstname, $surname, $minLength = 8)
    {
        // pickup first letter of $firstname and append $surname make sure to pick only alphabets from surname
        $username = strtolower(substr($firstname, 0, 1) . preg_replace("/[^a-zA-Z]/", "", $surname));

        // if $username is less than 8 characters then append 12345678 to make it 8 characters
        if (strlen($username) < $minLength) {
            $username .= substr("12345678", 0, $minLength - strlen($username));
        }

        //username must be upto 45 characters so just return first 45 characters
        return substr($username, 0, 45);
    }

    public static function getBrowser($u_agent = "")
    {
        $u_agent = $u_agent != "" ? $u_agent : $_SERVER["HTTP_USER_AGENT"];
        $bname = "Unknown";
        $platform = "Unknown";
        $version = "";
        $font_awesome_icon = "fa fa-window-maximize";

        // First get the platform?
        if (preg_match("/linux/i", $u_agent)) {
            $platform = "linux";
        } elseif (preg_match("/macintosh|mac os x/i", $u_agent)) {
            $platform = "mac";
        } elseif (preg_match("/windows|win32/i", $u_agent)) {
            $platform = "windows";
        }

        $ub = "";
        // Next get the name of the user agent yes seperately and for good reason
        if (
            preg_match("/MSIE/i", $u_agent) &&
            !preg_match("/Opera/i", $u_agent)
        ) {
            $bname = "Internet Explorer";
            $ub = "MSIE";
            $font_awesome_icon = "fa fa-internet-explorer";
        } elseif (preg_match("/Firefox/i", $u_agent)) {
            $bname = "Mozilla Firefox";
            $ub = "Firefox";
            $font_awesome_icon = "fa fa-firefox";
        } elseif (preg_match("/Chrome/i", $u_agent)) {
            $bname = "Google Chrome";
            $ub = "Chrome";
            $font_awesome_icon = "fa fa-chrome";
        } elseif (preg_match("/Safari/i", $u_agent)) {
            $bname = "Apple Safari";
            $ub = "Safari";
            $font_awesome_icon = "fa fa-safari";
        } elseif (preg_match("/Opera/i", $u_agent)) {
            $bname = "Opera";
            $ub = "Opera";
            $font_awesome_icon = "fa fa-opera";
        } elseif (preg_match("/Netscape/i", $u_agent)) {
            $bname = "Netscape";
            $ub = "Netscape";
            $font_awesome_icon = "fa fa-opera";
        }

        // finally get the correct version number
        $known = ["Version", $ub, "other"];
        $pattern =
            "#(?<browser>" .
            join("|", $known) .
            ")[/ ]+(?<version>[0-9.|a-zA-Z.]*)#";
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches["browser"]);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches["version"][0];
            } else {
                $version = $matches["version"][1];
            }
        } else {
            $version = $matches["version"][0];
        }

        // check if we have a number
        if ($version == null || $version == "") {
            $version = "?";
        }

        return [
            "userAgent" => $u_agent,
            "name" => $bname,
            "version" => $version,
            "platform" => $platform,
            "pattern" => $pattern,
            "icon" => $font_awesome_icon,
        ];
    }

    public static function getFileIcon($filename = "")
    {
        $ext = \File::extension($filename);
        $icon = "fa-file-o";
        switch ($ext) {
            case "pdf":
                $icon = "fa-file-pdf-o";
                break;
            case "zip":
                $icon = "fa-file-archive-o";
                break;
            case "xls":
            case "xlsx":
                $icon = "fa-file-excel-o";
                break;
            case "png":
            case "jpg":
            case "jpeg":
                $icon = "fa-file-image-o";
                break;
            case "ppt":
            case "pptx":
                $icon = "fa-file-powerpoint-o";
                break;
            case "txt":
                $icon = "fa-file-text-o";
                break;
            case "doc":
            case "docx":
                $icon = "fa-file-word-o";
                break;
            case "mp4":
                $icon = "fa-file-video-o";
                break;
            default:
                $icon = "fa-file-o";
                break;
        }
        return $icon;
    }

    public static function getFileIconV2($filename = "")
    {
        $ext = \File::extension($filename);
        $icon = "fa-file-o";
        switch ($ext) {
            case "pdf":
                $icon = "fa-file-pdf-o";
                break;
            case "zip":
                $icon = "fa-file-archive-o";
                break;
            case "xls":
            case "xlsx":
                $icon = "fa-file-excel-o";
                break;
            case "png":
            case "jpg":
            case "jpeg":
                $icon = "fa-file-image-o";
                break;
            case "ppt":
            case "pptx":
                $icon = "fa-file-powerpoint-o";
                break;
            case "txt":
                $icon = "fa-file-text-o";
                break;
            case "doc":
            case "docx":
                $icon = "fa-file-word-o";
                break;
            default:
                $icon = "fa-file-o";
                break;
        }
        return $icon;
    }

    public static function getActualFiltersCount($filters, $exclude = [])
    {
        $counts = 0;
        foreach ($filters as $key => $value) {
            if (!in_array($key, $exclude) && $value != "") {
                $counts++;
            }
        }
        return $counts;
    }

    public static function getUserTaskTypes($key = "")
    {
        $types = [
            'rag_rating'      => 'RAG Rating',
            'deep_dive'       => 'Deep Dive',
            'otla'            => 'OTLA',
            '4_week_audit'    => '4 Week Audit',
            'iqa_sample_plan' => 'IQA Sample Plan',
        ];

        return $key === "" ? $types : ($types[$key] ?? $key);
    }

    public static function getUserEventsTypes($id = "")
    {
        $lookup = Cache::remember(
            "folio.lookup_user_events_types",
            300,
            function () {
                return DB::table("lookup_user_events_types")
                    ->orderBy("description")
                    ->pluck("description", "id")
                    ->toArray();
            }
        );

        return $id == "" ? $lookup : (isset($lookup[$id]) ? $lookup[$id] : $id);
    }



    public static function getUserEventsStatus($id = "")
    {
        $lookup = Cache::remember(
            "folio.lookup_user_events_status",
            300,
            function () {
                return DB::table("lookup_user_events_status")
                    ->orderBy("description")
                    ->pluck("description", "id")
                    ->toArray();
            }
        );

        return $id == "" ? $lookup : (isset($lookup[$id]) ? $lookup[$id] : $id);
    }

    public static function getUserTasksStatus($id = "")
    {
        $lookup = Cache::remember(
            "folio.lookup_user_tasks_status",
            300,
            function () {
                return DB::table("lookup_user_tasks_status")
                    ->orderBy("description")
                    ->pluck("description", "id")
                    ->toArray();
            }
        );

        return $id == "" ? $lookup : (isset($lookup[$id]) ? $lookup[$id] : $id);
    }

    public static function getUserEventParticipantStatus($id = "")
    {
        $lookup = Cache::remember(
            "folio.lookup_user_event_participant_status",
            300,
            function () {
                return DB::table("lookup_user_event_participant_status")
                    ->orderBy("description")
                    ->pluck("description", "id")
                    ->toArray();
            }
        );

        return $id == "" ? $lookup : (isset($lookup[$id]) ? $lookup[$id] : $id);
    }

    public static function convertSecondsToHoursMinutes($seconds)
    {
        $seconds = abs($seconds);

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        return $minutes ? sprintf('%2d hours and %2d minutes', $hours, $minutes) : sprintf('%2d hours', $hours);
    }

    public static function convertToHoursMins($time, $format = "%02d:%02d")
    {
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = $time % 60;
        return sprintf($format, $hours, $minutes);
    }

    public static function formatMysqlTimeToHoursAndMinutes($time, $format = "%02d:%02d:%02d")
    {
        list($hours, $minutes, $seconds) = sscanf($time, $format);
        $formatted = CarbonInterval::hours($hours)->minutes($minutes)->seconds($seconds);
        return $formatted->cascade()->forHumans();
    }

    public static function calculateAge($dateOfBirth)
    {
        if ($dateOfBirth == '' || is_null($dateOfBirth))
            return;

        $dob = Carbon::parse($dateOfBirth);

        $now = Carbon::now();

        $ageYears = $dob->diff($now)->format('%y');
        $ageMonths = $dob->diff($now)->format('%m');
        $ageDays = $dob->diff($now)->format('%d');

        $totalDays = $ageYears * 365 + $ageMonths * 30 + $ageDays;
        $yearsFromDays = (int)($totalDays / 365);
        $totalDays %= 365;
        $monthsFromDays = (int)($totalDays / 30);
        $daysLeft = $totalDays % 30;

        $formattedAge = "$yearsFromDays years, $monthsFromDays months, and $daysLeft days";

        return $formattedAge;
    }

    public static function calculateContrcatedHoursPerYear($contractedHoursPerWeek, $weeksToBeWorkedPerYear)
    {
        return $contractedHoursPerWeek * $weeksToBeWorkedPerYear;
    }

    public static function calculateTotalContrcatedHours($trainingStart, $trainingEnd, $contractedHoursPerWeek)
    {
        $totalWeeksOnProgramme = $trainingEnd->diffInWeeks($trainingStart);
        $annualLeaveFOrTotalWeeksOnProgramme = ($totalWeeksOnProgramme / 52.1429) * AppHelper::YEARLY_ANNUAL_LEAVE;
        $actualWeeksOnProgramme = $totalWeeksOnProgramme - $annualLeaveFOrTotalWeeksOnProgramme;

        return round($contractedHoursPerWeek * $actualWeeksOnProgramme);
    }

    public static function calculateOtjHours($trainingStart, $trainingEnd, $contractedHoursPerWeek)
    {
        $totalWeeksOnProgramme = $trainingEnd->diffInWeeks($trainingStart);
        $annualLeaveFOrTotalWeeksOnProgramme = ($totalWeeksOnProgramme / 52.1429) * AppHelper::YEARLY_ANNUAL_LEAVE;
        $actualWeeksOnProgramme = $totalWeeksOnProgramme - $annualLeaveFOrTotalWeeksOnProgramme;

        return round(self::checkForMimimumOtjHours($actualWeeksOnProgramme * 6));
    }

    public static function checkForMimimumOtjHours($otjHours)
    {
        return $otjHours < 279 ? 279 : $otjHours;
    }

    public static function replaceWithNbsp($string)
    {
        return str_replace(' ', '&nbsp;', $string);
    }

    public static function cacheUnreadCountForUser(User $user)
    {
        Cache::put("user:{$user->id}:unread_notifications_count", $user->unreadNotifications, now()->addMinutes(10));
    }

    public static function clearUnreadCountCacheForUser($user)
    {
        $cacheKey = "user:{$user->id}:unread_notifications_count";
        Cache::forget($cacheKey);
    }

    public static function addCaseloadConditionEloquent(Builder &$query, User $user)
    {
        switch ($user->user_type) {
            case UserTypeLookup::TYPE_ADMIN:
            case UserTypeLookup::TYPE_SYSTEM_VIEWER:
                break;

            case UserTypeLookup::TYPE_ASSESSOR:
                $query->where(function ($q) use ($user) {
                    $q->where('tr.primary_assessor', '=', $user->id)
                        ->orWhere('tr.secondary_assessor', '=', $user->id);
                });
                break;

            case UserTypeLookup::TYPE_TUTOR:
                $trIds = DB::table('portfolios')->where('fs_tutor_id', $user->id)->pluck('tr_id')->toArray();
                $query->where(function ($q) use ($trIds, $user) {
                    $q->where('tr.tutor', '=', $user->id)
                        ->orWhereIn('tr.id', $trIds);
                });
                break;

            case UserTypeLookup::TYPE_VERIFIER:
                $trIds = DB::table('portfolios')->where('fs_verifier_id', $user->id)->pluck('tr_id')->toArray();
                $query->where(function ($q) use ($trIds, $user) {
                    $q->where('tr.verifier', '=', $user->id)
                        ->orWhereIn('tr.id', $trIds);
                });
                break;

            case UserTypeLookup::TYPE_STUDENT:
                $query->where('tr.student_id', '=', $user->id);
                break;

            case UserTypeLookup::TYPE_EMPLOYER_USER:
                $assessorIds = DB::table('employer_user_assessor')->where('employer_user_id', $user->id)->pluck('assessor_id')->toArray();
                $query->where('users.employer_location', $user->employer_location)
                    ->where(function ($q) use ($assessorIds) {
                        $q->where('tr.employer_user_id', auth()->user()->id)
                            ->orWhere(function ($q2) use ($assessorIds) {
                                $q2->whereIn('tr.primary_assessor', $assessorIds)
                                    ->orWhereIn('tr.secondary_assessor', $assessorIds);
                            });
                    });
                break;

            case UserTypeLookup::TYPE_MANAGER:
                $assessorIds = DB::table('user_caseload_accounts')
                    ->where('user_id', $user->id)
                    ->where('caseload_account_type', UserTypeLookup::TYPE_ASSESSOR)
                    ->pluck('caseload_account_id')
                    ->toArray();
                $tutorIds = DB::table('user_caseload_accounts')
                    ->where('user_id', $user->id)
                    ->where('caseload_account_type', UserTypeLookup::TYPE_TUTOR)
                    ->pluck('caseload_account_id')
                    ->toArray();
                $verifierIds = DB::table('user_caseload_accounts')
                    ->where('user_id', $user->id)
                    ->where('caseload_account_type', UserTypeLookup::TYPE_VERIFIER)
                    ->pluck('caseload_account_id')
                    ->toArray();

                $query->where(function ($q1) use ($assessorIds, $tutorIds, $verifierIds) {
                    $q1
                        ->whereIn('tr.tutor', $tutorIds)
                        ->orWhereIn('tr.verifier', $verifierIds)
                        ->orWhere(function ($q2) use ($assessorIds) {
                            $q2->whereIn('tr.primary_assessor', $assessorIds)
                                ->orWhereIn('tr.secondary_assessor', $assessorIds);
                        });
                });

                break;

            default:
                break;
        }
    }

    public static function addCaseloadConditionDatabase(QueryBuilder &$query, User $user)
    {
        switch ($user->user_type) {
            case UserTypeLookup::TYPE_ADMIN:
                break;

            case UserTypeLookup::TYPE_ASSESSOR:
                $query->where(function ($q) use ($user) {
                    $q->where('tr.primary_assessor', '=', $user->id)
                        ->orWhere('tr.secondary_assessor', '=', $user->id);
                });
                break;

            case UserTypeLookup::TYPE_TUTOR:
                $trIds = DB::table('portfolios')->where('fs_tutor_id', $user->id)->pluck('tr_id')->toArray();
                $query->where(function ($q) use ($trIds, $user) {
                    $q->where('tr.tutor', '=', $user->id)
                        ->orWhereIn('tr.id', $trIds);
                });
                break;

            case UserTypeLookup::TYPE_VERIFIER:
                $trIds = DB::table('portfolios')->where('fs_verifier_id', $user->id)->pluck('tr_id')->toArray();
                $query->where(function ($q) use ($trIds, $user) {
                    $q->where('tr.verifier', '=', $user->id)
                        ->orWhereIn('tr.id', $trIds);
                });
                break;

            case UserTypeLookup::TYPE_STUDENT:
                $query->where('tr.student_id', '=', $user->id);
                break;

            case UserTypeLookup::TYPE_EMPLOYER_USER:
                $assessorIds = DB::table('employer_user_assessor')->where('employer_user_id', $user->id)->pluck('assessor_id')->toArray();
                $query->where('users.employer_location', $user->employer_location)
                    ->where(function ($q) use ($assessorIds) {
                        $q->whereIn('tr.primary_assessor', $assessorIds)
                            ->orWhereIn('tr.secondary_assessor', $assessorIds);
                    });
                break;

            case UserTypeLookup::TYPE_MANAGER:
                $assessorIds = DB::table('user_caseload_accounts')
                    ->where('user_id', $user->id)
                    ->where('caseload_account_type', UserTypeLookup::TYPE_ASSESSOR)
                    ->pluck('caseload_account_id')
                    ->toArray();
                $tutorIds = DB::table('user_caseload_accounts')
                    ->where('user_id', $user->id)
                    ->where('caseload_account_type', UserTypeLookup::TYPE_TUTOR)
                    ->pluck('caseload_account_id')
                    ->toArray();
                $verifierIds = DB::table('user_caseload_accounts')
                    ->where('user_id', $user->id)
                    ->where('caseload_account_type', UserTypeLookup::TYPE_VERIFIER)
                    ->pluck('caseload_account_id')
                    ->toArray();

                $query->where(function ($q1) use ($assessorIds, $tutorIds, $verifierIds) {
                    $q1
                        ->whereIn('tr.tutor', $tutorIds)
                        ->orWhereIn('tr.verifier', $verifierIds)
                        ->orWhere(function ($q2) use ($assessorIds) {
                            $q2->whereIn('tr.primary_assessor', $assessorIds)
                                ->orWhereIn('tr.secondary_assessor', $assessorIds);
                        });
                });

                break;

            default:
                $query->where('tr.employer_location', $user->employer_location);
                break;
        }
    }

    public static function enrolmentAllowed()
    {
        $purchased = License::sum('number_of_licenses');
        $used = TrainingRecord::count();
        $remaining = ((int)$purchased +  5) - (int)$used;

        return (int)$remaining > 0;
    }

    public static function requestFromOffice()
    {
        return (
            (in_array(request()->ip(), ['92.238.144.108'])) ||
            (auth()->user()->can('delete-training-record') && AppConfig::get('DELETE-TRAINING-ALLOWED') == '1')
        );
    }

    public static function getContractYear(Carbon $date)
    {
        if ($date->month < 8) {
            return $date->year - 1;
        }

        return $date->year;
    }

    public static function columnCounts(string $modelClass, string $column)
    {
        if (!class_exists($modelClass)) {
            throw new \InvalidArgumentException("Model class {$modelClass} does not exist.");
        }

        if (!is_subclass_of($modelClass, \Illuminate\Database\Eloquent\Model::class)) {
            throw new \InvalidArgumentException("{$modelClass} is not a valid Eloquent model.");
        }

        return $modelClass::query()
            ->select($column, DB::raw('count(*) as count'))
            ->groupBy($column)
            ->pluck('count', $column)
            ->toArray();
    }

    const YEARLY_ANNUAL_LEAVE = 5.6;
    const AUDIT_STATUS_CHANGE = 'training-status-change';
}
