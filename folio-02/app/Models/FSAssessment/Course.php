<?php

namespace App\Models\FSAssessment;

use App\Facades\AppConfig;
use App\Models\FSAssessment\CourseQuestion;
use App\Models\User;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use Filterable;
    
    protected $fillable = ['title', 'fs_type', 'video_link', 'details', 'created_by'];

    protected $table = 'fs_courses';

    /**
     * Relationship: A course has multiple questions.
     */
    public function questions()
    {
        // return $this->belongsToMany(CourseQuestion::class, 'fs_course_questions')->withPivot('id', 'question_order');
        return $this->hasMany(CourseQuestion::class, 'course_id')->orderBy('question_order');
    }

    /**
     * Relationship: A course belongs to a user (creator).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getThumbnail()
    {
        $courseThumbnail = asset('images/logos/' . AppConfig::get('FOLIO_LOGO_NAME'));
        if($this->video_link)
        {
            $videoId = $this->getYoutubeId();
            if(!is_null($videoId))
            {
                $courseThumbnail = "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
            }
        }

        return $courseThumbnail;
    }

    public function getYoutubeId()
    {
        $videoId = null;
        if($this->video_link)
        {
            preg_match(
                '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]+)/', 
                $this->video_link, 
                $matches
            );
            if(isset($matches[1]))
            {
                $videoId = $matches[1];
            }
        }

        return $videoId;
    }
}
