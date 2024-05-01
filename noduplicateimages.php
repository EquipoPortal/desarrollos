<?php
/*
Plugin Name: Desactivar Generación de Tamaños Adicionales
Plugin URI: https://mendoza.edu.ar
Description: Este plugin desactiva la generación de tamaños adicionales de imágenes al cargarlas en los medios de WordPress.
Version: 1.0
Author: Equipo Portal DGE
Author URI: https://mendoza.edu.ar
License: GPL2
*/

// Función para desactivar la generación de tamaños adicionales de imágenes
function desactivar_generacion_tamanos_adicionales() {
    // Desactivar la generación de miniaturas
    update_option('thumbnail_size_w', 0);
    update_option('thumbnail_size_h', 0);
    update_option('thumbnail_crop', 0);

    // Desactivar la generación de tamaños medio y grande
    update_option('medium_size_w', 0);
    update_option('medium_size_h', 0);
    update_option('large_size_w', 0);
    update_option('large_size_h', 0);

    // Desactivar la generación de tamaño medio grande (medium_large)
    update_option('medium_large_size_w', 0);
    update_option('medium_large_size_h', 0);
}
add_action('init', 'desactivar_generacion_tamanos_adicionales');

// Función para evitar la generación de tamaños adicionales al subir imágenes
function desactivar_generacion_tamanos_al_subir($metadata, $file, $sourceImageType) {
    return false;
}
add_filter('wp_generate_attachment_metadata', 'desactivar_generacion_tamanos_al_subir', 10, 3);

// Función para desactivar la generación de tamaños adicionales al subir imágenes
function desactivar_generacion_de_tamanos($sizes) {
    return array();
}
add_filter('intermediate_image_sizes_advanced', 'desactivar_generacion_de_tamanos');