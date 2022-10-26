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
          'methods' => 'get',
          'callback' => ['OrganizerMedia', 'get_media_data'],
        ) );
    }

    public static function get_media_data( ) {
        $result = [];
        $channel = curl_init();
        
        curl_setopt($channel, CURLOPT_URL, str_replace("https://","http://",get_home_url()).'/wp-json/wp/v2/media');
        curl_setopt($channel, CURLOPT_RETURNTRANSFER, TRUE);
        $responseImages = curl_exec($channel);
        curl_close($channel);
        $images = json_decode( $responseImages );
        $category = $_GET['client'];


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

    public static function is_category( $category_name, $categories_id ){
        return true;
        $categories = get_terms( array(
            'taxonomy' => 'category',
            'hide_empty' => false,
        ) );
        foreach($categories as $category){
            if( in_array($category->term_id, $categories_id ) && $category_name===$category->name ){
                return true;
            }
        }
        return false;
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