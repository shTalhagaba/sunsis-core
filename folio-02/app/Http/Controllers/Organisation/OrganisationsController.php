<?php

namespace App\Http\Controllers\Organisation;

use App\Http\Controllers\Controller;
use App\Models\Organisations\Organisation;

class OrganisationsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function showSystemOwner()
	{
		$organisation = Organisation::systemOwner()->first();
        abort_if( is_null($organisation), 404, 'System owner information is not found.' );

        $organisation->load([
            'mediaSections',
            'media',
        ]);

        $mainDirectoryFiles = $organisation->media->filter(function($media) {
            return ! $media->hasCustomProperty('section_name') ? true : false;
        });

        $sectionFilesCount['main'] = count($mainDirectoryFiles);
        foreach($organisation->mediaSections AS $section)
        {
            if(! array_key_exists($section->slug, $sectionFilesCount))
            {
                $sectionFilesCount[$section->slug] = 0;
            }
            foreach($organisation->media AS $media)
            {
                $sectionFilesCount[$section->slug] += $media->getCustomProperty('section_name') === $section->slug ? 1 : 0;
            }
    
        }

        $sectionName = (request()->has('section') && in_array(request()->section, $organisation->mediaSections->pluck('slug')->toArray())) ? 
            request()->section : 
            '';

        $mediaFiles = $organisation->media->filter(function($media) use ($sectionName) {
            return $sectionName != '' ? $media->getCustomProperty('section_name') === $sectionName : true;
        });

        if($sectionName == '')
        {
            $mediaFiles = $mainDirectoryFiles;
        }

		return view('organisations.system_owner.show', compact('organisation', 'mediaFiles', 'sectionName', 'sectionFilesCount'));
	}
}
