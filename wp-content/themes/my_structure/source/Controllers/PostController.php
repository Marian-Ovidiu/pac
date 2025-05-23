<?php
namespace Controllers;

use Core\Bases\BaseController;

class PostController extends BaseController
{
    public function archive()
    {
        $this->render('archivio-post', ['fields' => 'ciaoooo']);
    }

    public function single()
    {
        global $post;

        $this->render('singolo-post', [
            'post'    => $post,
            'title'   => get_the_title(),
            'content' => apply_filters('the_content', get_the_content()),
            'author'  => get_the_author(),
            'date'    => get_the_date(),
        ]);
    }

}
