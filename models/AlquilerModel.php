<?php

class AlquilerModel{
    public $enlace;

   
    public function __construct() {
        
        $this->enlace=new MySqlConnect();
       
    }
    public function all(){
        try {
            //Consulta sql
			$vSql = "SELECT * FROM alquiler order by fechaDesde asc;";
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
            $biciM=new BicicletaModel();
            $usuarioM=new UsuarioModel();
            //Consulta sql
			$vSql = "SELECT * FROM alquiler where idAlquiler=$id";
			$this->enlace->connect();
            //Ejecutar la consulta
			$vResultado = $this->enlace->ExecuteSQL ( $vSql);
            $vResultado = $vResultado[0];
            //Usuario
            $usuario=$usuarioM->get($vResultado->idUsuario);
            $vResultado->usuario=$usuario;
            //Bicicleta
            $bici=$biciM->get($vResultado->idBicicleta);
            $vResultado->bicicleta=$bici;
            //Talla
            $talla=$tallaM->get($vResultado->idTalla);
            $vResultado->talla=$talla; 
			// Retornar el objeto
			return $vResultado;
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
    } 
    private function calcularTotal($objeto){
        //Calcular total
        //Obtener info Bici
        $biciM=new BicicletaModel();
        $bici=$biciM->get($objeto->idBicicleta);
        //Valor seguro
        $seguroAsistencia=$objeto->seguroAsistencia ? 4000: 0;
        return ($bici->precioDia*$objeto->cantidadDias)+$seguroAsistencia;
    }
    public function create($objeto) {
        try {
            //Calcular total
            $objeto->total=$this->calcularTotal($objeto);
            //Consulta sql
            $this->enlace->connect();
			$sql = "Insert into alquiler ( idUsuario, fechaDesde, cantidadDias, idBicicleta, idTalla, comentarios, seguroAsistencia, total)". 
                     "Values ($objeto->idUsuario,'$objeto->fechaDesde',$objeto->cantidadDias,$objeto->idBicicleta,$objeto->idTalla,'$objeto->comentarios',$objeto->seguroAsistencia,$objeto->total)";
			
            //Ejecutar la consulta
            //Obtener ultimo insert
			$idAlquiler = $this->enlace->executeSQL_DML_last( $sql);           
            //Retornar bicicleta
            return $this->get($idAlquiler);
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
    }
    public function update($objeto) {
        try {
            //Calcular total
            $objeto->total=$this->calcularTotal($objeto);
            //Consulta sql
            $this->enlace->connect();
			$sql = "Update alquiler SET idUsuario ='$objeto->idUsuario',".
            " fechaDesde ='$objeto->fechaDesde', cantidadDias =$objeto->cantidadDias,".
            " idBicicleta =$objeto->idBicicleta, idTalla =$objeto->idTalla,". 
            " comentarios ='$objeto->comentarios', seguroAsistencia =$objeto->seguroAsistencia,".
            " total =$objeto->total".
            " Where idAlquiler=$objeto->idAlquiler";
			
            //Ejecutar la consulta
			$cResults = $this->enlace->executeSQL_DML( $sql);
           
            //Retornar alquiler
            return $this->get($objeto->idAlquiler);
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
    } 
    public function alquilerBici($fecha){
        try {
            //Consulta sql
			$vSql = "SELECT b.nombre as Bicicleta, SUM(a.cantidadDias) as CantidadAlquiler".
            " FROM alquiler a, bicicleta b".
            " WHERE b.idBicicleta=a.idBicicleta and".
            " a.fechaDesde>=$fecha".
            " group by b.nombre".
            " order by SUM(a.cantidadDias) desc";
			$this->enlace->connect();
            //Ejecutar la consulta
			$vResultado = $this->enlace->ExecuteSQL ( $vSql);
				
			// Retornar el objeto
			return $vResultado;
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
    }
}
?>