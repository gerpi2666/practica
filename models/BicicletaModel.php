<?php

class BicicletaModel{
    public $enlace;

   
    public function __construct() {
        
        $this->enlace=new MySqlConnect();
       
    }
    public function all(){
        try {
            //Consulta sql
			$vSql = "SELECT * FROM bicicleta order by nombre asc;";
			$this->enlace->connect();
            //Ejecutar la consulta
			$vResultado = $this->enlace->ExecuteSQL ( $vSql);
				
			// Retornar el objeto
			return $vResultado;
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
    }

    public function get($id){
        try {
            $tallaM=new TallaModel();
            $categM=new CategoriaModel();
            //Consulta sql
			$vSql = "SELECT * FROM bicicleta where idBicicleta=$id";
			$this->enlace->connect();
            //Ejecutar la consulta
			$vResultado = $this->enlace->ExecuteSQL ( $vSql);
            $vResultado = $vResultado[0];
            //Categoria
            $categ=$categM->get($vResultado->idCategoria);
            $vResultado->categoria=$categ;
            //Lista de tallas de la bicicleta
            $tallas=$tallaM->getTallasBici($id);
            $vResultado->tallas=$tallas;
			// Retornar el objeto
			return $vResultado;
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
    }   
    public function create($objeto) {
        try {
            //Consulta sql
            $this->enlace->connect();
			$sql = "Insert into bicicleta (nombre, descripcion, precioDia, idCategoria)". 
                     "Values ('$objeto->nombre','$objeto->descripcion',$objeto->precioDia,$objeto->idCategoria)";
			
            //Ejecutar la consulta
            //Obtener ultimo insert
			$idBicicleta = $this->enlace->executeSQL_DML_last( $sql);
            //--- Tallas ---
            //Crear elementos a insertar en tallas
            foreach( $objeto->tallas as $talla){
                $dataTallas[]=array($idBicicleta,$talla);
            }
            foreach($dataTallas as $row){
                $this->enlace->connect();
                $valores=implode(',', $row);
                $sql = "INSERT INTO bicicleta_talla(idBicicleta,idTalla) VALUES(".$valores.");";
                $vResultado = $this->enlace->executeSQL_DML($sql);
            }            
            //Retornar bicicleta
            return $this->get($idBicicleta);
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
    }
    public function update($objeto) {
        try {
            //Consulta sql
            $this->enlace->connect();
			$sql = "Update bicicleta SET nombre ='$objeto->nombre',".
            " descripcion ='$objeto->descripcion', precioDia =$objeto->precioDia, idCategoria ='$objeto->idCategoria'". 
            " Where idBicicleta=$objeto->idBicicleta";
			
            //Ejecutar la consulta
			$cResults = $this->enlace->executeSQL_DML( $sql);
            //--- Tallas ---
            //Borrar Tallas existentes asignadas
            $this->enlace->connect();
			$sql = "Delete from bicicleta_talla Where idBicicleta=$objeto->idBicicleta";
			$cResults = $this->enlace->executeSQL_DML( $sql);

             //Crear elementos a insertar en tallas
             foreach( $objeto->tallas as $talla){
                $dataTallas[]=array($objeto->idBicicleta,$talla);
            }
            foreach($dataTallas as $row){
                $this->enlace->connect();
                $valores=implode(',', $row);
                $sql = "INSERT INTO bicicleta_talla(idBicicleta,idTalla) VALUES(".$valores.");";
                $vResultado = $this->enlace->executeSQL_DML($sql);
            }
            //Retornar bicicleta
            return $this->get($objeto->idBicicleta);
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
    }
}
?>