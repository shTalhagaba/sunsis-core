<?php

use Controllers\ContractController;
use Controllers\CourseController;
use Controllers\EmployerController;
use Controllers\LearnerController;
use Controllers\ProgrammeController;
use Controllers\ProviderController;
use Controllers\UserController;

return [
    'GET' => [
        '/learners' => [LearnerController::class, 'index'],
        '/learners/(\d+)' => [LearnerController::class, 'show'],
        '/employers' => [EmployerController::class, 'index'],
        '/employers/(\d+)' => [EmployerController::class, 'show'],
        '/providers' => [ProviderController::class, 'index'],
        '/providers/(\d+)' => [ProviderController::class, 'show'],
        '/programmes' => [ProgrammeController::class, 'index'],
        '/programmes/(\d+)' => [ProgrammeController::class, 'show'],
        '/courses' => [CourseController::class, 'index'],
        '/courses/(\d+)' => [CourseController::class, 'show'],
        '/contracts' => [ContractController::class, 'index'],
        '/contracts/(\d+)' => [ContractController::class, 'show'],
        '/users' => [UserController::class, 'index'],
        '/users/(\d+)' => [UserController::class, 'show'],
    ],
    'POST' => [
        '/authentication' => 'authenticate',
        '/learners' => [LearnerController::class, 'store'],
    ]
];
