<?php

/**
 * Plugin Name:       Unguessable Images
 * Plugin URI:        https://www.phpdevelopment.ca/unguessable-images
 * Description:       Replace default image filenames with unguessable random filenames
 * Version:           1.0.0
 * Author:            Callum Richards
 * Author URI:        https://www.phpdevelopment.ca/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       unguessable-images
 */


include_once(__DIR__."/options_class.php");


class Unguessable_Images_Plugin {

  function __construct() {
    add_filter("wp_handle_upload_prefilter",array($this,"wp_handle_upload_prefilter"));
    add_action("init",array($this,"init"));
  }  
  
  static function install() {
    $op['number_of_bytes']=16;
    if(false === get_option('unguessable_images_settings'))add_option('unguessable_images_settings', $op);
  }
  function init() {

  $options = new PD_Options_Page("Unguessable Images","Unguessable Images","unguessable_settings");

  
  $fields = array(
      'number_of_bytes'=>array(
         'type'=>'number',
         'min'=>3,
         'max'=>128,       
          'label'=>__("Number of bytes to generate (Default 16)","unguessable-images"),
      ),
      'use_original_filename'=>array(
          'type'=>'checkbox',
          'label'=>__("Use the original filename as well as the random bytes","unguessable-images"),
      ),
      'max_num_chars_from_original_filename'=>array(
         'type'=>'number',
         'min'=>0,
         'max'=>128,                    
          "label"=>__("Limit # characters from original file name to display (0 for no limit)","unguessable-images")
      ),
      'prefix'=>array(
          'label'=>__('Prefix filename',"unguessable-images"),
      ),
      'suffix'=>array(
          'label'=>__('Suffix filename',"unguessable-images"),          
      ),
      'connector_string'=>array(
          'label'=>__('Connector String: Characters between original file name and random bytes',"unguessable-images"),          
      ),
      
  );

  $section1 = new PD_Options_Page_Section();
  $section1->setting_name = "unguessable_images_settings";
  $section1->section_intro="";
  $section1->title = __( 'Unguessable Images Settings', 'unguessable-images' );
  $section1->id='unguessable_images_section1';
  $section1->fields = $fields;
  
  $options->sections[]=$section1;
  
    
  }
  
  
  function wp_handle_upload_prefilter($file) {


    $path_info = pathinfo($file['name']);
    $filename = $path_info['filename'];  
    $ext = $path_info['extension'];

    $unguessable_urls_setings = get_option("unguessable_images_settings");

    $number_of_bytes = $unguessable_urls_setings['number_of_bytes'];
    $use_original_filename = $unguessable_urls_setings['use_original_filename'];
    $max_num_chars_from_original_filename = $unguessable_urls_setings['max_num_chars_from_original_filename'];
    $prefix = $unguessable_urls_setings['prefix'];
    $suffix = $unguessable_urls_setings['suffix'];
    $connector_string = $unguessable_urls_setings['connector_string'];



    if(!$use_original_filename) {
      $filename ="";
    }
    else {
      if($max_num_chars_from_original_filename>0) {
        $filename = substr(0,$max_num_chars_from_original_filename,$filename);
      }
      else {
        // do nothing, just use full filename.
      }

    }

    $number_of_bytes = intval($number_of_bytes);
    if(!$number_of_bytes) {
      $number_of_bytes = 16; // default
    }
    if($number_of_bytes>128) { // is already excessive!
      $number_of_bytes = 128;
    }
    if($number_of_bytes<3) {
      $number_of_bytes = 3;
    }


    if(function_exists("random_bytes")) {
    $token = bin2hex(random_bytes($number_of_bytes)); // Fallback 1
    $strong = true;
    }
    else {
    $strong = false;
    $token = bin2hex(openssl_random_pseudo_bytes($number_of_bytes,$strong));

    }

    if(!$strong) {
      $token = uniqid(mt_rand(), true); // fallback 2
    }

    $file['name']= $prefix. $filename. $connector_string .$token. $suffix.".".$ext;

    return $file;

  }

}

register_activation_hook( __FILE__, array('Unguessable_Images_Plugin','install') );

new Unguessable_Images_Plugin();