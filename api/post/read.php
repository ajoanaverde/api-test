<?php
// Headers
// onde està o "*" geralmente sao usadas opçoes de segurança como os tokens
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Post.php';

// Instantiate DB and connect
$database = new Database();
$db = $database->connect();

// Instatiate blog post object
$post = new Post($db);

// Blog post query
$result = $post->read();
//get row count
$num = $result->rowCount();
//check if any posts
if ($num > 0) {
    //post array
    $post_arr = array();
    $post_arr['data'] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $post_item = array(
            'id' => $id,
            'title' => $title,
            'body' => html_entity_decode($body),
            'author' => $author,
            'category_id' => $category_id,
            'category_name' => $category_name,
        );

        //push to "data"
        array_push($posts_arr['data'], $post_item);
    }
    // turn into json and output
    echo json_encode($post_arr);
} else {
    // if no posts
    echo json_encode(
        array('message' => 'No posts found')
    );
}
