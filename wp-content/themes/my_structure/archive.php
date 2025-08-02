<?php
if (is_home()) {
    var_dump('diocane');die();
    \Controllers\PostController::call('archive');
}
if (is_single()) {
    \Controllers\PostController::call('single');
}
