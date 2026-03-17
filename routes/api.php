<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\MenuController;
use App\Http\Controllers\api\RolePermissionController;
use App\Http\Controllers\api\DivisionController;
use App\Http\Controllers\api\CompanyController;
use App\Http\Controllers\api\RoleManagementController;
use App\Http\Controllers\api\UserManagementController;
use App\Http\Controllers\api\UserRoleManagementController;
use App\Http\Controllers\api\ApprovalFlowController;
use App\Http\Controllers\api\ApprovalFlowStepController;
use App\Http\Controllers\api\TicketTypeFieldController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware(['jwt.auth'])->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // MENU
    Route::post('/master/menu/getData', [MenuController::class, 'getData']);
    Route::post('/master/menu/submit', [MenuController::class, 'submit']);
    Route::delete('/master/menu/delete/{id}', [MenuController::class, 'delete']);

    // ROLE PERMISSION
    Route::post('/roles-permission/getData', [RolePermissionController::class, 'getData']);
    Route::post('/roles-permission/submit', [RolePermissionController::class, 'submit']);
    Route::delete('/roles-permission/delete/{id}', [RolePermissionController::class, 'delete']);
    Route::post('/roles-permission/delete-bulk', [RolePermissionController::class, 'deleteBulk']);

    // DIVISION
    Route::post('/master/departemen/getData', [DivisionController::class, 'getData']);
    Route::post('/master/departemen/submit', [DivisionController::class, 'submit']);
    Route::delete('/master/departemen/delete/{id}', [DivisionController::class, 'delete']);

    // COMPANY
    Route::post('/master/subsidiary/getData', [CompanyController::class, 'getData']);
    Route::post('/master/subsidiary/submit', [CompanyController::class, 'submit']);
    Route::delete('/master/subsidiary/delete/{id}', [CompanyController::class, 'delete']);

    // ROLE MANAGEMENT
    Route::post('/master/roles/getData', [RoleManagementController::class, 'getData']);
    Route::post('/master/roles/submit', [RoleManagementController::class, 'submit']);
    Route::delete('/master/roles/delete/{id}', [RoleManagementController::class, 'delete']);

    // USER MANAGEMENT
    Route::post('/users/getData', [UserManagementController::class, 'getData']);
    Route::post('/users/submit', [UserManagementController::class, 'submit']);
    Route::delete('/users/delete/{id}', [UserManagementController::class, 'delete']);

    // USER ROLES
    Route::post('/master/user-roles/getData', [UserRoleManagementController::class, 'getData']);
    Route::post('/master/user-roles/submit', [UserRoleManagementController::class, 'submit']);
    Route::delete('/master/user-roles/delete/{id}', [UserRoleManagementController::class, 'delete']);

    // APPROVAL FLOW
    Route::post('/setting/approval-flows/getData', [ApprovalFlowController::class, 'getData']);
    Route::post('/setting/approval-flows/submit', [ApprovalFlowController::class, 'submit']);
    Route::delete('/setting/approval-flows/delete/{id}', [ApprovalFlowController::class, 'delete']);

    // APPROVAL FLOW STEPS
    Route::post('/setting/approval-flows/{flowId}/steps/getData', [ApprovalFlowStepController::class, 'getData']);
    Route::post('/setting/approval-flows/steps/submit', [ApprovalFlowStepController::class, 'submit']);
    Route::delete('/setting/approval-flows/steps/delete/{id}', [ApprovalFlowStepController::class, 'delete']);

    // TICKET TYPE FIELDS
    Route::post('/tickets/type-fields/getData', [TicketTypeFieldController::class, 'getData']);
    Route::post('/tickets/type-fields/submit', [TicketTypeFieldController::class, 'submit']);
    Route::delete('/tickets/type-fields/delete/{id}', [TicketTypeFieldController::class, 'delete']);
});



//
