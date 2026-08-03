<?php
namespace Controllers;

use Core\Bases\BaseController;
use Models\Options\OpzioniGlobaliFields;
use WP_Query;

class PostController extends BaseController
{
    public function archive()
    {
        $paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
        $query = new WP_Query([
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => 9,
            'paged'          => $paged,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);

        $this->render('archivio-post', [
            'fields'   => OpzioniGlobaliFields::get(),
            'posts'    => $query->get_posts(),
            'paged'    => $paged,
            'maxPages' => (int) $query->max_num_pages,
        ]);
    }

    public function single()
    {
        global $post;

        $filteredContent = apply_filters('the_content', get_the_content());
        $wordCount = str_word_count(wp_strip_all_tags($filteredContent));
        $related = get_posts([
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => 2,
            'post__not_in'   => [(int) $post->ID],
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);

        $this->render('singolo-post', [
            'post'            => $post,
            'title'           => get_the_title(),
            'content'         => theme_prepare_article_content($filteredContent),
            'author'          => get_the_author(),
            'date'            => get_the_date(),
            'readingTime'     => max(1, (int) ceil($wordCount / 220)),
            'featuredImageId' => get_post_thumbnail_id($post),
            'categories'      => get_the_category($post->ID),
            'relatedPosts'    => $related,
        ]);
    }
}
