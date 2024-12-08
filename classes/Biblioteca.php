<?php

class Biblioteca {
    public function __construct() {
        if (!isset($_SESSION['libros'])) {
            $_SESSION['libros'] = [];
        }
    }

    public function agregarLibro($libro) {
        $_SESSION['libros'][] = $libro;
    }
    
    public function listarLibros() {
        return $_SESSION['libros'];
    }


    public function buscarLibro($criterio) {
        $resultados = [];
        foreach ($_SESSION['libros'] as $libro) {
            if (stripos($libro->getTitulo(), $criterio) !== false || $libro->getId() == $criterio) {
                $resultados[] = $libro;
            }
        }
        return $resultados;
    }

    
    public function actualizarLibro($id, $nuevoTitulo, $nuevoAutor, $nuevaCategoria) {
        foreach ($_SESSION['libros'] as $libro) {
            if ($libro->getId() == $id) {
                $libro->setTitulo($nuevoTitulo);
                $libro->setAutor($nuevoAutor);
                $libro->setCategoria($nuevaCategoria);
                return true;
            }
        }
        return false;
    }

    

    public function eliminarLibro($id) {
        foreach ($_SESSION['libros'] as $index => $libro) {
            if ($libro->getId() == $id) {
                unset($_SESSION['libros'][$index]);
                $_SESSION['libros'] = array_values($_SESSION['libros']); 
                return true;
            }
        }
        return false;
    }

    
    public function prestarLibro($id) {
    foreach ($_SESSION['libros'] as $libro) {
        if ($libro->getId() == $id && $libro->isDisponible()) {
            $libro->setDisponible(false);
            return true;
        }
    }
    return false;
}



}



?>