<?php

if (is_home() || is_post_type_archive('post')) {
    \Controllers\PostController::call('archive');
    return;
}

\Controllers\PageController::call('notFound');
