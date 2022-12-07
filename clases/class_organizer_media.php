<?php


class OrganizerMedia {


    public static function active(){}

    public static function deactive(){}
    
    public static function init(){
        add_action( 'rest_api_init', ['OrganizerMedia','register_end_points'] );

        
        add_filter( 'manage_media_columns', ['OrganizerMedia', 'column_filter']);
        //add_filter( 'get_term', ['OrganizerMedia', 'labels_category']);
        self::register_attachment_taxonomy();
        

        add_action( 'add_meta_boxes_attachment', ['OrganizerMedia', 'metaboxes']);
        add_action( 'admin_head', ['OrganizerMedia', 'insert_head'] );
    }

    public static function register_attachment_taxonomy(){
        register_taxonomy( 'clients', ['attachment'], [
            "labels" => [
                "name" => __("Clients","organizer-media"),
                "singular_name" => __("Client","organizer-media"),
                "search_items" => __("Search Clients","organizer-media"),
                "all_items" => __("All Clients","organizer-media"),
                "parent_item" => __("Parent Client","organizer-media"),
                "parent_item_colon" => __("Parent Client:","organizer-media"),
                "edit_item" => __("Edit Client","organizer-media"),
                "update_item" => __("Update Client","organizer-media"),
                "add_new_item" => __("Add New Client","organizer-media"),
                "new_item_name" => __("New Client Name","organizer-media"),
                "menu_name" => __("Clients","organizer-media"),
            ],
            "description" => "",
            "public" => true,
            "publicly_queryable" => true, 
            "hierarchical" => false,
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_rest" => true,
            "sort" => true,
            "show_admin_column" => true,
            "rest_namespace" => "bohiques/v1",
            "rewrite" => array( 'slug' => 'client' ),
        ] );

        register_taxonomy( 'configuration', ['attachment'], [
            "labels" => [
                "name" => __("Configurations","organizer-media"),
                "singular_name" => __("Configuration","organizer-media"),
                "search_items" => __("Search Configurations","organizer-media"),
                "all_items" => __("All Configurations","organizer-media"),
                "parent_item" => __("Parent Configuration","organizer-media"),
                "parent_item_colon" => __("Parent Configuration:","organizer-media"),
                "edit_item" => __("Edit Configuration","organizer-media"),
                "update_item" => __("Update Configuration","organizer-media"),
                "add_new_item" => __("Add New Configuration","organizer-media"),
                "new_item_name" => __("New Configuration Name","organizer-media"),
                "menu_name" => __("Configurations","organizer-media"),
            ],
            "description" => "",
            "public" => true,
            "publicly_queryable" => true, 
            "hierarchical" => false,
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_rest" => true,
            "show_admin_column" => true,
            // "meta_box_cb" => function($item, $a){
                // echo json_encode( $a );
            // },
            "rest_namespace" => "bohiques/v1",
            "rewrite" => array( 'slug' => 'configuration' ),
        ] );
    }

    public static function insert_head(){
        ?>
            <script src="<?= site_url(  ) . '/wp-content/plugins/organizer-media-for-taxonomy/templates/edit-clients.js'  ?>"></script>
        <?php
    }

    public static function metaboxes( $columns ){
        // add_meta_box( 'metabox_attachment', 'mi meta attachment', function(){
        
        // } );
    }
    
    public static function column_filter( $columns ){
        unset($columns['comments']);
        //$columns['clients']='Clientes';
        echo "<script>console.log(".json_encode($columns).")</script>";
        return $columns;
    }

    public static function register_end_points( ) {
        register_rest_route( 'bohiques/v1', 'sliders/(?P<id>\d+)', array(
          'methods' => 'get',
          'callback' => ['OrganizerMedia', 'get_media_data'],
        ) );
    }

    public static function get_media_data( WP_REST_Request $request ) {
        $result = [];
        $channel = curl_init();
        
        curl_setopt($channel, CURLOPT_URL, str_replace("https://","http://",get_home_url()).'/wp-json/wp/v2/media');
        curl_setopt($channel, CURLOPT_RETURNTRANSFER, TRUE);
        $responseImages = curl_exec($channel);
        curl_close($channel);
        $images = json_decode( $responseImages );
        $category_id = $request['id'];


        foreach($images as $image){
            if( in_array( $category_id, $image->clients) ){
                $result[]=[
                    "id" => $image->id,
                    "title" => $image->title->rendered,
                    "type" => $image->media_type,
                    "src" => $image->source_url,
                    "settings" => self::get_configurations( $image->configuration )
               ];
            }
        }
        return $result;
    }

    public static function get_configurations($ids){
        $result = [];
        $terms = get_terms( array(
            'taxonomy' => 'configuration',
            'hide_empty' => false,
        ) );
        foreach($terms as $term){
            if( in_array($term->term_id, $ids, false )){
                $result[$term->name] = json_decode( $term->description );
            }
        }
        return $result;
    }
}