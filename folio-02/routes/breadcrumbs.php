<?php

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'), ['icon' => '<i class="ace-icon fa fa-home home-icon"></i>']);
});

// Home > Chnage Password
Breadcrumbs::for('change_password.show', function ($trail) {
    $trail->parent('home');
    $trail->push('Change Password', route('change_password.show'));
});

// Home > Logout from other devices
Breadcrumbs::for('logout-other-devices.show', function ($trail) {
    $trail->parent('home');
    $trail->push('Logout from other devices', route('logout-other-devices.show'));
});

// Home > Profile
Breadcrumbs::for('profile.show', function ($trail) {
    $trail->parent('home');
    $trail->push('Your Profile', route('profile.show'));
});

// Home > Signature
Breadcrumbs::for('signature.manage', function ($trail) {
    $trail->parent('home');
    $trail->push('Your Signature', route('signature.manage'));
});

// Home > View Successful Logins
Breadcrumbs::for('logins.successful.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Successful Logins', route('logins.successful.index'));
});

// Home > View Successful Logins
Breadcrumbs::for('logins.failed.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Failed Logins Attempts', route('logins.failed.index'));
});

// Home > Users
Breadcrumbs::for('users.index', function ($trail) {
    $trail->parent('home');
    $trail->push('System Users', route('users.index'), ['icon' => '<i class="ace-icon fa fa-users"></i>']);
});

// Home > Users > Create
Breadcrumbs::for('users.create', function ($trail) {
    $trail->parent('users.index');
    $trail->push('Create', route('users.create'), ['icon' => '<i class="ace-icon fa fa-user-plus"></i>']);
});

// Home > Users > [User]
Breadcrumbs::for('users.show', function ($trail, $user) {
    $trail->parent('users.index');
    $trail->push($user->full_name, route('users.show', $user));
});

// Home > Users > [User] > Edit
Breadcrumbs::for('users.edit', function ($trail, $user) {
    $trail->parent('users.show', $user);
    $trail->push('Edit', route('users.edit', $user), ['icon' => '<i class="ace-icon fa fa-edit"></i>']);
});

// Home > Users > [User] > Manage Access
Breadcrumbs::for('users.manage-user-access', function ($trail, $user) {
    $trail->parent('users.show', $user);
    $trail->push('Manage Access', route('users.manage-user-access', $user));
});

// Home > Students
Breadcrumbs::for('students.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Students', route('students.index'), ['icon' => '<i class="ace-icon fa fa-users"></i>']);
});

// Home > Students > Create
Breadcrumbs::for('students.create', function ($trail) {
    $trail->parent('students.index');
    $trail->push('Create', route('students.create'), ['icon' => '<i class="ace-icon fa fa-user-plus"></i>']);
});

// Home > Students > [Student Name]
Breadcrumbs::for('students.show', function ($trail, $student) {
    $trail->parent('students.index');
    $trail->push($student->firstnames, route('students.show', $student), ['icon' => '<i class="ace-icon fa fa-user"></i>']);
});

// Home > Students > [Student Name] > Edit
Breadcrumbs::for('students.edit', function ($trail, $student) {
    $trail->parent('students.show', $student);
    $trail->push('Edit', route('students.edit', $student), ['icon' => '<i class="ace-icon fa fa-edit"></i>']);
});

// Home > Students > [Student] > Manage Access
Breadcrumbs::for('students.manage-access', function ($trail, $student) {
    $trail->parent('students.show', $student);
    $trail->push('Manage Access', route('students.manage-access', $student));
});


// Home > Students > [Student Name] > Enrol - Step 1
Breadcrumbs::for('students.singleEnrolment.step1.show', function ($trail, $student) {
    $trail->parent('students.show', $student);
    $trail->push('Single Enrolment - Step 1', route('students.singleEnrolment.step1.show', $student));
});

// Home > Students > [Student Name] > Enrol - Step 1 > Enrol - Step 2
Breadcrumbs::for('students.singleEnrolment.step2.show', function ($trail, $student) {
    $trail->parent('students.singleEnrolment.step1.show', $student);
    $trail->push('Step 2', route('students.singleEnrolment.step2.show', $student));
});

// Home > Students > [Student Name] > Enrol - Step 1 > Enrol - Step 2 > Enrol Step 3
Breadcrumbs::for('students.singleEnrolment.step3.show', function ($trail, $student) {
    $trail->parent('students.singleEnrolment.step2.show', $student);
    $trail->push('Step 3', route('students.singleEnrolment.step3.show', $student));
});

// Home > Training Records
Breadcrumbs::for('trainings.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Training Records', route('trainings.index'), ['icon' => '<i class="ace-icon fa fa-graduation-cap"></i>']);
});

// Home > Students > [Student Name] > Training Record
Breadcrumbs::for('trainings.show', function ($trail, $student, $training_record) {
    $trail->parent('students.show', $student);
    $trail->push('Training Record', route('trainings.show', $training_record), ['icon' => '<i class="ace-icon fa fa-graduation-cap"></i>']);
});

// Home > Students > [Student Name] > Training Record > Create Evidence
Breadcrumbs::for('trainings.evidences.create', function ($trail, $student, $training_record) {
    $trail->parent('trainings.show', $student, $training_record);
    $trail->push('Create Evidence', route('trainings.evidences.create', [$student, $training_record]), ['icon' => '<i class="ace-icon fa fa-file-text"></i>']);
});

// Home > Students > [Student Name] > Training Record > Resubmit Evidence
Breadcrumbs::for('trainings.evidences.resubmit', function ($trail, $student, $training_record, $evidence) {
    $trail->parent('trainings.show', $student, $training_record);
    $trail->push('Resubmit Evidence', route('trainings.evidences.resubmit', [$student, $training_record, $evidence]), ['icon' => '<i class="ace-icon fa fa-file-text"></i>']);
});

// Home > Students > [Student Name] > Training Record > Assess Evidence
Breadcrumbs::for('trainings.evidences.assess', function ($trail, $student, $training_record, $evidence) {
    $trail->parent('trainings.show', $student, $training_record);
    $trail->push('Assess Evidence', route('trainings.evidences.assess', [$student, $training_record, $evidence]), ['icon' => '<i class="ace-icon fa fa-file-text"></i>']);
});

// Home > Students > [Student Name] > Training Record > IQA Evidence
Breadcrumbs::for('trainings.evidences.iqa', function ($trail, $student, $training_record, $evidence) {
    $trail->parent('trainings.show', $student, $training_record);
    $trail->push('IQA Evidence', route('trainings.evidences.iqa', [$student, $training_record, $evidence]), ['icon' => '<i class="ace-icon fa fa-file-text"></i>']);
});

// Home > Students > [Student Name] > Training Record > Map Evidence
Breadcrumbs::for('trainings.evidences.mapping', function ($trail, $student, $training_record, $evidence) {
    $trail->parent('trainings.show', $student, $training_record);
    $trail->push('Map Evidence', route('trainings.evidences.mapping', [$student, $training_record, $evidence]), ['icon' => '<i class="ace-icon fa fa-file-text"></i>']);
});

// Home > Students > [Student Name] > Training Record > Signoff Progress
Breadcrumbs::for('trainings.signoffProgress', function ($trail, $student, $training_record, $portfolio) {
    $trail->parent('trainings.show', $student, $training_record);
    $trail->push('Signoff Progress', route('trainings.signoffProgress', [$student, $training_record, $portfolio]));
});

// Home > Students > [Student Name] > Training Record > Add Portfolio
Breadcrumbs::for('trainings.portfolios.show', function ($trail, $student, $training_record) {
    $trail->parent('trainings.show', $student, $training_record);
    $trail->push('Add Portfolio', route('trainings.portfolios.show', [$student, $training_record]));
});

// Home > Students > [Student Name] > Training Record > Training plans
Breadcrumbs::for('trainings.training_plans.edit', function ($trail, $student, $training_record) {
    $trail->parent('trainings.show', $student, $training_record);
    $trail->push('Training Plans', route('trainings.training_plans.edit', [$student, $training_record]));
});

// Home > Students > [Student Name] > Training Record > Edit
Breadcrumbs::for('trainings.edit', function ($trail, $student, $training_record) {
    $trail->parent('trainings.show', $student, $training_record);
    $trail->push('Edit Training Record', route('trainings.edit', [$student, $training_record]));
});

// Home > Employers
Breadcrumbs::for('employers.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Employers', route('employers.index'), ['icon' => '<i class="ace-icon fa fa-building"></i>']);
});

// Home > Employers > Create
Breadcrumbs::for('employers.create', function ($trail) {
    $trail->parent('employers.index');
    $trail->push('Create', route('employers.create'));
});

// Home > Employers > [Employer Name]
Breadcrumbs::for('employers.show', function ($trail, $organisation) {
    $trail->parent('employers.index');
    $trail->push($organisation->legal_name, route('employers.show', $organisation));
});

// Home > Employers > [Employer Name] > Edit
Breadcrumbs::for('employers.edit', function ($trail, $organisation) {
    $trail->parent('employers.show', $organisation);
    $trail->push('Edit', route('employers.edit', $organisation), ['icon' => '<i class="ace-icon fa fa-edit"></i>']);
});

// Home > Qualifications
Breadcrumbs::for('qualifications.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Qualifications', route('qualifications.index'));
});

// Home > Qualifications > Create
Breadcrumbs::for('qualifications.create', function ($trail) {
    $trail->parent('qualifications.index');
    $trail->push('Create', route('qualifications.create'));
});

// Home > Qualifications > [Qualification Name]
Breadcrumbs::for('qualifications.show', function ($trail, $qualification) {
    $trail->parent('qualifications.index');
    $trail->push($qualification->qan, route('qualifications.show', $qualification));
});

// Home > Qualifications > [Qualification Name] > Edit
Breadcrumbs::for('qualifications.edit', function ($trail, $qualification) {
    $trail->parent('qualifications.show', $qualification);
    $trail->push('Edit', route('qualifications.edit', $qualification), ['icon' => '<i class="ace-icon fa fa-edit"></i>']);
});

// Home > Qualifications > [Qualification Name] > Create Multiple Units
Breadcrumbs::for('qualifications.units.createMultiple', function ($trail, $qualification) {
    $trail->parent('qualifications.show', $qualification);
    $trail->push('Add Multiple Units', route('qualifications.units.createMultiple', $qualification), ['icon' => '<i class="ace-icon fa fa-plus"></i>']);
});


// Home > Qualifications > [Qualification Name] > Units > Create
Breadcrumbs::for('qualifications.units.create', function ($trail, $qualification) {
    $trail->parent('qualifications.show', $qualification);
    $trail->push('Add/Edit Unit', route('qualifications.units.create', $qualification), ['icon' => '<i class="ace-icon fa fa-edit"></i>']);
});

// Home > Qualifications > [Qualification Name] > Units > Edit
Breadcrumbs::for('qualifications.units.edit', function ($trail, $qualification, $unit) {
    $trail->parent('qualifications.show', $qualification);
    $trail->push('Add/Edit Unit', route('qualifications.units.edit', [$qualification, $unit]), ['icon' => '<i class="ace-icon fa fa-edit"></i>']);
});

// Home > Qualifications > [Qualification Name] > Units > [Unit Name] > Create
Breadcrumbs::for('qualifications.units.pcs.create', function ($trail, $qualification, $unit) {
    $trail->parent('qualifications.show', $qualification);
    $trail->push('Add/Edit PC', route('qualifications.units.pcs.create', [$qualification, $unit]), ['icon' => '<i class="ace-icon fa fa-edit"></i>']);
});

// Home > Qualifications > [Qualification Name] > Units > [Unit Name] > PCs > Edit
Breadcrumbs::for('qualifications.units.pcs.edit', function ($trail, $qualification, $unit, $pc) {
    $trail->parent('qualifications.show', $qualification);
    $trail->push('Add/Edit PC', route('qualifications.units.pcs.edit', [$qualification, $unit, $pc]), ['icon' => '<i class="ace-icon fa fa-edit"></i>']);
});

// Students Login - Home
Breadcrumbs::for('students.home', function ($trail, $student) {
    $trail->push('Home', route('students.home'), ['icon' => '<i class="ace-icon fa fa-home home-icon"></i>']);
});

// Home > Roles & Permissions
Breadcrumbs::for('rp.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Roles & Permissions', route('rp.index'));
});

// Home > Roles & Permissions > Edit
Breadcrumbs::for('roles.edit', function ($trail, $role) {
    $trail->parent('rp.index');
    $trail->push('Edit Role (' . $role->name . ')', route('roles.edit', $role), ['icon' => '<i class="ace-icon fa fa-edit"></i>']);
});

// Home > Messages
Breadcrumbs::for('messages.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Messages', route('messages.index'), ['icon' => '<i class="ace-icon fa fa-inbox"></i>']);
});

// Home > Messages > New Message
Breadcrumbs::for('messages.compose', function ($trail) {
    $trail->parent('messages.index');
    $trail->push('Compose Message', route('messages.compose'), ['icon' => '<i class="ace-icon fa fa-send"></i>']);
});

// Home > Messages > Message
Breadcrumbs::for('messages.show', function ($trail, $message) {
    $trail->parent('messages.index');
    $trail->push('View Message', route('messages.show', $message), ['icon' => '<i class="ace-icon fa fa-envelope-open"></i>']);
});

// Home > Programmes
Breadcrumbs::for('programmes.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Programmes', route('programmes.index'), ['icon' => '<i class="ace-icon fa fa-graduation-cap"></i>']);
});

// Home > Programmes > [Programme]
Breadcrumbs::for('programmes.show', function ($trail, $programme) {
    $trail->parent('programmes.index');
    $trail->push($programme->title, route('programmes.show', $programme));
});

// Home > Programmes > [Programme] > Training plan template
Breadcrumbs::for('programmes.training_plans.update', function ($trail, $programme) {
    $trail->parent('programmes.show', $programme);
    $trail->push('Training Plans Template', route('programmes.training_plans.update', $programme));
});

// Home > Programmes > Create
Breadcrumbs::for('programmes.create', function ($trail) {
    $trail->parent('programmes.index');
    $trail->push('Create', route('programmes.create'), ['icon' => '<i class="ace-icon fa fa-plus"></i>']);
});

// Home > Programmes > [Programme] > Add Qualifications
Breadcrumbs::for('programmes.qualifications.add', function ($trail, $programme) {
    $trail->parent('programmes.show', $programme);
    $trail->push('Add Qualifications', route('programmes.qualifications.add', [$programme]));
});

// Home > Programmes > [Programme] > Edit
Breadcrumbs::for('programmes.edit', function ($trail, $programme) {
    $trail->parent('programmes.show', $programme);
    $trail->push('Edit', route('programmes.edit', $programme), ['icon' => '<i class="ace-icon fa fa-edit"></i>']);
});

// Home > Notifications
Breadcrumbs::for('notifications.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Your Notifications', route('notifications.index'), ['icon' => '<i class="ace-icon fa fa-bell"></i>']);
});

// Home > Events
Breadcrumbs::for('user_events.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Events', route('user_events.index'));
});

// Home > Events > Create
Breadcrumbs::for('user_events.create', function ($trail) {
    $trail->parent('user_events.index');
    $trail->push('Create', route('user_events.create'));
});

// Home > Events > [Event]
Breadcrumbs::for('user_events.show', function ($trail, $event) {
    $trail->parent('user_events.index');
    $trail->push(\Str::limit($event->title, 50), route('user_events.show', $event));
});

// Home > Events > [Event] > Edit
Breadcrumbs::for('user_events.edit', function ($trail, $event) {
    $trail->parent('user_events.show', $event);
    $trail->push('Edit', route('user_events.edit', $event), ['icon' => '<i class="ace-icon fa fa-edit"></i>']);
});

// Home > EQA Samples
Breadcrumbs::for('eqa_samples.index', function ($trail) {
    $trail->parent('home');
    $trail->push('EQA Samples', route('eqa_samples.index'), ['icon' => '<i class="ace-icon fa fa-graduation-cap"></i>']);
});

// Home > EQA Samples > Create
Breadcrumbs::for('eqa_samples.create', function ($trail) {
    $trail->parent('eqa_samples.index');
    $trail->push('Create', route('eqa_samples.create'), ['icon' => '<i class="ace-icon fa fa-plus"></i>']);
});

// Home > EQA Samples > [EQA Sample] > Edit
Breadcrumbs::for('eqa_samples.edit', function ($trail, $sample) {
    $trail->parent('eqa_samples.show', $sample);
    $trail->push('Edit', route('eqa_samples.edit', $sample), ['icon' => '<i class="ace-icon fa fa-edit"></i>']);
});

// Home > EQA Samples > [EQA Sample]
Breadcrumbs::for('eqa_samples.show', function ($trail, $sample) {
    $trail->parent('eqa_samples.index');
    $trail->push($sample->title, route('eqa_samples.show', $sample));
});

// Home > Students > [Student Name] > Training Record > Create OTJH
Breadcrumbs::for('trainings.otj.create', function ($trail, $student, $training_record) {
    $trail->parent('trainings.show', $student, $training_record);
    $trail->push('Create OTJH entry', route('trainings.otj.create', [$student, $training_record]), ['icon' => '<i class="ace-icon fa fa-file-text"></i>']);
});

// Home > Students > [Student Name] > Training Record > Create Review
Breadcrumbs::for('trainings.reviews.create', function ($trail, $student, $training_record) {
    $trail->parent('trainings.show', $student, $training_record);
    $trail->push('Create Review', route('trainings.reviews.create', [$student, $training_record]), ['icon' => '<i class="ace-icon fa fa-file-text"></i>']);
});

// Home > Qualifications > [Qualification Name] > Copy
Breadcrumbs::for('qualifications.copy', function ($trail, $qualification) {
    $trail->parent('qualifications.show', $qualification);
    $trail->push('Copy Qualification', route('qualifications.copy', [$qualification]), ['icon' => '<i class="ace-icon fa fa-copy"></i>']);
});

// Home > Reports Drill Down
Breadcrumbs::for('dashboard.showDrillDown', function ($trail) {
    $trail->parent('home');
    $trail->push('Reports Drilldown', route('dashboard.showDrillDown'));
});