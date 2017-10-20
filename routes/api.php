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

// Pdf
Route::prefix('storage', function () {
    Route::prefix('pdf', function () {
        Route::get('/{pdf}', 'Documents\StorageController@pdf');
    });
});

/**
 * Routes that requires for user to be authenticated
 */
Route::middleware('auth:api')->group(function () {
    /**
     * Testing
     */
    Route::get('dummy-clients', 'Documents\ClientController@dummy');
    Route::get('dummy-vendors', 'Documents\VendorController@dummy');
    Route::get('dummy-products', 'Documents\ProductController@dummy');

    /**
     * Documents
     */
    // Clients
    Route::post('clients/{uuid}/archive', 'Documents\ClientController@archive');
    Route::post('clients/{uuid}/restore', 'Documents\ClientController@restore');
    Route::post('clients-delete', 'Documents\ClientController@deleteBatch');
    Route::post('clients-restore', 'Documents\ClientController@restoreBatch');
    Route::post('clients-archive', 'Documents\ClientController@archiveBatch');
    Route::post('clients-unarchive', 'Documents\ClientController@unarchiveBatch');
    Route::resource('clients', 'Documents\ClientController');

    // Credits
    Route::post('credits/{uuid}/archive', 'Documents\CreditController@archive');
    Route::post('credits/{uuid}/restore', 'Documents\CreditController@restore');
    Route::post('credits-delete', 'Documents\CreditController@deleteBatch');
    Route::post('credits-restore', 'Documents\CreditController@restoreBatch');
    Route::post('credits-archive', 'Documents\CreditController@archiveBatch');
    Route::post('credits-unarchive', 'Documents\CreditController@unarchiveBatch');
    Route::resource('credits', 'Documents\CreditController');

    // Expenses
    Route::post('expenses/{uuid}/archive', 'Documents\ExpenseController@archive');
    Route::post('expenses/{uuid}/restore', 'Documents\ExpenseController@restore');
    Route::post('expenses-delete', 'Documents\ExpenseController@deleteBatch');
    Route::post('expenses-restore', 'Documents\ExpenseController@restoreBatch');
    Route::post('expenses-archive', 'Documents\ExpenseController@archiveBatch');
    Route::post('expenses-unarchive', 'Documents\ExpenseController@unarchiveBatch');
    Route::resource('expenses', 'Documents\ExpenseController');

    // Expense Categories
    Route::post('expense-categories/{uuid}/archive', 'Documents\ExpenseCategoryController@archive');
    Route::post('expense-categories/{uuid}/restore', 'Documents\ExpenseCategoryController@restore');
    Route::post('expense-categories-delete', 'Documents\ExpenseCategoryController@deleteBatch');
    Route::post('expense-categories-restore', 'Documents\ExpenseCategoryController@restoreBatch');
    Route::post('expense-categories-archive', 'Documents\ExpenseCategoryController@archiveBatch');
    Route::post('expense-categories-unarchive', 'Documents\ExpenseCategoryController@unarchiveBatch');
    Route::resource('expense-categories', 'Documents\ExpenseCategoryController');

    // Products
    Route::post('products/{uuid}/archive', 'Documents\ProductController@archive');
    Route::post('products/{uuid}/restore', 'Documents\ProductController@restore');
    Route::post('products-delete', 'Documents\ProductController@deleteBatch');
    Route::post('products-restore', 'Documents\ProductController@restoreBatch');
    Route::post('products-archive', 'Documents\ProductController@archiveBatch');
    Route::post('products-unarchive', 'Documents\ProductController@unarchiveBatch');
    Route::resource('products', 'Documents\ProductController');

    // Vendors
    Route::post('vendors/{uuid}/archive', 'Documents\VendorController@archive');
    Route::post('vendors/{uuid}/restore', 'Documents\VendorController@restore');
    Route::post('vendors-delete', 'Documents\VendorController@deleteBatch');
    Route::post('vendors-restore', 'Documents\VendorController@restoreBatch');
    Route::post('vendors-archive', 'Documents\VendorController@archiveBatch');
    Route::post('vendors-unarchive', 'Documents\VendorController@unarchiveBatch');
    Route::resource('vendors', 'Documents\VendorController');

    // Payments
    Route::post('payments/{uuid}/archive', 'Documents\PaymentController@archive');
    Route::post('payments/{uuid}/restore', 'Documents\PaymentController@restore');
    Route::post('payments-delete', 'Documents\PaymentController@deleteBatch');
    Route::post('payments-restore', 'Documents\PaymentController@restoreBatch');
    Route::post('payments-archive', 'Documents\PaymentController@archiveBatch');
    Route::post('payments-unarchive', 'Documents\PaymentController@unarchiveBatch');
    Route::resource('payments', 'Documents\PaymentController');

    // Invoices
    Route::post('invoices/{uuid}/archive', 'Documents\InvoiceController@archive');
    Route::post('invoices/{uuid}/restore', 'Documents\InvoiceController@restore');
    Route::post('invoices-delete', 'Documents\InvoiceController@deleteBatch');
    Route::post('invoices-restore', 'Documents\InvoiceController@restoreBatch');
    Route::post('invoices-archive', 'Documents\InvoiceController@archiveBatch');
    Route::post('invoices-unarchive', 'Documents\InvoiceController@unarchiveBatch');
    Route::resource('invoices', 'Documents\InvoiceController');

    // Recurring Invoices
    Route::post('recurring-invoices/{uuid}/archive', 'Documents\RecurringInvoiceController@archive');
    Route::post('recurring-invoices/{uuid}/restore', 'Documents\RecurringInvoiceController@restore');
    Route::post('recurring-invoices-delete', 'Documents\RecurringInvoiceController@deleteBatch');
    Route::post('recurring-invoices-restore', 'Documents\RecurringInvoiceController@restoreBatch');
    Route::post('recurring-invoices-archive', 'Documents\RecurringInvoiceController@archiveBatch');
    Route::post('recurring-invoices-unarchive', 'Documents\RecurringInvoiceController@unarchiveBatch');
    Route::resource('recurring-invoices', 'Documents\RecurringInvoiceController');

    // Quotes
    Route::post('quotes/{uuid}/archive', 'Documents\QuoteController@archive');
    Route::post('quotes/{uuid}/restore', 'Documents\QuoteController@restore');
    Route::post('quotes-delete', 'Documents\QuoteController@deleteBatch');
    Route::post('quotes-restore', 'Documents\QuoteController@restoreBatch');
    Route::post('quotes-archive', 'Documents\QuoteController@archiveBatch');
    Route::post('quotes-unarchive', 'Documents\QuoteController@unarchiveBatch');
    Route::resource('quotes', 'Documents\QuoteController');

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
        Route::post('projects/{uuid}/archive', 'CRM\ProjectController@archive');
        Route::post('projects/{uuid}/restore', 'CRM\ProjectController@restore');
        Route::post('projects-delete', 'CRM\ProjectController@deleteBatch');
        Route::post('projects-restore', 'CRM\ProjectController@restoreBatch');
        Route::post('projects-archive', 'CRM\ProjectController@archiveBatch');
        Route::post('projects-unarchive', 'CRM\ProjectController@unarchiveBatch');
        Route::resource('projects', 'CRM\ProjectController');

        Route::post('projects/{project}/task-lists', 'CRM\ProjectController@storeTaskList');
        Route::put('projects/{project}/task-lists', 'CRM\ProjectController@updateTaskList');
        Route::post('projects/{project}/task-lists/{taskList}/tasks', 'CRM\ProjectController@storeTask');
        Route::put('projects/{project}/task-lists/{taskList}/tasks', 'CRM\ProjectController@updateTask');
    });

    /**
     * User data
     */
    Route::prefix('user')->group(function () {
        Route::post('taskbar', 'Auth\UserController@saveTaskbarState');
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