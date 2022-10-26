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
        register_rest_route( 'bohiques/v1', 'sliders', array(
          'methods' => 'GET',
          'callback' => ['OrganizerMedia', 'get_media_data'],
        ) );
    }

    public static function get_media_data( ) {
        $result = [];
        $channel = curl_init();
        curl_setopt($channel, CURLOPT_URL, 'http://bohiques.loc/wp-json/wp/v2/media');
        //curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($channel, CURLOPT_RETURNTRANSFER, TRUE);
        $responseImages = curl_exec($channel);
        curl_close($channel);
        $images = json_decode( $responseImages );
        $category = '';


        foreach($images as $image){
            if( self::is_category( $category, $image->categories) ){
                $result[]=[
                    "title" => $image->title->rendered,
                    "type" => $image->media_type,
                    "src" => $image->source_url,
                    "orignal_tags" =>$image->tags,
                    "tags" => self::get_tags( $image->tags)
               ];
            }
        }
        return $result;
    }

    public static function is_category( $category, $categories_id ){
        return true;
    }

    public static function get_tags($ids){
        $result = [];
        $terms = get_terms( array(
            'taxonomy' => 'post_tag',
            'hide_empty' => false,
        ) );
        foreach($terms as $term){
            if( in_array($term->term_id, $ids, false )){
                $result[] = $term->name;
            }
        }
        return $result;
    }
}