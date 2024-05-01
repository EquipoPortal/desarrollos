<?php
/*
Plugin Name: DGEtable
Description: Plugin para filtros de búsqueda, ordenación y paginación usando JavaScript en WordPress.
Version: 1.0
Author: José
*/

// Agrega scripts y estilos para el filtrado, ordenación y paginación de la tabla
function agregar_scripts_y_estilos_para_tabla() {
    // Registra y añade el script de JavaScript personalizado
    wp_register_script( 'filtrado-ordenacion-paginacion-js', plugins_url( '/js/filtrado-ordenacion-paginacion.js', __FILE__ ), array( 'jquery' ), '1.0', true );
    wp_enqueue_script( 'filtrado-ordenacion-paginacion-js' );

    // Estilos para la tabla
    echo '<style>
            .footable th, .footable td { white-space: normal !important; }
            .footable th { background-color: #f2f2f2; }
            .footable td { max-width: 200px; word-wrap: break-word; }
            .pagination, .search-box { width: 100%; }
            .pagination, .search-box input[type="text"] { margin-top: 10px; }
            .pagination .prev, .pagination .next, .pagination .page-numbers, .pagination .total-pages { padding: 5px 10px; border: 1px solid #ccc; background-color: #f9f9f9; cursor: pointer; margin: 0 2px; }
            .pagination .prev { margin-right: 5px; }
            .pagination .current { background-color: #ccc; }
          </style>';
}
add_action( 'wp_enqueue_scripts', 'agregar_scripts_y_estilos_para_tabla' );

// Función para agregar el campo de búsqueda, paginación y aplicar filtrado, ordenación y paginación a la tabla
function agregar_funcionalidades_a_tabla( $content ) {
    // Verifica si el contenido contiene una tabla con la clase 'footable'
    if ( strpos( $content, 'footable' ) !== false ) {
        // Agrega el script de JavaScript para filtrado, ordenación y paginación
        $content .= '<script>
                        jQuery(document).ready(function($) {
                            $(".footable").each(function() {
                                var $table = $(this);
                                var $searchInput = $("<div class=\'search-box\'><input type=\'text\' class=\'filtro-busqueda\' placeholder=\'🔍 Ingresar una palabra que identifique la información que desea encontrar...\'></div>");
                                $table.before($searchInput);

                                var searchHandler = function() {
                                    var value = $(this).val().toLowerCase();
                                    $table.find("tbody tr").filter(function() {
                                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                                    });
                                };

                                $searchInput.find("input[type=\'text\']").on("keyup", searchHandler);

                                var rowsPerPage = 10;
                                var $tableRows = $table.find("tbody tr");
                                var totalRows = $tableRows.length;
                                var numPages = Math.ceil(totalRows / rowsPerPage);

                                if (numPages > 1) {
                                    var $pagination = $("<div class=\'pagination\'></div>");
                                    var currentPage = 1;
                                    var updatePagination = function() {
                                        $pagination.empty();
                                        var $prevButton = $("<span class=\'prev\'>Anterior</span>");
                                        if (currentPage > 1) {
                                            $prevButton.click(function() {
                                                currentPage--;
                                                updatePagination();
                                                showRows();
                                            });
                                        } else {
                                            $prevButton.addClass("disabled");
                                        }
                                        $pagination.append($prevButton);

                                        // Páginas numeradas
                                        for (var i = Math.max(1, currentPage - 5); i <= Math.min(numPages, currentPage + 5); i++) {
                                            var $pageNumber = $("<span class=\'page-numbers\'></span>").text(i);
                                            if (i === currentPage) {
                                                $pageNumber.addClass("current");
                                            } else {
                                                $pageNumber.click(function() {
                                                    currentPage = parseInt($(this).text());
                                                    updatePagination();
                                                    showRows();
                                                });
                                            }
                                            $pagination.append($pageNumber);
                                        }
                                        
                                        // Muestra el número total de páginas solo una vez como un enlace válido
                                        if (currentPage === 1) {
                                            var $totalPages = $("<span class=\'total-pages\'></span>").html("(" + numPages + ")");
                                            $pagination.append($totalPages);
                                        }

                                        var $nextButton = $("<span class=\'next\'>Siguiente</span>");
                                        if (currentPage < numPages) {
                                            $nextButton.click(function() {
                                                currentPage++;
                                                updatePagination();
                                                showRows();
                                            });
                                        } else {
                                            $nextButton.addClass("disabled");
                                        }
                                        $pagination.append($nextButton);
                                    };
                                    var showRows = function() {
                                        var startIndex = (currentPage - 1) * rowsPerPage;
                                        var endIndex = Math.min(startIndex + rowsPerPage, totalRows);
                                        $tableRows.hide().slice(startIndex, endIndex).show();
                                    };
                                    updatePagination();
                                    showRows();
                                    $table.after($pagination);
                                }
                            });
                        });
                   </script>';
    }

    return $content;
}
add_filter( 'the_content', 'agregar_funcionalidades_a_tabla' );



//ocultar datos viejos del footable que ya no usaremos

function ocultar_contenido_especifico( $content ) {
    // Reemplaza el contenido específico con una cadena vacía
    $contenido_ocultar = '<img src="/wp-content/uploads/2021/11/lupac.png" alt="" width="40" height="40" /> Ingresar una palabra que identifique el número o tema del memorándum';
    $content = str_replace( $contenido_ocultar, '', $content );

    return $content;
}
add_filter( 'the_content', 'ocultar_contenido_especifico' );

function ocultar_contenido_especifico2( $content ) {
    // Reemplaza el contenido específico con una cadena vacía
    $contenido_ocultar = '<img src="/wp-content/uploads/2021/11/lupac.png" alt="" width="40" height="40" />';
    $content = str_replace( $contenido_ocultar, '', $content );

    return $content;
}
add_filter( 'the_content', 'ocultar_contenido_especifico2' );
