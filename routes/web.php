<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Section;


Auth::routes();

Route::middleware(['auth'])->prefix('admin')->group(function(){
    Route::get('/', 'AdminController@index')->name('admin-index');

    Route::get('/users', 'UserController@index')->name('users-index')->middleware('can:view, App\User');
    Route::get('/users/{id}/enable', 'UserController@enable')->name('users-enable')->middleware('can:edit,App\User');
    Route::get('/users/{id}/permissions', 'UserController@permissions')->name('users-permissions')->middleware('can:permissions, App\User');
    Route::get('/users/{id}/edit', 'UserController@edit')->name('users-edit')->middleware('can:edit,App\User');
    Route::get('/users/add', 'UserController@add')->name('users-new')->middleware('can:edit,App\User');
    Route::post('/users/save', 'UserController@save')->name('users-save')->middleware('can:edit,App\User');
    Route::get('/users/{id}/delete', 'UserController@delete')->name('users-delete')->middleware('can:edit,App\User');
    Route::get('/users/{id}/permission/{id_perm}/change', 'UserController@changePermissions')->name('users-change-permissions')->middleware('can:permissions, App\User');

    Route::get('/languages', 'LanguageController@index')->name('languages-index')->middleware('can:edit, App\Language');
    Route::get('/languages/add', 'LanguageController@add')->name('languages-new')->middleware('can:edit, App\Language');
    Route::get('/languages/{id}/enable', 'LanguageController@enable')->name('languages-enable')->middleware('can:edit, App\Language');
    Route::get('/languages/{id}/default', 'LanguageController@defaulted')->name('languages-default')->middleware('can:edit, App\Language');
    Route::get('/languages/{id}/edit', 'LanguageController@edit')->name('languages-edit')->middleware('can:edit, App\Language');
    Route::post('/languages/save', 'LanguageController@save')->name('languages-save')->middleware('can:edit, App\Language');
    Route::get('/languages/download', 'LanguageController@download')->name('languages-file-download')->middleware('can:edit, App\Language');
    Route::get('/languages/sections', 'LanguageController@sections')->name('languages-sections');

    Route::get('/sections', 'SectionController@index')->name('sections-index')->middleware('can:edit, App\Section');
    Route::get('/sections/add', 'SectionController@add')->name('sections-new')->middleware('can:edit, App\Section');
    Route::get('/sections/{id}/language/{lang}/toggle', 'SectionController@languageToggle')->name('sections-languages-toggle')->middleware('can:edit, App\Section');
    Route::get('/sections/{id}/toggle', 'SectionController@toggle')->name('sections-toggle')->middleware('can:edit, App\Section');
    Route::get('/sections/{id}/edit', 'SectionController@edit')->name('sections-edit')->middleware('can:edit, App\Section');
    Route::post('/sections/save', 'SectionController@save')->name('sections-save')->middleware('can:edit, App\Section');
    Route::get('/sections/{id}/delete', 'SectionController@delete')->name('sections-delete')->middleware('can:edit,App\Section');
    Route::get('/sections/{id}/freeze', 'SectionController@freeze')->name('sections-freeze')->middleware('can:edit,App\Section');

    Route::get('/comments', 'CommentController@index')->name('comments-index')->middleware('can:read, App\Comment');
    Route::get('/comments-data', 'CommentController@data')->name('comments-data')->middleware('can:read, App\Comment');
    Route::get('/comments/{id}/publish', 'CommentController@toggle')->name('comments-publish')->middleware('can:publish, App\Comment');
    Route::get('/comments/{id}/delete', 'CommentController@delete')->name('comments-delete')->middleware('can:delete, App\Comment');

    Route::get('/articles', 'ArticleController@index')->name('articles-index')->middleware('can:read, App\Article');
    Route::get('/articles-data', 'ArticleController@data')->name('articles-data')->middleware('can:read, App\Article');
    Route::get('/articles/new', 'ArticleController@add')->name('articles-new')->middleware('can:create, App\Article');
    Route::get('/articles/{article}/publish', 'ArticleController@toggle')->name('articles-publish')->middleware('can:publish,article');
    Route::any('/articles/organizer', 'ArticleController@organize')->name('articles-organize')->middleware('can:create, App\Article');
    Route::get('/articles/{article}/up', 'ArticleController@up')->name('articles-up')->middleware('can:create, App\Article');
    Route::get('/articles/{article}/down', 'ArticleController@down')->name('articles-down')->middleware('can:create, App\Article');
    Route::get('/articles/{article}/front', 'ArticleController@front')->name('articles-front')->middleware('can:create, App\Article');
    Route::get('/articles/{article}/orden', 'ArticleController@orden')->name('articles-orden')->middleware('can:create, App\Article');
    Route::get('/articles/{article}/edit', 'ArticleController@edit')->name('articles-edit')->middleware('can:edit,article');

    Route::post('/articles/save', 'ArticleController@save')->name('articles-save')->middleware('can:save, App\Article');
    Route::get('/articles/{article}/delete', 'ArticleController@delete')->name('articles-delete')->middleware('can:delete,article');

    Route::get('/schedule/{day}', 'ProgramScheduleController@index')->where('day','[0-6]')->name('schedule-index')->middleware('can:manage, App\ProgramSchedule');
    Route::get('/schedule/new', 'ProgramScheduleController@add')->name('schedule-new')->middleware('can:manage, App\ProgramSchedule');
    Route::post('/schedule/save', 'ProgramScheduleController@save')->name('schedule-save')->middleware('can:manage, App\ProgramSchedule');
    Route::get('/schedule/{id}/toggle', 'ProgramScheduleController@toggle')->name('schedule-toggle')->middleware('can:manage, App\ProgramSchedule');
    Route::get('/schedule/{id}/edit', 'ProgramScheduleController@edit')->name('schedule-edit')->middleware('can:manage, App\ProgramSchedule');
    Route::get('/schedule/{id}/delete', 'ProgramScheduleController@delete')->name('schedule-delete')->middleware('can:manage, App\ProgramSchedule');

    Route::get('/propaganda/{lang}', 'PropagandaController@index')->where('lang','es|en|fr|pt|eo|it|ar')->name('propaganda-index')->middleware('can:manage, App\Propaganda');
    Route::get('/propaganda/new', 'PropagandaController@add')->name('propaganda-new')->middleware('can:manage, App\Propaganda');
    Route::post('/propaganda/save', 'PropagandaController@save')->name('propaganda-save')->middleware('can:manage, App\Propaganda');
    Route::get('/propaganda/{id}/toggle', 'PropagandaController@toggle')->name('propaganda-toggle')->middleware('can:manage, App\Propaganda');
    Route::get('/propaganda/{id}/edit', 'PropagandaController@edit')->name('propaganda-edit')->middleware('can:manage, App\Propaganda');
    Route::get('/propaganda/{id}/delete', 'PropagandaController@delete')->name('propaganda-delete')->middleware('can:manage, App\Propaganda');
    Route::get('/propaganda/{id}/up', 'PropagandaController@up')->name('propaganda-up')->middleware('can:manage, App\Propaganda');
    Route::get('/propaganda/{id}/down', 'PropagandaController@down')->name('propaganda-down')->middleware('can:manage, App\Propaganda');

    Route::get('/programs/{lang}', 'ProgramController@index')->where('lang','es|en|fr|pt|eo|it|ar')->name('programs-index')->middleware('can:read, App\Podcast');
    Route::get('/programs/new', 'ProgramController@add')->name('programs-new')->middleware('can:manage_programs, App\Podcast');
    Route::post('/programs/save', 'ProgramController@save')->name('programs-save')->middleware('can:manage_programs, App\Podcast');
    Route::get('/programs/{id}/toggle', 'ProgramController@toggle')->name('programs-toggle')->middleware('can:manage_programs, App\Podcast');
    Route::get('/programs/{id}/edit', 'ProgramController@edit')->name('programs-edit')->middleware('can:manage_programs, App\Podcast');
    Route::get('/programs/{id}/delete', 'ProgramController@delete')->name('programs-delete')->middleware('can:manage_programs, App\Podcast');
    Route::get('/programs/{id}/up', 'ProgramController@up')->name('programs-up')->middleware('can:manage_programs, App\Podcast');
    Route::get('/programs/{id}/down', 'ProgramController@down')->name('programs-down')->middleware('can:manage_programs, App\Podcast');

    Route::any('/podcasts/', 'PodcastController@index')->name('podcasts-index')->middleware('can:read, App\Podcast');
    Route::get('/podcasts/new', 'PodcastController@add')->name('podcasts-new')->middleware('can:manage_podcasts, App\Podcast');
    Route::post('/podcasts/save', 'PodcastController@save')->name('podcasts-save')->middleware('can:manage_podcasts, App\Podcast');
    Route::get('/podcasts/{id}/toggle', 'PodcastController@toggle')->name('podcasts-toggle')->middleware('can:manage_podcasts, App\Podcast');
    Route::get('/podcasts/{id}/edit', 'PodcastController@edit')->name('podcasts-edit')->middleware('can:manage_podcasts, App\Podcast');
    Route::get('/podcasts/{id}/delete', 'PodcastController@delete')->name('podcasts-delete')->middleware('can:manage_podcasts, App\Podcast');
    Route::get('/podcasts/orphaned', 'PodcastController@orphaned')->name('podcasts-orphaned')->middleware('can:manage_podcasts,App\Podcast');

    Route::get('/mails', 'CorreoController@index')->name('mails-index')->middleware('can:manage, App\Correo');
    Route::get('/mails/{id}/publish', 'CorreoController@toggle')->name('mails-publish')->middleware('can:manage, App\Correo');
    Route::get('/mails/{id}/delete', 'CorreoController@delete')->where('id', '[0-9]+')->name('mails-delete')->middleware('can:manage, App\Correo');
    Route::get('/mails/filters/add', 'CorreoController@addFilter')->name('mails-add_filter')->middleware('can:manage, App\Correo');
    Route::get('/mails/filters/delete', 'CorreoController@deleteFilter')->name('mails-delete_filter')->middleware('can:manage, App\Correo');

    Route::post('/images/upload', 'ImageController@upload')->name('images-upload');
    Route::post('/images/description', 'ImageController@setDescription')->name('images-description');
    Route::post('/images/{gallery}/reorder', 'ImageController@reOrder')->name('images-reorder');
    Route::post('/images/delete', 'ImageController@delete')->name('images-delete');
    Route::post('/images/{gallery}/upload', 'ImageController@uploadToGallery')->name('images-upload-gallery');
    Route::get('/images/search', 'ImageController@search')->name('images-search');
    Route::post('/images/upload-cropped', 'ImageController@upload_cropped')->name('images-cropped');
    Route::get('/images/cropped-history', 'ImageController@cropped_history')->name('images-cropped-history');

    Route::get('/section/cache', 'SectionController@testCache');

    Route::get('/galleries', 'GalleryController@index')->name('galleries-index')->middleware('can:manage, App\Gallery');
    Route::get('/galleries/new', 'GalleryController@add')->name('galleries-new')->middleware('can:manage, App\Gallery');
    Route::post('/galleries/save', 'GalleryController@save')->name('galleries-save')->middleware('can:manage, App\Gallery');
    Route::get('/galleries/{id}/publish', 'GalleryController@toggle')->name('galleries-publish')->middleware('can:manage, App\Gallery');
    Route::get('/galleries/{id}/edit', 'GalleryController@edit')->where('id', '[0-9]+')->name('galleries-edit')->middleware('can:manage, App\Gallery');
    Route::get('/galleries/{id}/delete', 'GalleryController@delete')->where('id', '[0-9]+')->name('galleries-delete')->middleware('can:manage, App\Gallery');

    Route::get('/bulletin', 'BulletinController@index')->name('bulletin-index')->middleware('can:manage, App\Registro');
    Route::get('/registers-data', 'BulletinController@data')->name('bulletin-data')->middleware('can:manage, App\Registro');
    Route::get('/bulletin/{id}/toggle', 'BulletinController@toggle')->name('bulletin-toggle')->middleware('can:manage, App\Registro');
    Route::get('/bulletin/{id}/delete', 'BulletinController@delete')->name('bulletin-delete')->middleware('can:manage, App\Registro');
    Route::get('/bulletin/unsubscribe/{code}', 'BulletinController@unsubscribe')->name('bulletin-unsubscribe');
    
    Route::get('/ribbon', 'RibbonController@index')->name('ribbon-index')->middleware('can:manage, App\Ribbon');
    Route::get('/ribbon/new', 'RibbonController@add')->name('ribbon-new')->middleware('can:manage, App\Ribbon');
    Route::post('/ribbon/save', 'RibbonController@save')->name('ribbon-save')->middleware('can:manage, App\Ribbon');
    Route::get('/ribbon/{id}/publish', 'RibbonController@toggle')->name('ribbon-publish')->middleware('can:manage, App\Ribbon');
    Route::get('/ribbon/{id}/edit', 'RibbonController@edit')->where('id', '[0-9]+')->name('ribbon-edit')->middleware('can:manage, App\Ribbon');
    Route::get('/ribbon/{id}/delete', 'RibbonController@delete')->where('id', '[0-9]+')->name('ribbon-delete')->middleware('can:manage, App\Ribbon');

});

Route::middleware(['locale'])->group(function(){
    //first all no language routes
    Route::get('/', 'PortalController@home')->name('home');
    Route::get('/de-interes/galerias','PortalController@getGalleries')->name('galleries');
    Route::get('/galerias/{gallery}', 'PortalController@getGallery')->name('gallery');
    Route::get('/static/pdf/{filename}', 'PortalController@getPdf')->name('render-pdf');
    Route::get('/generate/captcha', 'PortalController@generateCaptcha')->name('render-captcha');
    Route::get('/generate/pdf/article/{id}', 'PortalController@getPrint')->name('download-pdf');
    Route::get('/ajax/media', 'PortalController@getMedia')->name('get-media');
    Route::get('/search', 'PortalController@getSearch')->name('get-search');
    Route::get('/audio_en_tiempo_real', 'PortalController@audio_en_tiempo_real')->name('audio_en_tiempo_real');
    Route::feeds();
    Route::post('/mail/send', 'PortalController@mailSend')->name('mail-send');
    Route::post('/comment/send', 'PortalController@commentSend')->name('comment-send');
    Route::post('/bulletin/register', 'PortalController@bulletinRegister')->name('bulletin-register');
    


    //now all language routes, probably the same from above but with language parameter

    Route::feeds('{language}');
    Route::get('/{language}/search', 'PortalController@getSearch')->name('get-search');
    Route::get('/{language}/de-interes/galerias','PortalController@getGalleries')->name('galleries');
    Route::get('/{language}', 'PortalController@home')->name('home');
    Section::routes();
    Section::routes(true);
});


//Route::get('/import/users', 'HomeController@importUsers')->name('users');
//Route::get('/routes', 'HomeController@showRoutes');

