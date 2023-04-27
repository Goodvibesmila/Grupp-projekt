<?php

/* Plugin Name: CPT plugin butiker */




add_action( 'init', 'butiker');




function butiker(){

$butik_args =

[

'public' => true,

'label' => 'Butik',

'show in rest' => true,

'has_archive' => true,

];


register_post_type('butik', $butik_args);

}




?>