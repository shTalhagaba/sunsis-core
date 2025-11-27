<?php

Auth::routes(['verify' => true, 'register' => false]);

Route::get('perspective/impersonate/stop', 'ImpersonateController@stopImpersonate')->name('perspective.support.impersonate.destroy');

Route::group(['middleware' => ['auth', 'is_not_active_user']], function() {
    Route::get('/', 'HomeController@index')->name('home');

});

Route::get('/testp', function(){

    dd('Abort');
//dump(getenv('PERSPECTIVE_DB_HOST'));
//dump(getenv('PERSPECTIVE_DB_NAME'));
//dump(getenv('PERSPECTIVE_DB_USER'));
    dump(\Session::get('configuration')['FOLIO_LOGO_NAME']);
    dump(\Request::root().'/password/reset/');
    echo 'App URL: ' . \Session::get('configuration')['FOLIO_CLIENT_URL'] . '<br>';
    echo 'Storage Directory: ' . config('app.storage_directory') . '<br>';
    echo 'PERSPECTIVE_DB_STORAGE_DIRECTORY: ' . getenv('PERSPECTIVE_DB_STORAGE_DIRECTORY') . '<br>';
});

Route::group(['middleware' => ['auth', 'is_support']], function(){
    Route::group(['prefix' => 'perspective'], function(){
        Route::get('/home', 'SupportController@home')->name('perspective.support.home');
        Route::get('/users/view', 'SupportController@view_users')->name('perspective.support.view_users');
        Route::get('/users/create', 'SupportController@create_user')->name('perspective.support.create_user');
        Route::get('/permissions/view', 'SupportController@view_permissions')->name('perspective.support.view_permissions');
        Route::get('/permissions/create', 'SupportController@createPermission')->name('perspective.support.permissions.create');
        Route::post('/permissions', 'SupportController@storePermission')->name('perspective.support.permissions.store');
        Route::get('/permissions/{permission}/edit', 'SupportController@editPermission')->name('perspective.support.permissions.edit');
        Route::match(['put', 'patch'], '/permissions/{permission}', 'SupportController@updatePermission')->name('perspective.support.permissions.update');
        Route::delete('/permissions/{permission}', 'SupportController@destroyPermission')->name('perspective.support.permissions.destroy');
		Route::get('/licenses/view', 'SupportController@view_licenses')->name('perspective.support.view_licenses');
        Route::post('/licenses', 'SupportController@storeLicense')->name('perspective.support.licenses.store');

        Route::post('/impersonate/{user}', 'ImpersonateController@impersonate')->name('perspective.support.impersonate');

    });
});

Route::group(['middleware' => ['auth', 'is_not_active_user', 'is_not_support']], function() {
    Route::get('/my/cp', 'ProfileController@showChangePassword')->name('change_password.show');
    Route::post('/my/cp', 'ProfileController@updatePassword')->name('change_password.store');

});

Route::group(['middleware' => ['impersonate', 'auth', 'is_not_active_user', 'is_not_support', 'check_first_time_login']], function() {

    Route::get('/render_programme_qualification_tree/{id}', 'Programme\ProgrammesController@renderTree')->name('render_programme_qualification_tree');

    Route::get('/my/profile', 'ProfileController@show')->name('profile.show');
    Route::match(['put', 'patch'], '/my/profile', 'ProfileController@update')->name('profile.update');
    //Route::get('/my/cp', 'ProfileController@showChangePassword')->name('change_password.show');
    //Route::post('/my/cp', 'ProfileController@updatePassword')->name('change_password.store');
    Route::get('/my/logout-other-devices', 'HomeController@showLogoutOtherDevices')->name('logout-other-devices.show');
    Route::post('/my/logout-other-devices', 'HomeController@logoutOtherDevices')->name('logout-other-devices.done');

    Route::get('image-cropper','ImageCropperController@index');
    Route::get('/my/signature','ImageCropperController@manageSignature')->name('signature.manage');
    Route::post('image-cropper/upload','ImageCropperController@upload');
    Route::post('/my/signature/upload','ImageCropperController@uploadSignature')->name('signature.upload');

    Route::get('fullcalendar','CalendarController@index')->name('calendar.show');
    Route::post('fullcalendar/create','CalendarController@create');
    Route::post('fullcalendar/update','CalendarController@update');
    Route::post('fullcalendar/delete','CalendarController@destroy');

    Route::get('programmes/export', 'Programme\ProgrammesController@export')->name('programmes.export');
    Route::resource('programmes', 'Programme\ProgrammesController');
    Route::group(['prefix' => 'programmes', 'namespace' => 'Programme'], function(){
        //Route::get('/export', 'ProgrammesController@export')->name('programmes.export');
        Route::get('/{programme}/training_plans_template/edit', 'ProgrammesController@editTrainingPlan')->name('programmes.training_plans.edit');
        Route::post('/{programme}/training_plans_template', 'ProgrammesController@updateNumberOfTrainingPlans')->name('programmes.update_number_of_training_plans');
        Route::post('/{programme}/training_plans_template/save', 'ProgrammesController@updateTrainingPlan')->name('programmes.training_plans.update');
        Route::post('/{programme}/plans/{id}', 'ProgrammesController@updateTrainingPlanDates')->name('programmes.training_plans.updateDates');
        Route::get('{programme}/qualifications/add', 'ProgrammesController@showAddQualification')->name('programmes.qualifications.add');
        Route::post('{programme}/qualifications/add', 'ProgrammesController@addQualification')->name('programmes.qualifications.save');
        Route::get('{programme}/qualifications/{qualification}/units/create', 'ProgrammeQualificationUnitController@create')->name('programme.qualification.unit.create');
        Route::post('{programme}/qualifications/{qualification}/units/create', 'ProgrammeQualificationUnitController@store')->name('programme.qualification.unit.store');

    });

    Route::group(['prefix' => 'notifications'], function(){
        Route::get('/index', 'NotificationsController@index')->name('notifications.index');
        Route::post('/{notification}/check', 'NotificationsController@check')->name('notifications.check');
        Route::delete('/{notification}', 'NotificationsController@destroy')->name('notifications.destroy');
    });

    Route::group(['prefix' => 'messages', 'namespace' => 'Message'], function(){
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

    Route::group(['prefix' => 'system', 'namespace' => 'User'], function() {
        Route::group(['prefix' => 'rp'], function() {
            Route::get('/', 'RolePermissionController@index')->name('rp.index');
            Route::get('roles/create', 'RolePermissionController@createRole')->name('roles.create');
            Route::post('roles', 'RolePermissionController@storeRole')->name('roles.store');
            Route::get('roles/{role}/edit', 'RolePermissionController@editRole')->name('roles.edit');
            Route::match(['put', 'patch'], 'roles/{role}', 'RolePermissionController@updateRole')->name('roles.update');
            Route::delete('roles/{role}', 'RolePermissionController@destroyRole')->name('roles.destroy');

        }); //rp - roles permissions

        Route::group(['prefix' => 'users'], function() {
            Route::get('/', 'UsersController@index')->name('users.index');
            Route::get('{user}/show', 'UsersController@show')->name('users.show');
            Route::get('create', 'UsersController@create')->name('users.create');
            Route::post('/', 'UsersController@store')->name('users.store');
            Route::get('{user}/edit', 'UsersController@edit')->name('users.edit');
            Route::match(['put', 'patch'], '{user}', 'UsersController@update')->name('users.update');
            Route::get('{user}/manage-access', 'UsersController@manageUserAccess')->name('users.manage-user-access');
            Route::match(['put', 'patch'], '{user}/updateUsername', 'UsersController@updateUsername')->name('users.updateUsername');
			Route::match(['put', 'patch'], '{user}/resetPassword', 'UsersController@resetPassword')->name('users.resetPassword');
            Route::match(['put', 'patch'], '{user}/updateWebAccess', 'UsersController@updateWebAccess')->name('users.updateWebAccess');
            Route::match(['put', 'patch'], '{user}/updatePermissions', 'UsersController@updatePermissions')->name('users.updatePermissions');
            Route::delete('{user}', 'UsersController@destroy')->name('users.destroy');
            Route::get('/export', 'UsersController@export')->name('users.export');
        }); //u - users

        Route::get('logins/successful', 'ViewSystemAccessController@showLogins')->name('logins.successful.index');
        Route::get('logins/failed', 'ViewSystemAccessController@showFailedLogins')->name('logins.failed.index');
    });

    Route::group(['prefix' => 'organisations', 'namespace' => 'Organisation'], function() {
        Route::get('/', 'OrganisationsController@index')->name('organisations.index');

        Route::group(['prefix' => 'employers'], function() {
            Route::get('/', 'OrganisationsController@listEmployers')->name('employers.index');
            Route::get('create', 'OrganisationsController@showCreateEmployerForm')->name('employers.create');
            Route::get('export', 'OrganisationsController@export')->name('employers.export');
            Route::post('/', 'OrganisationsController@storeEmployer')->name('employers.store');
            Route::get('{id}', 'OrganisationsController@showEmployer')->name('organisations.employers.show');
            Route::get('{id}/edit', 'OrganisationsController@editEmployer')->name('employers.edit');
            Route::match(['put', 'patch'], '{id}', 'OrganisationsController@updateEmployer')->name('employers.update');
        }); // employers

        Route::post('{organisation}/locations', 'OrganisationsController@saveLocation')->name('locations.save');
        Route::post('{organisation}/locations/detail', 'OrganisationsController@getLocationDetail')->name('locations.detail');
        Route::post('{organisation}/contacts', 'OrganisationsController@saveCRMContact')->name('organisation_contacts.save');
        Route::post('{organisation}/contacts/detail', 'OrganisationsController@getCRMContactDetail')->name('organisation_contacts.detail');
        Route::delete('{organisation}/locations/{location}', 'OrganisationsController@destroyLocation')->name('locations.destroyLocation');
        Route::get('/locations/{location_id}/detail', 'OrganisationsController@getLocationDetail')->name('location.address');

    });//organisations

    Route::group(['prefix' => 'students', 'namespace' => 'Student'], function() {
        Route::get('/', 'StudentsController@index')->name('students.index');
        Route::get('export', 'StudentsController@export')->name('students.export');
        Route::get('{student}/show', 'StudentsController@show')->name('students.show');
        Route::get('create', 'StudentsController@create')->name('students.create');
        Route::post('/', 'StudentsController@store')->name('students.store');
        Route::get('{student}/edit', 'StudentsController@edit')->name('students.edit');
        Route::get('{student}/manage-access', 'StudentsController@manageAccess')->name('students.manage-access');
        Route::match(['put', 'patch'], '{student}', 'StudentsController@update')->name('students.update');
        Route::match(['put', 'patch'], '{student}/updateStudentUsername', 'StudentsController@updateStudentUsername')->name('students.updateUsername');
        Route::match(['put', 'patch'], '{student}/updateWebAccess', 'StudentsController@updateWebAccess')->name('students.updateWebAccess');
		Route::match(['put', 'patch'], '{student}/resetPassword', 'StudentsController@resetPassword')->name('students.resetPassword');
        Route::delete('{student}', 'StudentsController@destroy')->name('students.destroy');

        Route::get('/training_records', 'TRController@index')->name('students.training.index'); // list training records
        Route::get('/training_records/export', 'TRController@export')->name('students.training.export');

		Route::get('/portfolios/export', 'TRController@exportPortfolios')->name('reports.portfolios.export');

        // enrolment - single enrolment
        Route::get('{student}/enrol/step1', 'EnrolmentController@showEnrolmentStep1')->name('students.singleEnrolment.step1.show');
        Route::post('{student}/enrol/step1', 'EnrolmentController@saveEnrolmentStep1')->name('students.singleEnrolment.step1.store');
        Route::get('{student}/enrol/step2', 'EnrolmentController@showEnrolmentStep2')->name('students.singleEnrolment.step2.show');
        Route::post('{student}/enrol/step2', 'EnrolmentController@saveEnrolmentStep2')->name('students.singleEnrolment.step2.store');
        Route::get('{student}/enrol/step3', 'EnrolmentController@showEnrolmentStep3')->name('students.singleEnrolment.step3.show');
        Route::post('{student}/enrol/step3', 'EnrolmentController@saveEnrolmentStep3')->name('students.singleEnrolment.step3.store');

        Route::get('{student}/training/{training_record}/show', 'TRController@show')->name('students.training.show'); // training show
        Route::get('{student}/training/{training_record}/evidences/create', 'EvidenceController@create')->name('students.training.evidences.create');
        Route::post('{student}/training/{training_record}/evidences', 'EvidenceController@store')->name('students.training.evidences.store');
        Route::get('{student}/training/{training_record}/evidences/{evidence}/resubmit', 'EvidenceController@resubmit')->name('students.training.evidences.resubmit');
        Route::match(['put', 'patch'], '{student}/training/{training_record}/evidences/{evidence}/saveResubmit', 'EvidenceController@saveResubmit')->name('students.training.evidences.saveResubmit');
        Route::get('{student}/training/{training_record}/edit', 'TRController@edit')->name('students.training.edit');
        Route::match(['put', 'patch'], '{student}/training/{training_record}/edit', 'TRController@update')->name('students.training.update');
        Route::get('{student}/training/{training_record}/evidences/{evidence}/assess', 'EvidenceController@assess')->name('students.training.evidences.assess');
        Route::post('{student}/training/{training_record}/evidences/{evidence}/assess', 'EvidenceController@saveAssessment')->name('students.training.evidences.saveAssessment');
        Route::get('{student}/training/{training_record}/evidences/{evidence}/iqa', 'EvidenceController@iqa')->name('students.training.evidences.iqa');
        Route::post('{student}/training/{training_record}/evidences/{evidence}/iqa', 'EvidenceController@saveIqaAssessment')->name('students.training.evidences.saveIqaAssessment');
        Route::get('{student}/training/{training_record}/evidences/{evidence}/mapping', 'EvidenceController@map')->name('students.training.evidences.mapping');
        Route::post('{student}/training/{training_record}/evidences/{evidence}/mapping', 'EvidenceController@saveMapping')->name('students.training.evidences.saveMapping');
        Route::get('{student}/training/{training_record}/portfolio/{portfolio}', 'ProgressController@signoff')->name('students.training.signoffProgress');
        Route::post('{student}/training/{training_record}/portfolio/{portfolio}', 'ProgressController@saveSignoff')->name('students.training.saveSignoffProgress');
        Route::get('{student}/training/{training_record}/evidences/{evidence}/assessors_communication', 'EvidenceController@showAssessorComm')->name('students.training.evidences.assessors_communication');
        Route::post('{student}/training/{training_record}/evidences/{evidence}/assessors_communication', 'EvidenceController@saveAssessorComm')->name('students.training.evidences.save_assessors_communication');
        Route::delete('{student}/training/{training_record}', 'TRController@destroy')->name('students.training.destroy');
        Route::delete('{student}/training/{training_record}/evidences/{evidence}', 'EvidenceController@destroy')->name('students.training.evidences.destroy');
        Route::delete('{student}/training/{training_record}/portfolios/{portfolio}/units/{unit}', 'TRController@deleteUnit')->name('students.training.units.destroy');
        Route::delete('{student}/training/{training_record}/portfolios/{portfolio}/units/{unit}/pcs/{pc}', 'TRController@deletePC')->name('students.training.pcs.destroy');

        Route::get('{student}/training/{training_record}/evidences/{evidence}/show', 'EvidenceController@show')->name('students.training.evidence.show');

        Route::get('{student}/training/{training_record}/training_plans_template/edit', 'TRController@editTrainingPlan')->name('students.training.training_plans.edit');
        Route::post('{student}/training/{training_record}/training_plans_template', 'TRController@updateNumberOfTrainingPlans')->name('students.training.update_number_of_training_plans');
        Route::post('{student}/training/{training_record}/training_plans_template/save', 'TRController@updateTrainingPlan')->name('students.training.training_plans.update');
        Route::post('{student}/training/{training_record}/plans/{id}', 'TRController@updateTrainingPlanDates')->name('students.training.training_plans.updateDates');

	Route::get('{student}/training/{training_record}/{unit}/iqa', 'PortfolioUnitIqaController@showIqaUnitForm')->name('students.training.unit.iqa.show');
        Route::post('{student}/training/{training_record}/{unit}/iqa', 'PortfolioUnitIqaController@storeIqaUnit')->name('students.training.unit.iqa.store');
        Route::get('{student}/training/{training_record}/{unit}/iqa_history', 'PortfolioUnitIqaController@showIqaHistory')->name('students.training.unit.iqa.history');
        Route::post('{student}/training/{training_record}/{unit}/iqa_history', 'PortfolioUnitIqaController@storeIqaUnitReply')->name('students.training.unit.iqa.reply');

 	Route::get('{student}/training/{training_record}/{unit}/eqa', 'PortfolioUnitEqaController@showUnitForEqa')->name('students.training.unit.eqa.show');
        Route::post('{student}/training/{training_record}/{unit}/eqa', 'PortfolioUnitEqaController@storeUnitForEqa')->name('students.training.unit.eqa.store');

	Route::get('{student}/training/{training_record}/otj/create', 'OtjController@create')->name('students.training.otj.create');
        Route::post('{student}/training/{training_record}/otj', 'OtjController@store')->name('students.training.otj.store');
        Route::get('{student}/training/{training_record}/otj/{id}/edit', 'OtjController@edit')->name('students.training.otj.edit');
        Route::match(['put', 'patch'], '{student}/training/{training_record}/otj/{id}', 'OtjController@update')->name('students.training.otj.update');

        Route::get('{student}/training/{training_record}/reviews/create', 'TrainingReviewController@create')->name('students.training.reviews.create');
        Route::post('{student}/training/{training_record}/reviews', 'TrainingReviewController@store')->name('students.training.reviews.store');
        Route::get('{student}/training/{training_record}/reviews/{id}/edit', 'TrainingReviewController@edit')->name('students.training.reviews.edit');
        Route::match(['put', 'patch'], '{student}/training/{training_record}/reviews/{id}', 'TrainingReviewController@update')->name('students.training.reviews.update');

        Route::get('{student}/training/{training_record}/reviews/{review}/form', 'TrainingReviewController@open_review_form')->name('students.training.reviews.open_review_form');
        Route::match(['put', 'patch'], '{student}/training/{training_record}/reviews/{review}/form', 'TrainingReviewController@save_review_form')->name('students.training.reviews.save_review_form');

    });

    Route::group(['prefix' => 'qualifications', 'namespace' => 'Qualification'], function(){
        //Route::get('/load', 'QualificationController@loadQualification')->name('ajax.load.qualifications');
        Route::get('/', 'QualificationController@index')->name('qualifications.index');
        Route::get('create', 'QualificationController@create')->name('qualifications.create');
        Route::post('/', 'QualificationController@store')->name('qualifications.store');
        Route::get('{id}/edit', 'QualificationController@edit')->name('qualifications.edit');
        Route::match(['put', 'patch'], '{id}', 'QualificationController@update')->name('qualifications.update');
        Route::get('{id}', 'QualificationController@show')->name('qualifications.show');
        Route::delete('{qualification}', 'QualificationController@destroy')->name('qualifications.destroy');
        Route::get('{qualification}/units/createMultiple', 'QualificationUnitController@createMultiple')->name('qualifications.units.createMultiple');
        Route::post('{qualification}/units/storeMultiple', 'QualificationUnitController@storeMultiple')->name('qualifications.units.storeMultiple');
        Route::get('{qualification}/units/create', 'QualificationUnitController@create')->name('qualifications.units.create');
        Route::post('{qualification}/units', 'QualificationUnitController@store')->name('qualifications.units.store');
        Route::get('{qualification}/units/{unit}/edit', 'QualificationUnitController@edit')->name('qualifications.units.edit');
        Route::match(['put', 'patch'], '{qualification}/units/{id}', 'QualificationUnitController@update')->name('qualifications.units.update');
        Route::delete('{qualification}/units/{unit}', 'QualificationUnitController@destroy')->name('qualifications.units.destroy');
        Route::get('{qualification}/units/{unit}/pcs/create', 'QualificationPCController@create')->name('qualifications.units.pcs.create');
        Route::post('{qualification}/units/{unit}/pcs', 'QualificationPCController@store')->name('qualifications.units.pcs.store');
        Route::get('{qualification}/units/{unit}/pcs/{pc}/edit', 'QualificationPCController@edit')->name('qualifications.units.pcs.edit');
        Route::match(['put', 'patch'], '{qualification}/units/{unit}/pcs/{id}', 'QualificationPCController@update')->name('qualifications.units.pcs.update');
        Route::delete('{qualification}/units/{unit}/pcs/{pc}', 'QualificationPCController@destroy')->name('qualifications.units.pcs.destroy');
	Route::get('{id}/copy', 'QualificationController@copy')->name('qualifications.copy');
        Route::post('{id}/copy', 'QualificationController@copyAndCreate')->name('qualifications.copyAndCreate');
    }); //qualifications

    Route::get('files/{mediaItem}/download', 'Media\DownloadMediaController@download')->name('files.download');
    Route::get('files/{mediaItem}/play', 'Media\DownloadMediaController@playVideo')->name('files.playVideo');
    Route::get('files/{evidence}/downloadArchive', 'Media\DownloadMediaController@downloadArchive')->name('evidences.downloadArchive');

    Route::post('/getIPGeoLocationFromIPStackDotCom', 'AjaxController@getIPGeoLocationFromIPStackDotCom')->name('getIPGeoLocationFromIPStackDotCom');

    Route::get('{student}/training/{training_record}/portfolios/add', 'Student\AddRemoveTrainingElementsController@showAddPortfolio')->name('students.training.portfolios.show');
    Route::post('{student}/training/{training_record}/portfolios/add', 'Student\AddRemoveTrainingElementsController@addPortfolio')->name('students.training.portfolios.add');

	Route::get('add_remove_training_elements/qualifications/load', 'Student\AddRemoveTrainingElementsController@loadQualification')->name('ajax.load.qualifications');
    Route::get('add_remove_training_elements/{portfolio_id}', 'Student\AddRemoveTrainingElementsController@loadPortfolio')->name('ajax.training.add_remove_elements.show_portfolio');
    Route::delete('add_remove_training_elements/portfolio', 'Student\AddRemoveTrainingElementsController@removePortfolio')->name('ajax.training.add_remove_elements.remove_portfolio');
    Route::post('add_remove_training_elements/portfolio_unit', 'Student\AddRemoveTrainingElementsController@addUnit')->name('ajax.training.add_remove_elements.add_unit');
    Route::delete('add_remove_training_elements/portfolio_unit', 'Student\AddRemoveTrainingElementsController@removeUnit')->name('ajax.training.add_remove_elements.remove_unit');
    Route::post('add_remove_training_elements/pcs', 'Student\AddRemoveTrainingElementsController@addPC')->name('ajax.training.add_remove_elements.add_pc');
    Route::delete('add_remove_training_elements/pcs', 'Student\AddRemoveTrainingElementsController@removePC')->name('ajax.training.add_remove_elements.remove_pc');


    Route::post('tickets/media', 'Media\FileController@storeMedia')->name('support.tickets.storeMedia');
    Route::post('tickets/media/remove', 'Media\FileController@removeMedia')->name('support.tickets.removeMedia');

    Route::post('/training/evidences/media', 'Media\FileController@storeMedia')->name('evidences.storeMedia');
    Route::post('/training/evidences/media/remove', 'Media\FileController@removeMedia')->name('evidences.removeMedia');

    Route::group(['prefix' => 'system'], function(){
        Route::resource('eqa_samples', 'EqaSamples\EqaSampleController');
    });

});
