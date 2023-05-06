<?php
    namespace CH;
          
    class apiController
    {
    
        public function __construct()
        {
    
        }
        public static function index($data){
            $posts = get_posts( array(
                'author' => $data['id'],
              ) );
            
              if ( empty( $posts ) ) {
                return null;
              }
            
              return $posts[0]->post_title;
        }
    }
    //Make whit Antonella Framework