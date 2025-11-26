<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\CRM\CrmNote;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use InvalidArgumentException;

class CrmNoteController extends Controller
{
    public function create($noteableType, $noteable)
    {
        abort_if( !$this->isAuthorized(__METHOD__, $noteableType, $noteable), Response::HTTP_UNAUTHORIZED );

        $backUrl = $backUrl = $this->resolveRedirectRoute($noteableType, $noteable);
        $data = $this->resolveNoteable($noteable);

        return view('crm_notes.create', array_merge(compact('noteableType', 'noteable', 'backUrl'), $data));
    }

    public function store($noteableType, $noteable, Request $request, FileUploadService $fileUploadService)
    {
        abort_if( !$this->isAuthorized(__METHOD__, $noteableType, $noteable), Response::HTTP_UNAUTHORIZED );

        $noteData = $request->validate([
            'type_of_contact' => 'nullable|numeric',
            'subject' => 'nullable|numeric',
            'date_of_contact' => 'required|date_format:"Y-m-d"',
            'time_of_contact' => 'nullable|date_format:"H:i"',
            'by_whom' => 'required|max:70',
            'details' => 'required|string'
        ]);

        $crmNote = $noteable->crmNotes()->create([
            'type_of_contact' => $noteData['type_of_contact'],
            'subject' => $noteData['subject'],
            'date_of_contact' => $noteData['date_of_contact'],
            'time_of_contact' => $noteData['time_of_contact'],
            'by_whom' => $noteData['by_whom'],
            'details' => preg_replace('/[^\x00-\x7F]/', '', $noteData['details']),
            'created_by' => auth()->user()->id,
        ]);

        if ($request->hasFile('crm_note_attachment')) 
        {
            $fileUploadService->uploadAndAttachMedia($request, $crmNote, 'crm_note_attachment');
        }

        return redirect()
            ->route('crm_notes.show', [$noteableType, $noteable->id, $crmNote->id])
            ->with(['alert-success' => 'CRM Note added successfully.']);
    }

    public function show($noteableType, $noteable, CrmNote $crmNote)
    {
        abort_if( !$this->isAuthorized(__METHOD__, $noteableType, $noteable, $crmNote), Response::HTTP_UNAUTHORIZED );

        $backUrl = $this->resolveRedirectRoute($noteableType, $noteable);
        $data = $this->resolveNoteable($noteable);

        return view('crm_notes.show', array_merge(compact('noteableType', 'noteable', 'crmNote', 'backUrl'), $data));
    }

    public function edit($noteableType, $noteable, CrmNote $crmNote)
    {
        abort_if( !$this->isAuthorized(__METHOD__, $noteableType, $noteable, $crmNote), Response::HTTP_UNAUTHORIZED );

        $data = $this->resolveNoteable($noteable);

        return view('crm_notes.edit', array_merge(compact('noteableType', 'noteable', 'crmNote'), $data));
    }

    public function update($noteableType, $noteable, CrmNote $crmNote, Request $request, FileUploadService $fileUploadService)
    {
        abort_if( !$this->isAuthorized(__METHOD__, $noteableType, $noteable, $crmNote), Response::HTTP_UNAUTHORIZED );

        $noteData = $request->validate([
            'type_of_contact' => 'nullable|numeric',
            'subject' => 'nullable|numeric',
            'date_of_contact' => 'required|date_format:"Y-m-d"',
            'time_of_contact' => 'nullable',
            'by_whom' => 'required|max:70',
            'details' => 'required|string'
        ]);

        $crmNote->update([
            'type_of_contact' => $noteData['type_of_contact'],
            'subject' => $noteData['subject'],
            'date_of_contact' => $noteData['date_of_contact'],
            'time_of_contact' => $noteData['time_of_contact'],
            'by_whom' => $noteData['by_whom'],
            'details' => preg_replace('/[^\x00-\x7F]/', '', $noteData['details']),
        ]);

        if ($request->hasFile('crm_note_attachment')) 
        {
            $fileUploadService->uploadAndAttachMedia($request, $crmNote, 'crm_note_attachment');
        }

        return redirect()
            ->route('crm_notes.show', [$noteableType, $noteable->id, $crmNote->id])
            ->with(['alert-success' => 'CRM Note updated successfully.']);
    }

    public function destroy($noteableType, $noteable, CrmNote $crmNote)
    {
        abort_if( !$this->isAuthorized(__METHOD__, $noteableType, $noteable, $crmNote), Response::HTTP_UNAUTHORIZED );

        $crmNote->delete();

        $redirectTo = $this->resolveRedirectRoute($noteableType, $noteable);

        return redirect($redirectTo)->with(['alert-success' => 'CRM Note deleted successfully.']);
    }

    /**
     * Resolve redirect route based on noteable type.
     *
     * @param string $noteableType
     * @param mixed $noteable
     * @return string
     */
    private function resolveRedirectRoute($noteableType, $noteable)
    {
        $routeMap = [
            'trainings' => 'trainings.show',
            'students' => 'students.show',
        ];

        // If the noteable type is not supported, throw an exception or return a default route
        if (!isset($routeMap[$noteableType])) 
        {
            throw new InvalidArgumentException("Unsupported noteable type: $noteableType");
        }

        return route($routeMap[$noteableType], $noteable);
    }

    protected function resolveNoteable($noteable)
    {
        $student = null;
        $training = null;

        if ($noteable->getMorphClass() == \App\Models\Student::class) {
            $training = $noteable;
        }

        if ($noteable->getMorphClass() == \App\Models\Training\TrainingRecord::class) {
            $training = $noteable;
            $student = $training->student;
        }

        return compact('student', 'training');
    }

    private function isAuthorized($action, $noteableType, $noteable, $crmNote = null)
    {
        if( !is_null($crmNote) )
        {
            if( !in_array($crmNote->id, $noteable->crmNotes()->pluck('id')->toArray()) )
            {
                return false;
            }

            if($action != 'App\Http\Controllers\CRM\CrmNoteController::show' && $crmNote->created_by !== auth()->user()->id)
            {
                return false;
            }
        }

        if( auth()->user()->isStaff() && auth()->user()->can('update-training-record') )
        {
            return true;
        }

        if(
            auth()->user()->isStudent() && 
            $action === 'App\Http\Controllers\CRM\CrmNoteController::show' && 
            ($noteableType == 'trainings' && $noteable->student->id === auth()->user()->id) ||
            ($noteableType == 'students' && $noteable->id === auth()->user()->id)
        )
        {
            return true;
        }

        return false;
    }
}