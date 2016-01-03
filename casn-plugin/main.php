<?php
/*
Plugin Name: fg-plugin - A plugin for rendering results of SPARQL queries
Version: 0.2
Description: display results of dynamic SPARQL queries
Author: Hans-Gert Graebe
Author URI: http://bis.informatik.uni-leipzig.de/HansGertGraebe
Last Update: 2016-01-03
*/

include_once("tagungsankuendigungen.php");
include_once("arbeitsgruppen.php");
include_once("dissertationen.php");
include_once("buchliste.php");

add_action( 'init', 'register_this_style' );
add_action( 'wp_enqueue_scripts', 'enqueue_this_style' );

/**
 * Register style sheet.
 */
function register_this_style() {
  wp_register_style( 'fg-plugin', plugins_url( 'fg-plugin/styles.css' ) );
}

/**
 * enqueue style sheet.
 */
function enqueue_this_style() {
  wp_enqueue_style( 'fg-plugin' );
  //echo plugins_url( 'fg-plugin/styles.css' );
}

?>
