<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Lead\ActivityController;
use Webkul\Admin\Http\Controllers\Lead\EmailController;
use Webkul\Admin\Http\Controllers\Lead\LeadController;
use Webkul\Admin\Http\Controllers\Lead\QuoteController;
use Webkul\Admin\Http\Controllers\Lead\TagController;

/**
 * Settings routes.
 */
Route::group(['middleware' => ['admin_locale'], 'prefix' => config('app.admin_path')], function () {
    /**
     * Leads routes.
     */
    Route::controller(LeadController::class)->prefix('leads')->group(function () {
        Route::get('', 'index')->name('admin.leads.index');

        Route::get('create', 'create')->name('admin.leads.create');

        Route::post('create', 'store')->name('admin.leads.store');

        Route::get('view/{id}', 'view')->name('admin.leads.view');

        Route::put('edit/{id}', 'update')->name('admin.leads.update');

        Route::get('search', 'search')->name('admin.leads.search');

        Route::delete('{id}', 'destroy')->name('admin.leads.delete');

        Route::put('mass-update', 'massUpdate')->name('admin.leads.mass_update');

        Route::put('mass-destroy', 'massDestroy')->name('admin.leads.mass_delete');

        Route::get('get/{pipeline_id?}', 'get')->name('admin.leads.get');

        Route::controller(ActivityController::class)->prefix('{id}/activities')->group(function () {
            Route::get('', 'index')->name('admin.leads.activities.index');
        });

        Route::controller(TagController::class)->prefix('{id}/tags')->group(function () {
            Route::post('', 'attach')->name('admin.leads.tags.attach');

            Route::delete('', 'detach')->name('admin.leads.tags.detach');
        });

        Route::controller(EmailController::class)->prefix('{id}/emails')->group(function () {
            Route::post('', 'store')->name('admin.leads.emails.store');

            Route::delete('', 'detach')->name('admin.leads.emails.detach');
        });

        Route::controller(QuoteController::class)->prefix('{id}/quotes')->group(function () {
            Route::delete('{quote_id?}', 'delete')->name('admin.leads.quotes.delete');
        });
    });
});
