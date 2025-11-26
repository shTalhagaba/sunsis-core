<?php

Auth::routes([
    'verify' => true,
    'register' => false
]);

Route::group(['middleware' => ['auth', 'is_not_active_user']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/my/cp', 'ProfileController@showChangePassword')->name('change_password.show');
    Route::post('/my/cp', 'ProfileController@updatePassword')->name('change_password.store');
});

Route::group(['prefix' => 'media', 'namespace' => 'Media'], function () {
    Route::post('upload', 'MediaController@upload')->name('media.upload');
    Route::delete('models/{model}/media/{media}/delete', 'MediaController@remove')->name('media.remove');
});


Route::group(['middleware' => ['auth', 'is_not_active_user', 'check_first_time_login']], function () {

    Route::get('login_as', 'Auth\LoginAsController@showLoginAs')->name('login_as.show');
    Route::post('login_as', 'Auth\LoginAsController@executeLoginAs')->name('login_as.execute');

    Route::post('save_account_contact_id', 'SupportTickets\TicketController@saveAccountContactId')->name('tickets.save_account_contact_id');
    Route::resource('tickets', 'SupportTickets\TicketController')->only(['index', 'create', 'show']);

    Route::get('/my/profile', 'ProfileController@show')->name('profile.show');
    Route::match(['put', 'patch'], '/my/profile', 'ProfileController@update')->name('profile.update');
    Route::get('/my/logout-other-devices', 'HomeController@showLogoutOtherDevices')->name('logout-other-devices.show');
    Route::post('/my/logout-other-devices', 'HomeController@logoutOtherDevices')->name('logout-other-devices.done');

    Route::get('image-cropper', 'ImageCropperController@index');
    Route::get('/my/signature', 'ImageCropperController@manageSignature')->name('signature.manage');
    Route::post('image-cropper/upload', 'ImageCropperController@upload');
    Route::post('/my/signature/upload', 'ImageCropperController@uploadSignature')->name('signature.upload');

    Route::group(['prefix' => 'programmes', 'namespace' => 'Programme'], function () {
        Route::get('{programme}/qualifications/manage', 'ProgrammeQualificationController@manageQualifications')->name('programmes.qualifications.manage');
        Route::post('{programme}/qualifications/add', 'ProgrammeQualificationController@add')->name('programmes.qualifications.add');
        Route::post('{programme}/qualifications/save', 'ProgrammeQualificationController@saveManageQualifications')->name('programmes.update_qualifications_details');
        Route::delete('{programme}/qualifications/remove', 'ProgrammeQualificationController@remove')->name('programmes.qualifications.remove');
        Route::get('{programme}/qualifications/{qualification}/export', 'ProgrammeQualificationController@exportSingleQualification')->name('programmes.qualifications.single.export');
        Route::get('{programme}/qualifications/export', 'ProgrammeQualificationController@export')->name('programmes.qualifications.export');
    });

    Route::resource('programmes.qualifications.units', 'Programme\ProgrammeQualificationUnitController')->except(['index']);
    Route::resource('programmes.qualifications.units.pcs', 'Programme\ProgrammeQualificationUnitPcController')->except(['index']);

    Route::get('programmes/export', 'Programme\ProgrammesController@export')->name('programmes.export');
    Route::resource('programmes', 'Programme\ProgrammesController');
    Route::resource('programmes.sessions', 'Programme\ProgrammeSessionController')->except(['index']);
    Route::resource('programmes.sessions.tasks', 'Programme\ProgrammeSessionTaskController')->except(['index']);

    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/index', 'NotificationsController@index')->name('notifications.index');
        Route::post('/{notification}/markAsRead', 'NotificationsController@markAsRead')->name('notifications.markAsRead');
        Route::delete('/{notification}', 'NotificationsController@destroy')->name('notifications.destroy');
    });

    Route::group(['prefix' => 'messages', 'namespace' => 'Message'], function () {
        Route::get('/index', 'MessagesController@index')->name('messages.index');
        Route::get('/compose/{message?}', 'MessagesController@compose')->name('messages.compose');
        Route::post('/', 'MessagesController@send')->name('messages.send');
        Route::get('{message}', 'MessagesController@show')->name('messages.show');
        Route::get('{message}/mark_unread', 'MessagesController@setSingleMessageAsUnread')->name('messages.single.setAsUnread');
        Route::post('{message}/respond', 'MessagesController@respond')->name('messages.respond');
        Route::post('{message}/draft_send', 'MessagesController@draft_send')->name('messages.draft_send');
        Route::get('{message}/mark_read', 'MessagesController@setSingleMessageAsRead')->name('messages.single.setAsRead');
        Route::get('{message}/mark_archive', 'MessagesController@setSingleMessageAsArchive')->name('messages.single.setAsArchive');
        Route::get('{message}/mark_delete', 'MessagesController@setSingleMessageAsDelete')->name('messages.single.setAsDelete');
        Route::post('mark_unread', 'MessagesController@setMultipleMessageAsUnread')->name('messages.multiple.setAsUnread');
        Route::post('mark_read', 'MessagesController@setMultipleMessageAsRead')->name('messages.multiple.setAsRead');
        Route::post('mark_archive', 'MessagesController@setMultipleMessageAsArchive')->name('messages.multiple.setAsArchive');
        Route::post('destroy', 'MessagesController@setMultipleMessageAsDelete')->name('messages.multiple.setAsDelete');
        Route::post('save_as_draft', 'MessagesController@saveAsDraft')->name('message.saveAsDraft');
    });

    Route::post('todo_tasks/{task}/communications', 'Todo\TodoTaskController@storeCommunication', ['parameters' => ['todo_tasks' => 'task']])->name('todo_tasks.communications.store');
    Route::resource('todo_tasks', 'Todo\TodoTaskController', ['parameters' => ['todo_tasks' => 'task']]);

    Route::group(['prefix' => 'system', 'namespace' => 'User'], function () {
        Route::group(['prefix' => 'rp'], function () {
            Route::get('/', 'RolePermissionController@index')->name('rp.index');
            Route::get('roles/create', 'RolePermissionController@createRole')->name('roles.create');
            Route::post('roles', 'RolePermissionController@storeRole')->name('roles.store');
            Route::get('roles/{role}/edit', 'RolePermissionController@editRole')->name('roles.edit');
            Route::match(['put', 'patch'], 'roles/{role}', 'RolePermissionController@updateRole')->name('roles.update');
            Route::delete('roles/{role}', 'RolePermissionController@destroyRole')->name('roles.destroy');
        }); //rp - roles permissions

        Route::group(['prefix' => 'users/manage'], function () {
            Route::get('{user}/manage-access', 'UserAccessController@manageUserAccess')->name('users.manage-user-access');
            Route::match(['put', 'patch'], '{user}/updateUsername', 'UserAccessController@updateUsername')->name('users.updateUsername');
            Route::match(['put', 'patch'], '{user}/resetPassword', 'UserAccessController@resetPassword')->name('users.resetPassword');
            Route::match(['put', 'patch'], '{user}/updateWebAccess', 'UserAccessController@updateWebAccess')->name('users.updateWebAccess');
            Route::match(['put', 'patch'], '{user}/updatePermissions', 'UserAccessController@updatePermissions')->name('users.updatePermissions');
            Route::post('{user}/unlink_account', 'UserAccessController@unlinkAccount')->name('users.unlinkAccount');
            Route::post('{user}/link_account', 'UserAccessController@linkAccount')->name('users.linkAccount');
            Route::post('{employer_user}/unlink_assessor', 'UserAccessController@unlinkAssessor')->name('employer_users.unlinkAssessor');
            Route::post('{employer_user}/link_assessor', 'UserAccessController@linkAssessor')->name('employer_users.linkAssessor');
            Route::post('{user}/unlink_caseload_account', 'UserAccessController@unlinkUserToCaseloadAccount')->name('user.unlinkCaseloadAccount');
            Route::post('{user}/link_caseload_account', 'UserAccessController@linkUserToCaseloadAccount')->name('user.linkCaseloadAccount');
            Route::post('/save-theme-color', 'UserAccessController@saveColorTheme')->name('user.save-theme-color');
        }); //u - users/manage

        Route::get('users/export', 'UsersController@export')->name('users.export');
        Route::resource('users', 'UsersController');

        Route::get('logins/successful', 'ViewSystemAccessController@showLogins')->name('logins.successful.index');
        Route::get('logins/failed', 'ViewSystemAccessController@showFailedLogins')->name('logins.failed.index');
    });

    Route::group(['namespace' => 'Organisation'], function () {
        Route::get('organisations/export', 'EmployerController@export')->name('employers.export');
        Route::resource('organisations/employers', 'EmployerController');
        Route::resource('organisations.locations', 'LocationController');
        Route::resource('organisations.contacts', 'ContactController');

        Route::get('organisations/system_owner', 'OrganisationsController@showSystemOwner')->name('system_owner.show');
    }); //organisations

    Route::get('trainings/portfolios/export', 'Training\TrainingRecordController@exportPortfolios')->name('reports.portfolios.export');

    Route::get('trainings/{training}/update_status', 'Training\Statuses\TrainingRecordStatusController@showUpdate')->name('trainings.statuses.showUpdate');
    Route::match(['PUT', 'PATCH'], 'trainings/{training}/update_status', 'Training\Statuses\TrainingRecordStatusController@storeUpdate')->name('trainings.statuses.storeUpdate');

    Route::post('trainings/sessions/refresh', 'Training\DeliveryPlans\TrainingDeliveryPlanSessionController@refresh')->name('trainings.sessions.refresh');
    Route::get('trainings/{training}/sessions/{session}/view_or_sign', 'Training\DeliveryPlans\TrainingDeliveryPlanSessionController@showViewOrSign')->name('trainings.sessions.show_view_or_sign');
    Route::match(['put', 'patch'], 'trainings/{training}/sessions/{session}/view_or_sign', 'Training\DeliveryPlans\TrainingDeliveryPlanSessionController@saveViewOrSign')->name('trainings.sessions.save_view_or_sign');
    Route::resource('trainings.sessions', 'Training\DeliveryPlans\TrainingDeliveryPlanSessionController')->except(['index']);
    Route::post('trainings/{training}/sessions/{session}/tasks/{task}/save_learner_work', 'Training\DeliveryPlans\TrainingDeliveryPlanSessionTaskController@saveLearnerWork')->name('trainings.sessions.tasks.save_learner_work');
    Route::post('trainings/{training}/sessions/{session}/tasks/{task}/save_assessment', 'Training\DeliveryPlans\TrainingDeliveryPlanSessionTaskController@saveAssessment')->name('trainings.sessions.tasks.save_assessment');
    Route::get('trainings/{training}/sessions/{session}/tasks/{task}/create_evidence', 'Training\TaskEvidenceLinkController@create')->name('trainings.tasks_evidences_link.create');
    Route::post('trainings/{training}/sessions/{session}/tasks/{task}/store_evidence', 'Training\TaskEvidenceLinkController@store')->name('trainings.tasks_evidences_link.store');
    Route::post('trainings/sessions/tasks/refresh', 'Training\DeliveryPlans\TrainingDeliveryPlanSessionTaskController@refresh')->name('trainings.sessions.tasks.refresh');
    Route::resource('trainings.sessions.tasks', 'Training\DeliveryPlans\TrainingDeliveryPlanSessionTaskController')->except(['index']);

    Route::get('trainings/{training}/evidences/{evidence}/assess', 'Training\Evidences\TrainingRecordEvidenceAssessmentController@assess')->name('trainings.evidences.assess');
    Route::post('trainings/{training}/evidences/{evidence}/assess', 'Training\Evidences\TrainingRecordEvidenceAssessmentController@saveAssessment')->name('trainings.evidences.saveAssessment');
    Route::get('trainings/{training}/evidences/{evidence}/validate', 'Training\Evidences\TrainingRecordEvidenceAssessmentController@studentValidation')->name('trainings.evidences.studentValidation');
    Route::post('trainings/{training}/evidences/{evidence}/validate', 'Training\Evidences\TrainingRecordEvidenceAssessmentController@saveStudentValidation')->name('trainings.evidences.saveStudentValidation');
    Route::get('trainings/{training}/evidences/{evidence}/mapping', 'Training\Evidences\TrainingRecordEvidenceController@map')->name('trainings.evidences.mapping');
    Route::post('trainings/{training}/evidences/{evidence}/mapping', 'Training\Evidences\TrainingRecordEvidenceController@saveMapping')->name('trainings.evidences.saveMapping');

    Route::get('trainings/{training}/evidences/{evidence}/assessors_communication', 'Training\Evidences\TrainingRecordEvidenceAssessmentController@showAssessorComm')->name('trainings.evidences.assessors_communication');
    Route::post('trainings/{training}/evidences/{evidence}/assessors_communication', 'Training\Evidences\TrainingRecordEvidenceAssessmentController@saveAssessorComm')->name('trainings.evidences.save_assessors_communication');

    Route::get('trainings/{training}/evidences/{evidence}/iqa', 'Training\Evidences\TrainingRecordEvidenceIqaController@iqa')->name('trainings.evidences.iqa');
    Route::post('trainings/{training}/evidences/{evidence}/iqa', 'Training\Evidences\TrainingRecordEvidenceIqaController@saveIqaAssessment')->name('trainings.evidences.saveIqaAssessment');
    Route::get('trainings/evidences/export', 'Training\Evidences\EvidenceExportController')->name('trainings.evidences.export');
    Route::get('trainings/evidences', 'Training\EvidenceIndexController')->name('trainings.evidences.index');
    Route::resource('trainings.evidences', 'Training\Evidences\TrainingRecordEvidenceController')->except(['index', 'edit', 'update']);

    Route::resource('trainings.four_week_audit', 'IQA\FourWeekAuditController', ['parameters' => ['four_week_audit' => 'audit']]);

    Route::resource('trainings.reviews', 'Training\Reviews\TrainingRecordReviewController');
    Route::resource('trainings.als_reviews', 'Training\AlsReview\AlsReviewController');
    Route::resource('trainings.als_assessment', 'Training\AlsAssessmentPlanController');
    Route::match(['PUT', 'PATCH'], 'trainings/{training}/als_assessment/{alsAssessment}/save-form', 'Training\AlsAssessmentPlanController@saveForm')->name('trainings.als_assessment.save_form');

    Route::resource('fs_courses.questions', 'FSAssessment\FSQuestionController');
    Route::resource('fs_courses', 'FSAssessment\FSCourseController');

    Route::post('trainings/{training}/fs_tests/{fsTest}/save_test', 'FSAssessment\FSTestSessionController@saveTest')->name('trainings.fs_tests.save_test');
    Route::match(['PUT', 'PATCH'], 'trainings/{training}/fs_tests/{fsTest}/start_test', 'FSAssessment\FSTestSessionController@startTest')->name('trainings.fs_tests.start_test');
    Route::resource('trainings.fs_tests', 'FSAssessment\FSTestSessionController');

    Route::resource('otla', 'OTLA\OTLAController');

    Route::get('integration/sunesis/search_learner', 'Integration\Sunesis\SunesisDirectController@showFetchLearnerForm')->name('sunesis.showFetchLearnerForm');
    Route::post('integration/sunesis/search_learner', 'Integration\Sunesis\SunesisDirectController@searchLearners')->name('sunesis.searchLearners');
    Route::post('integration/sunesis/fetch_learner', 'Integration\Sunesis\SunesisDirectController@fetchLearner')->name('sunesis.fetchLearner');

    Route::get('trainings/{training}/push_into_sunesis', 'Integration\Sunesis\SunesisController@showPushLearnerForm')->name('sunesis.showPushLearnerForm');
    Route::get('sunesis/fetch_options', 'Integration\Sunesis\SunesisController@fetchOptions')->name('sunesis.fetchOptions');
    Route::post('trainings/{training}/push_into_sunesis', 'Integration\Sunesis\SunesisController@pushLearner')->name('sunesis.pushLearner');

    Route::get('integration/onefile/search_learner', 'Integration\Onefile\OnefileController@showForm')->name('onefile.showFetchLearnerForm');
    Route::post('integration/onefile/search_learner', 'Integration\Onefile\OnefileController@searchLearners')->name('onefile.searchLearners');
    Route::post('integration/onefile/fetch_learner', 'Integration\Onefile\OnefileController@fetchLearner')->name('onefile.fetchLearner');

    Route::get('staff_development_support/{staff_development_support}/sign', 'StaffDevelopment\StaffDevelopmentSupportController@showSupportToSignForm')->name('staff_development_support.showSupportToSignForm');
    Route::match(['PUT', 'PATCH'], 'staff_development_support/{staff_development_support}/sign', 'StaffDevelopment\StaffDevelopmentSupportController@saveSupportToSignForm')->name('staff_development_support.saveSupportToSignForm');
    Route::resource('staff_development_support', 'StaffDevelopment\StaffDevelopmentSupportController');

    Route::resource('trainings.deep_dives', 'Training\DeepDive\DeepDiveController');

    Route::get('trainings/{training}/reviews/{review}/form', 'Training\Reviews\TrainingRecordReviewFormController@show')->name('trainings.reviews.form.show');
    Route::get('trainings/{training}/reviews/{review}/form/export', 'Training\Reviews\TrainingRecordReviewFormController@export')->name('trainings.reviews.form.export');
    Route::post('trainings/{training}/reviews/{review}/employerSignatureEmail', 'Training\Reviews\TrainingRecordReviewFormController@employerSignatureEmail')->name('trainings.reviews.form.employerSignatureEmail');
    Route::match(['put', 'patch'], 'trainings/{training}/reviews/{review}/form', 'Training\Reviews\TrainingRecordReviewFormController@update')->name('trainings.reviews.form.update');

    Route::get('trainings/{training}/units/{unit}/iqa', 'Training\PortfolioUnitIqaController@showUnitIqaForm')->name('trainings.unit.iqa.show');
    Route::post('trainings/{training}/units/{unit}/iqa', 'Training\PortfolioUnitIqaController@storeUnitIqa')->name('trainings.unit.iqa.store');
    Route::get('trainings/{training}/units/{unit}/iqa_history', 'Training\PortfolioUnitIqaController@showUnitIqaReplyForm')->name('trainings.unit.iqa.reply.show');
    Route::post('trainings/{training}/units/{unit}/iqa_history', 'Training\PortfolioUnitIqaController@storeUnitIqaReplyForm')->name('trainings.unit.iqa.reply.store');

    Route::match(['put', 'patch'], 'trainings/{training}/portfolios/{portfolio}/add_elements', 'Training\Portfolios\AddElementToPortfolioController')->name('trainings.portfolios.add_element');
    Route::match(['put', 'patch'], 'trainings/{training}/portfolios/{portfolio}/remove_elements', 'Training\Portfolios\RemoveElementFromPortfolioController')->name('trainings.portfolios.remove_element');
    Route::resource('trainings.portfolios', 'Training\Portfolios\TrainingPortfolioController')->only(['create', 'store', 'edit', 'destroy']);
    Route::get('trainings/export', 'Training\TrainingRecordController@export')->name('trainings.export');
    Route::resource('trainings', 'Training\TrainingRecordController');

    Route::get('/iqa-notes-on-grid/{trainingId}/{portfolioUnitId}', 'AjaxController@getIqaNotes')->name('ajax.iqa.notes');

    Route::group(['namespace' => 'Student'], function () {
        Route::get('students/{student}/manage-access', 'StudentAccessController@manageAccess')->name('students.manage-access');
        Route::post('students/{student}/sendWelcomeEmail', 'StudentAccessController@sendWelcomeEmail')->name('students.sendWelcomeEmail');
        Route::match(['put', 'patch'], 'students/{student}/updateStudentUsername', 'StudentAccessController@updateStudentUsername')->name('students.updateUsername');
        Route::match(['put', 'patch'], 'students/{student}/updateWebAccess', 'StudentAccessController@updateWebAccess')->name('students.updateWebAccess');
        Route::match(['put', 'patch'], 'students/{student}/resetPassword', 'StudentAccessController@resetPassword')->name('students.resetPassword');

        Route::get('students/export', 'StudentsController@export')->name('students.export');
        Route::resource('students', 'StudentsController');
    });

    Route::group(['namespace' => 'Student\Enrolment', 'prefix' => 'enrolment', 'as' => 'students.singleEnrolment.'], function () {
        // enrolment - single enrolment
        Route::get('{student}/step1', 'EnrolmentController@showStep1')->name('step1');
        Route::post('{student}/step1', 'EnrolmentController@postStep1')->name('step1.post');
        Route::get('{student}/step2', 'EnrolmentController@showStep2')->name('step2');
        Route::post('{student}/step2', 'EnrolmentController@postStep2')->name('step2.post');
        Route::get('{student}/review', 'EnrolmentController@review')->name('review');
        Route::post('{student}/confirm', 'EnrolmentController@confirm')->name('confirm');
    });

    Route::group(['namespace' => 'Student'], function () {
        Route::get('trainings/{training}/portfolios/{portfolio}/signoff', 'ProgressController@signoff')->name('trainings.portfolios.signoffProgress');
        Route::post('trainings/{training}/portfolios/{portfolio}/signoff', 'ProgressController@saveSignoff')->name('trainings.portfolios.saveSignoffProgress');
        Route::post('trainings/{training}/portfolios/{portfolio}/cancelPcSignoff', 'ProgressController@cancelPcSignoff')->name('trainings.portfolios.cancelPcSignoff');
        Route::post('trainings/{training}/portfolios/{portfolio}/cancelPcSignoff', 'ProgressController@cancelPcSignoff')->name('trainings.portfolios.cancelPcSignoff');
        // Route::get('trainings/{training}/evidences/{evidence}/assessors_communication', 'EvidenceController@showAssessorComm')->name('trainings.evidences.assessors_communication');
        // Route::post('trainings/{training}/evidences/{evidence}/assessors_communication', 'EvidenceController@saveAssessorComm')->name('trainings.evidences.save_assessors_communication');

        Route::get('trainings/{training}/{unit}/eqa', 'PortfolioUnitEqaController@showUnitForEqa')->name('trainings.unit.eqa.show');
        Route::post('trainings/{training}/{unit}/eqa', 'PortfolioUnitEqaController@storeUnitForEqa')->name('trainings.unit.eqa.store');

        Route::resource('trainings.otj', 'OtjController')->except(['index']);
    });


    Route::get('students/trainings/{training}/updateUnitAssessmentStatus', '\App\Http\Controllers\AjaxController@updateUnitAssessmentStatus')->name('unit.updateUnitAssessmentStatus');

    Route::post('createMediaSection', 'AjaxController@createMediaSection')->name('createMediaSection');

    Route::get('calcualteEndDate', 'AjaxController@calcualteEndDate')->name('calcualteEndDate');

    Route::post('createSelectOption', 'AjaxController@createSelectOption')->name('createSelectOption');

    Route::post('ajax/showAlsTabToEmployer', 'AjaxController@showAlsTabToEmployer')->name('ajax.showAlsTabToEmployer');

    Route::post('ajax/updateTrainingEvidenceIqaCheckStatus', 'AjaxController@updateTrainingEvidenceIqaCheckStatus')->name('ajax.updateTrainingEvidenceIqaCheckStatus');

    Route::get('ajax/showPortfolioUnitHistory', 'AjaxController@showPortfolioUnitHistory')->name('ajax.showPortfolioUnitHistory');

    Route::group(['namespace' => 'Qualification'], function () {
        //Route::get('/load', 'QualificationController@loadQualification')->name('ajax.load.qualifications');

        Route::get('qualifications/{qualification}/copy', 'QualificationController@copy')->name('qualifications.copy');
        Route::post('qualifications/{qualification}/copy', 'QualificationController@copyAndCreate')->name('qualifications.copyAndCreate');
        Route::get('qualifications/export', 'QualificationController@export')->name('qualifications.export');
        Route::get('qualifications/{qualification}/export', 'QualificationController@exportSingleQualification')->name('qualifications.exportSingleQualification');
        Route::resource('qualifications', 'QualificationController');

        Route::resource('download_qualification', 'DownloadQualificationController')->only(['index', 'show', 'store']);

        Route::get('qualifications/{qualification}/units/createMultiple', 'QualificationUnitController@createMultiple')->name('qualifications.units.createMultiple');
        Route::post('qualifications/{qualification}/units/storeMultiple', 'QualificationUnitController@storeMultiple')->name('qualifications.units.storeMultiple');

        Route::resource('qualifications.units', 'QualificationUnitController')->except(['index']);
        Route::resource('qualifications.units.pcs', 'QualificationPCController')->except(['index']);
    }); //qualifications

    Route::get('files/{mediaItem}/download', 'Media\DownloadMediaController@download')->name('files.download');
    Route::get('files/{mediaItem}/play', 'Media\DownloadMediaController@playVideo')->name('files.playVideo');
    Route::get('files/{evidence}/downloadArchive', 'Media\DownloadMediaController@downloadArchive')->name('evidences.downloadArchive');

    Route::post('/getIPGeoLocationFromIPStackDotCom', 'AjaxController@getIPGeoLocationFromIPStackDotCom')->name('getIPGeoLocationFromIPStackDotCom');
    Route::get('/getOrganisationLocation', 'AjaxController@getOrganisationLocation')->name('getOrganisationLocation');
    Route::post('/saveTabInSession', 'AjaxController@saveTabInSession')->name('saveTabInSession');

    Route::post('/training/evidences/media', 'Media\FileController@storeMedia')->name('evidences.storeMedia');
    Route::post('/training/evidences/media/remove', 'Media\FileController@removeMedia')->name('evidences.removeMedia');

    Route::get('fullcalendar', 'UserEvents\UserEventsController@diary')->name('calendar.diary');
    Route::post('events/{event}/updateStatusByParticipant', 'UserEvents\UserEventsController@updateStatusByParticipant')->name('user_events.updateStatusByParticipant');
    Route::post('tasks/{task}/updateStatus', 'UserEvents\UserEventsController@updateStatus')->name('user_tasks.updateStatus');
    Route::match(['put', 'patch'], 'events/{event}/remove_participants', 'UserEvents\UserEventsController@removeParticipant')->name('user_events.remove_participant');
    Route::match(['put', 'patch'], 'events/{event}/add_participants', 'UserEvents\UserEventsController@addParticipant')->name('user_events.add_participant');
    Route::resource('user_events', 'UserEvents\UserEventsController', ['parameters' => ['user_events' => 'event']]);

    Route::group(['prefix' => 'system'], function () {
        Route::resource('eqa_samples', 'EqaSamples\EqaSampleController');
    });

    Route::group(['prefix' => 'tags', 'as' => 'tags.'], function () {
        Route::post('assign', 'Tags\TagController@assign')->name('assign');
        Route::post('remove', 'Tags\TagController@remove')->name('remove');
    });

    Route::get('dashboard/getTrainingRecordStatusByYear', 'DashboardController@getTrainingRecordStatusByYear')->name('home.getTrainingRecordStatusByYear');
    Route::get('dashboard/detail', 'Reports\ViewReportController@showDrillDown')->name('dashboard.showDrillDown');
    Route::get('dashboard/getAssessorActions', 'DashboardController@getAssessorActions')->name('home.getAssessorActions');
    Route::get('dashboard/getVerifierActions', 'DashboardController@getVerifierActions')->name('home.getVerifierActions');

    Route::get('iqa_sample_plans/{plan}/units/manage', 'IQA\IqaSamplePlanController@manageUnits')->name('iqa_sample_plans.units.manage');
    Route::post('iqa_sample_plans/{plan}/units/add', 'IQA\IqaSamplePlanController@updateUnits')->name('iqa_sample_plans.units.update');
    Route::get('iqa_sample_plans/{plan}/trainings/manage', 'IQA\IqaSamplePlanController@manageTrainingRecords')->name('iqa_sample_plans.trainings.manage');
    Route::post('iqa_sample_plans/{plan}/trainings/add', 'IQA\IqaSamplePlanController@updateTrainingRecords')->name('iqa_sample_plans.trainings.update');
    Route::delete('iqa_sample_plans/{plan}/units/{unit}', 'IQA\IqaSamplePlanController@deleteUnit')->name('iqa_sample_plans.units.delete');
    Route::delete('iqa_sample_plans/{plan}/trainings/{training}', 'IQA\IqaSamplePlanController@deleteTraining')->name('iqa_sample_plans.trainings.delete');
    Route::match(['put', 'patch'], 'iqa_sample_plans/{plan}/update_basic', 'IQA\IqaSamplePlanController@updateBasic', ['parameters' => ['iqa_sample_plans' => 'plan']])->name('iqa_sample_plans.update_basic');
    Route::post('iqa_sample_plans/{plan}/add_remove_training_units', 'IQA\IqaSamplePlanController@addRemoveTrainingUnits')->name('iqa_sample_plans.addRemoveTrainingUnits');
    Route::get('iqa_sample_plans/export', 'IQA\IqaSamplePlanController@export')->name('iqa_sample_plans.export');
    Route::resource('iqa_sample_plans', 'IQA\IqaSamplePlanController', ['parameters' => ['iqa_sample_plans' => 'plan']]);

    Route::get('assessor_risk_assessment/{riskAssessment}/form/edit', 'AssessorRiskAssessment\AssessorRiskAssessmentController@editForm')->name('assessor_risk_assessment.editForm');
    Route::match(['PUT', 'PATCH'], 'assessor_risk_assessment/{riskAssessment}/form', 'AssessorRiskAssessment\AssessorRiskAssessmentController@saveForm')->name('assessor_risk_assessment.saveForm');
    Route::resource('assessor_risk_assessment', 'AssessorRiskAssessment\AssessorRiskAssessmentController', ['parameters' => ['assessor_risk_assessment' => 'riskAssessment']]);

    Route::get('iqa_plans/{plan}/plan_single_entry', 'IQA\IqaSamplePlanController@showPlanSingleEntry')->name('iqa_sample_plans.showPlanSingleEntry');
    Route::post('iqa_plans/{plan}/plan_single_entry', 'IQA\IqaSamplePlanController@savePlanSingleEntry')->name('iqa_sample_plans.savePlanSingleEntry');
    Route::post('iqa_plans/{plan}/plan_multi_entry', 'IQA\IqaSamplePlanController@savePlanMultiEntry')->name('iqa_sample_plans.savePlanMultiEntry');
    Route::delete('iqa_plans/{plan}/delete_plan_entry/{entry}', 'IQA\IqaSamplePlanController@deletePlanEntry')->name('iqa_sample_plans.deletePlanEntry');
    Route::resource('iqa_plans', 'IQA\IqaPlanController', ['parameters' => ['iqa_plans' => 'plan']]);

    Route::get('getProgrammeQualificationsForIqaSample', 'AjaxController@getProgrammeQualificationsForIqaSample')->name('getProgrammeQualificationsForIqaSample');
    Route::get('{event}/searchParticipantsForEvent', 'AjaxController@searchParticipantsForEvent')->name('searchParticipantsForEvent');
    Route::get('getProgrammeDeliveryPlanSessionTemplate', 'AjaxController@getProgrammeDeliveryPlanSessionTemplate')->name('getProgrammeDeliveryPlanSessionTemplate');
    Route::get('getProgrammeDeliveryPlanSessionTaskTemplate', 'AjaxController@getProgrammeDeliveryPlanSessionTaskTemplate')->name('getProgrammeDeliveryPlanSessionTaskTemplate');


    Route::group(['prefix' => 'reports'], function () {
        Route::get('dashboard', 'DashboardController@index')->name('reports.dashboard.index');
        Route::get('portfolios/export', 'Reports\PortfoliosSummary\ViewPortfolioSummaryController@export')->name('reports.portfolios.summary.export');
        Route::get('sampling/export', 'Reports\PortfoliosSummary\ViewPortfolioSummaryController@export')->name('reports.sampling.summary.export');
        Route::get('portfolios', 'Reports\PortfoliosSummary\ViewPortfolioSummaryController@index')->name('reports.portfolios.summary');
        Route::get('sampling', 'Reports\PortfoliosSummary\ViewPortfolioSummaryController@index')->name('reports.sampling.summary');
        Route::get('otj/export', 'Reports\Otj\ViewOtjReportController@export')->name('reports.otj.export');
        Route::get('otjh/{training}/export', 'Reports\Otj\ViewOtjReportController@exportOtjh')->name('reports.otjh.export');
        Route::get('otj', 'Reports\Otj\ViewOtjReportController@index')->name('reports.otj');
        Route::get('visit_type', 'Reports\VisitTypeReport\VisitTypeReportController@index')->name('reports.visit_type');
        Route::get('gap-analysis/{training}/{portfolio}', 'Reports\GapAnalysis\ReportController@index')->name('reports.gap-analysis');
    });

    Route::post('learning_resources/{learning_resource}/like', 'LearningResources\LearningResourceController@likeUnlike')->name('learning_resources.users.like');
    Route::post('learning_resources/{learning_resource}/bookmark', 'LearningResources\LearningResourceController@bookUnbookmark')->name('learning_resources.users.bookmark');
    Route::resource('learning_resources', 'LearningResources\LearningResourceController');

    Route::resource('/{noteable_type}/{noteable}/crm_notes', 'CRM\CrmNoteController');

    Route::get('dashboard/getLearnerTasks', 'Student\DashboardController@getLearnerTasks')->name('home.getLearnerTasks');
});

Route::get('review/view_and_sign', 'Training\Reviews\TrainingRecordReviewFormController@showSignatureForm')->name('reviews.showSignatureForm');
Route::match(['put', 'patch'], 'review/view_and_sign', 'Training\Reviews\TrainingRecordReviewFormController@storeSignatureForm')->name('reviews.storeSignatureForm');


Route::get('getVerifierLinkedAssessors/{verifierId}', 'AjaxController@getVerifierLinkedAssessors')->name('getVerifierLinkedAssessors');
Route::get('getVerifierAndAssessorLinkedQualifications', 'AjaxController@getVerifierAndAssessorLinkedQualifications')->name('getVerifierAndAssessorLinkedQualifications');