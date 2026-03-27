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
use App\Http\Controllers\api\TicketController;
use App\Http\Controllers\api\ApprovalController;
use App\Http\Controllers\api\AdminController;
use App\Http\Controllers\api\WorkerController;
use App\Http\Controllers\api\AttachmentController;
use App\Http\Controllers\api\TicketHistoryController;
use App\Http\Controllers\api\CommentController;
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
    // TICKETS
    Route::post('/tickets/getData', [TicketController::class, 'getData']);

    // APPROVALS
    Route::post('/approvals/getData', [ApprovalController::class, 'getData']);

    // ADMIN TICKETS
    Route::post('/tickets/all/getData', [AdminController::class, 'getAllTickets']);
    Route::post('/tickets/assign/{id}', [AdminController::class, 'assignTicket']);
    Route::post('/tickets/close/{id}', [AdminController::class, 'closeTicket']);

    // WORKER TICKETS
    Route::post('/worker/tickets/my-tickets/getData', [WorkerController::class, 'getMyTickets']);
    Route::post('/worker/tickets/history/getData', [WorkerController::class, 'getTicketHistory']);
    Route::post('/worker/tickets/start-work/{id}', [WorkerController::class, 'startWork']);
    Route::post('/worker/tickets/mark-done/{id}', [WorkerController::class, 'markDone']);

    // ATTACHMENTS
    Route::post('/tickets/{ticketId}/attachments/upload', [AttachmentController::class, 'upload']);
    Route::get('/tickets/{ticketId}/attachments', [AttachmentController::class, 'list']);
    Route::get('/attachments/{attachmentId}/download', [AttachmentController::class, 'download']);
    Route::delete('/attachments/{attachmentId}', [AttachmentController::class, 'delete']);

    // TICKET HISTORIES
    Route::get('/tickets/{ticketId}/histories', [TicketHistoryController::class, 'getHistory']);
    Route::post('/tickets/{id}/submit-for-approval', [TicketController::class, 'submitForApproval']);

    // COMMENTS
    Route::get('/tickets/{ticketId}/comments', [CommentController::class, 'getComments']);
    Route::post('/tickets/{ticketId}/comments', [CommentController::class, 'postComment']);
    Route::delete('/comments/{commentId}', [CommentController::class, 'deleteComment']);
    // TICKET FIELDS
    Route::get('/ticket-fields/{type}', function ($type) {
        $fields = \App\Models\TicketTypeField::where('ticket_type_id', $type)->get();
        $html = '';
        foreach ($fields as $field) {
            $html .= '<div class="form-group"><label>' . $field->field_name . '</label><input type="' . $field->field_type . '" name="' . $field->field_name . '" class="form-control" required></div>';
        }
        return $html;
    });
});



//
