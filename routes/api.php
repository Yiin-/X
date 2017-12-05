<?php

// Create a temporary demo account
Route::post('demo', 'Auth\AuthController@demo');

// Register a new account
Route::post('register', 'Auth\AuthController@register');
Route::post('register/validate', 'Auth\AuthController@validateField');

// Login to registered account
Route::post('login', 'Auth\AuthController@login');

// Refresh access token
Route::post('login/refresh', 'Auth\AuthController@refresh');


// Revoke access token
Route::post('logout', 'Auth\AuthController@logout');

// Check if token still valid
Route::post('heartbeat', 'Auth\AuthController@heartbeat');

// Pdf generation
Route::get('pdf-preview', 'Documents\InvoiceController@preview');

if (!function_exists('genDocumentRoutes')) {
    function genDocumentRoutes($name, $controller, Closure $additionalRoutes = null) {
        Route::post($name . '/{uuid}/archive', "{$controller}@archive");
        Route::post($name . '/{uuid}/unarchive', "{$controller}@unarchive");
        Route::post($name . '/{uuid}/restore', "{$controller}@restore");
        Route::post($name . '-delete', "{$controller}@deleteBatch");
        Route::post($name . '-restore', "{$controller}@restoreBatch");
        Route::post($name . '-archive', "{$controller}@archiveBatch");
        Route::post($name . '-unarchive', "{$controller}@unarchiveBatch");
        if (is_callable($additionalRoutes)) {
            $additionalRoutes();
        }
        Route::resource($name, $controller);
    }
}

/**
 * Routes that requires for user to be authenticated
 */
Route::middleware('auth:api')->group(function () {
    /**
     * Auth
     */
    Route::post('unlock', 'Auth\AuthController@unlock');

    /**
     * Documents
     */
    // Clients
    genDocumentRoutes('clients', 'Documents\ClientController');

    // Credits
    genDocumentRoutes('credits', 'Documents\CreditController');

    // Expenses
    genDocumentRoutes('expenses', 'Documents\ExpenseController');

    // Expense Categories
    genDocumentRoutes('expense-categories', 'Documents\ExpenseCategoryController');

    // Products
    genDocumentRoutes('products', 'Documents\ProductController');

    // Vendors
    genDocumentRoutes('vendors', 'Documents\VendorController');

    // Payments
    genDocumentRoutes('payments', 'Documents\PaymentController');

    // Invoices
    genDocumentRoutes('invoices', 'Documents\InvoiceController');

    // Recurring Invoices
    genDocumentRoutes('recurring-invoices', 'Documents\RecurringInvoiceController');

    // Quotes
    genDocumentRoutes('quotes', 'Documents\QuoteController');

    // Employees
    genDocumentRoutes('employees', 'Documents\EmployeeController', function () {
        Route::post('employees/{uuid}/profile-picture', 'Documents\EmployeeController@profilePicture');
    });

    // Tax Rates
    Route::post('tax-rates/{uuid}/restore', 'Documents\TaxRateController@restore');
    Route::resource('tax-rates', 'Documents\TaxRateController');

    /**
     * Features
     */
    Route::prefix('feature')->group(function () {
        // VAT Checker
        Route::get('vat-checker/results', 'Features\VatCheckController@index');
        Route::post('vat-checker/check', 'Features\VatCheckController@check');
    });

    /**
     * CRM
     */
    Route::prefix('crm')->group(function () {
        genDocumentRoutes('projects', 'CRM\ProjectController');

        Route::post('projects/{project}/task-lists', 'CRM\ProjectController@storeTaskList');
        Route::put('projects/{project}/task-lists', 'CRM\ProjectController@updateTaskList');
        Route::post('projects/{project}/task-lists/{taskList}/tasks', 'CRM\ProjectController@storeTask');
        Route::put('projects/{project}/task-lists/{taskList}/tasks', 'CRM\ProjectController@updateTask');
    });

    Route::prefix('documents')->group(function () {
        Route::post('accessible', 'Auth\AuthorizationController@accessibleDocuments');
    });

    /**
     * Company
     */
    Route::prefix('companies')->group(function () {
        Route::post('/', 'Auth\CompanyController@store');
    });

    /**
     * User data
     */
    Route::prefix('user')->group(function () {
        Route::post('taskbar', 'Auth\UserController@saveTaskbarState');
        Route::prefix('state')->group(function () {
            Route::post('company/{uuid}', 'Auth\UserController@saveStateCompany');
        });
    });

    /**
     * Roles and permissions assignment to user
     */
    Route::prefix('authorization')->group(function () {
        // Give/revoke role to/from user
        Route::post('give-role/{user}/{role}', 'Auth\AuthorizationController@giveRole');
        Route::post('revoke-role/{user}/{role}', 'Auth\AuthorizationController@revokeRole');

        // Give/revoke permission to/from role
        Route::post('give-role-permission/{role}', 'Auth\AuthorizationController@givePermissionToRole');
        Route::post('revoke-role-permission/{role}', 'Auth\AuthorizationController@revokePermissionFromRole');

        // Give/revoke permission to/from user
        Route::post('give-user-permission/{user}', 'Auth\AuthorizationController@givePermissionToUser');
        Route::post('revoke-user-permission/{user}', 'Auth\AuthorizationController@revokePermissionFromUser');
    });

    /**
     * Settings
     */
    Route::prefix('settings')->group(function () {
        Route::post('currency', 'Settings\UserSettingsController@changeCurrency');
    });

    /**
     * System
     */
    Route::prefix('system')->group(function () {
        Route::post('activity-log', 'System\ActivityLogController@index');
    });

    /**
     * Static data
     */
    Route::get('static-data', 'Passive\PassiveDataController@all');
});