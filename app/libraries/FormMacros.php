<?php

/*
 * Form macro to include a Font Awesome icon on button.
 * 
 * To use these macros, add "require app_path().'/libraries/FormMacros.php';" to /app/start/global.php.
 * Or add "app_path().'/libraries'" to ClassLoader::addDirectories() in /app/start/global.php.
 * 
 * References:
 *      http://stackoverflow.com/questions/23322989/add-twitter-bootstrap-icon-in-button
 *      https://blog.smalldo.gs/2014/04/laravel-4-html5-input-elements/
 *      http://laravelsnippets.com/snippets/bootstrap-3-form-macros
 *      https://gist.github.com/mnshankar/7253657
 * 
 */

Form::macro('fa_icon_button', function($route, $title, $parameters = array(), $attributes = array())
{
    $icon = array_get($attributes, 'icon');

    $iconTag = $icon ? '<i class="fa '.$icon.'"></i> ' : '';

    if ($icon) unset($attributes['icon']);

    return link_to_route($route, $iconTag.$title, $parameters, $attributes);
});





