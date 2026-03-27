<?php

use App\Http\Controllers\web\AuthController;
use App\Http\Controllers\web\DashboardController;
use App\Http\Controllers\web\MenuController;
use App\Http\Controllers\web\RolePermissionController;
use App\Http\Controllers\web\DivisionController;
use App\Http\Controllers\web\CompanyController;
use App\Http\Controllers\web\RoleManagementController;
use App\Http\Controllers\web\UserManagementController;
use App\Http\Controllers\web\UserRoleManagementController;
use App\Http\Controllers\web\ApprovalFlowController;
use App\Http\Controllers\web\ApprovalFlowStepController;
use App\Http\Controllers\web\TicketTypeFieldController;
use App\Http\Controllers\web\TicketController;
use App\Http\Controllers\web\ApprovalController;
use App\Http\Controllers\web\AdminController;
use App\Http\Controllers\web\WorkerController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [AuthController::class, 'index'])->name('login.index');
Route::get('/auth/login', [AuthController::class, 'index'])->name('auth.login');
Route::get('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/auth/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/auth/save_session', [AuthController::class, 'save_session'])->name('auth.save_session');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

// MENU
Route::get('/master/menu', [MenuController::class, 'index'])->name('master.menu.index');
Route::get('/master/menu/create', [MenuController::class, 'create'])->name('master.menu.create');
Route::get('/master/menu/edit/{id}', [MenuController::class, 'edit'])->name('master.menu.edit');
Route::get('/master/menu/detail/{id}', [MenuController::class, 'detail'])->name('master.menu.detail');

// Roles Permission
Route::get('/roles-permission', [RolePermissionController::class, 'index'])->name('roles_permission.index');
Route::get('/roles-permission/create', [RolePermissionController::class, 'create'])->name('roles_permission.create');
Route::get('/roles-permission/edit/{id}', [RolePermissionController::class, 'edit'])->name('roles_permission.edit');
Route::get('/roles-permission/detail/{id}', [RolePermissionController::class, 'detail'])->name('roles_permission.detail');

// Master Departemen
Route::get('/master/departemen', [DivisionController::class, 'index'])->name('master.departemen.index');
Route::get('/master/departemen/create', [DivisionController::class, 'create'])->name('master.departemen.create');
Route::get('/master/departemen/edit/{id}', [DivisionController::class, 'edit'])->name('master.departemen.edit');
Route::get('/master/departemen/detail/{id}', [DivisionController::class, 'detail'])->name('master.departemen.detail');

// Master Subsidiary
Route::get('/master/subsidiary', [CompanyController::class, 'index'])->name('master.subsidiary.index');
Route::get('/master/subsidiary/create', [CompanyController::class, 'create'])->name('master.subsidiary.create');
Route::get('/master/subsidiary/edit/{id}', [CompanyController::class, 'edit'])->name('master.subsidiary.edit');
Route::get('/master/subsidiary/detail/{id}', [CompanyController::class, 'detail'])->name('master.subsidiary.detail');

// Role Management
Route::get('/master/roles', [RoleManagementController::class, 'index'])->name('master.roles.index');
Route::get('/master/roles/create', [RoleManagementController::class, 'create'])->name('master.roles.create');
Route::get('/master/roles/edit/{id}', [RoleManagementController::class, 'edit'])->name('master.roles.edit');
Route::get('/master/roles/detail/{id}', [RoleManagementController::class, 'detail'])->name('master.roles.detail');

// User Management
Route::get('users', [UserManagementController::class, 'index'])->name('master.users.index');
Route::get('users/create', [UserManagementController::class, 'create'])->name('master.users.create');
Route::get('users/edit/{id}', [UserManagementController::class, 'edit'])->name('master.users.edit');
Route::get('users/detail/{id}', [UserManagementController::class, 'detail'])->name('master.users.detail');

// User Roles management
Route::get('/master/user-roles', [UserRoleManagementController::class, 'index'])->name('master.user_roles.index');
Route::get('/master/user-roles/create', [UserRoleManagementController::class, 'create'])->name('master.user_roles.create');
Route::get('/master/user-roles/edit/{id}', [UserRoleManagementController::class, 'edit'])->name('master.user_roles.edit');
Route::get('/master/user-roles/detail/{id}', [UserRoleManagementController::class, 'detail'])->name('master.user_roles.detail');

// Approval Flow
Route::get('/setting/approval-flows', [ApprovalFlowController::class, 'index'])->name('setting.approval_flows.index');
Route::get('/setting/approval-flows/create', [ApprovalFlowController::class, 'create'])->name('setting.approval_flows.create');
Route::get('/setting/approval-flows/edit/{id}', [ApprovalFlowController::class, 'edit'])->name('setting.approval_flows.edit');
Route::get('/setting/approval-flows/detail/{id}', [ApprovalFlowController::class, 'detail'])->name('setting.approval_flows.detail');

// Approval Flow Steps
Route::get('/setting/approval-flows/{flowId}/steps', [ApprovalFlowStepController::class, 'index'])->name('setting.approval_flow_steps.index');
Route::get('/setting/approval-flows/{flowId}/steps/create', [ApprovalFlowStepController::class, 'create'])->name('setting.approval_flow_steps.create');
Route::get('/setting/approval-flows/{flowId}/steps/edit/{id}', [ApprovalFlowStepController::class, 'edit'])->name('setting.approval_flow_steps.edit');
Route::get('/setting/approval-flows/{flowId}/steps/detail/{id}', [ApprovalFlowStepController::class, 'detail'])->name('setting.approval_flow_steps.detail');

// Ticket type fields
Route::get('/tickets/type-fields', [TicketTypeFieldController::class, 'index'])->name('tickets.type_fields.index');
Route::get('/tickets/type-fields/create', [TicketTypeFieldController::class, 'create'])->name('tickets.type_fields.create');
Route::get('/tickets/type-fields/edit/{id}', [TicketTypeFieldController::class, 'edit'])->name('tickets.type_fields.edit');
Route::get('/tickets/type-fields/detail/{id}', [TicketTypeFieldController::class, 'detail'])->name('tickets.type_fields.detail');

// Tickets
Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
Route::get('/tickets/history', [TicketController::class, 'history'])->name('tickets.history');
Route::get('/tickets/approval', [ApprovalController::class, 'index'])->name('tickets.approval');
Route::get('/tickets/all', [AdminController::class, 'allTickets'])->name('admin.tickets');
Route::post('/tickets/{id}/submit-for-approval', [TicketController::class, 'submitForApproval'])->name('tickets.submit_for_approval');
Route::get('/tickets/{id}', [TicketController::class, 'show'])
    ->where('id', '[0-9]+')->name('tickets.show');
Route::get('/tickets/{id}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
Route::put('/tickets/{id}', [TicketController::class, 'update'])->name('tickets.update');
Route::delete('/tickets/delete/{id}', [TicketController::class, 'destroy'])->name('tickets.delete');
Route::get('/tickets/history', [TicketController::class, 'history'])->name('tickets.history');

// Approval
Route::post('/approval/approve/{id}', [ApprovalController::class, 'approve'])->name('approval.approve');
Route::post('/approval/reject/{id}', [ApprovalController::class, 'reject'])->name('approval.reject');

// Admin
Route::get('/admin/waiting-assignment', [AdminController::class, 'waitingAssignment'])->name('admin.waiting_assignment');
Route::get('/admin/in-progress', [AdminController::class, 'inProgress'])->name('admin.in_progress');
Route::get('/admin/done', [AdminController::class, 'done'])->name('admin.done');
Route::get('/admin/closed', [AdminController::class, 'closed'])->name('admin.closed');
Route::get('/admin/assign/{id}', [AdminController::class, 'assign'])->name('admin.assign.view');
Route::post('/admin/assign/{id}', [AdminController::class, 'assign'])->name('admin.assign');
Route::get('/admin/close/{id}', [AdminController::class, 'close'])->name('admin.close');
Route::get('/admin/worker-management', [AdminController::class, 'workerManagement'])->name('admin.worker_management');

// Worker
Route::get('/tickets/my', [WorkerController::class, 'myTickets'])->name('worker.my_tickets');
Route::post('/worker/start-work/{id}', [WorkerController::class, 'startWork'])->name('worker.start_work');
Route::post('/worker/mark-done/{id}', [WorkerController::class, 'markDone'])->name('worker.mark_done');
Route::post('/worker/update-progress/{id}', [WorkerController::class, 'updateProgress'])->name('worker.update_progress');
Route::get('/worker/ticket-history/{id}', [WorkerController::class, 'ticketHistory'])->name('worker.history');
