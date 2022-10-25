<?php


class OrganizerMedia {


    public static function active(){}

    public static function deactive(){}
    
    public static function init(){
        add_action( 'rest_api_init', ['OrganizerMedia','register_end_points'] );

        register_taxonomy_for_object_type( 'category', 'attachment' );
        register_taxonomy_for_object_type( 'post_tag', 'attachment' );
    }

    public static function register_end_points( ) {
        register_rest_route( 'medias/v1', '/media', array(
          'methods' => 'GET',
          'callback' => ['OrganizerMedia', 'get_media_data'],
        ) );
    }

    public static function get_media_data( ) {
        $query = get_posts( array(
            'orderby'          => 'date',
            'order'            => 'DESC',
            'include'          => array(),
            'exclude'          => array(),
            'meta_key'         => '',
            'meta_value'       => '',
            'post_type'        => 'attachment',
            'suppress_filters' => true,
        ));
        return $query;
    }
}