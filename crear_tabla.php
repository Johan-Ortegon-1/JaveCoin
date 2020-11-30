<?php

    include_once dirname(__FILE__) . '/config.php';
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
    $sql = "CREATE TABLE Usuario
    (
    PID INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY(PID),
    Nombre CHAR(15),
    Contrasena VARCHAR(100),
    Correo VARCHAR(100),
    Rol CHAR(15)
    )";
    if (mysqli_query($con, $sql)) {
        echo "Tabla Personas creada correctamente";
    } else {
        echo "Error en la creacion Persona" . mysqli_error($con);
    }

    $sql = "CREATE TABLE Cuenta
    (
    PID INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY(PID),
    Saldo NUMERIC(10,2),
    Cuota_manejo NUMERIC(10,2),
    ID_USUARIO INT NOT NULL,
    FOREIGN key(ID_USUARIO) references Usuario(PID) ON DELETE CASCADE
    )";
    if (mysqli_query($con, $sql)) {
        echo "Tabla Producto creada correctamente";
    } else {
        echo "Error en la creacion Producto" . mysqli_error($con);
    }

    $sql = "CREATE TABLE Credito
    (
    PID INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY(PID),
    Tasa_interes NUMERIC(5,2),
    Saldo NUMERIC(10,2),
    Estado CHAR(15),
    Fecha_pago DATE,
    Correo_notificaciones VARCHAR(100),
    ID_USUARIO INT,
    FOREIGN key(ID_USUARIO) references Usuario(PID) ON DELETE CASCADE
    )";
    if (mysqli_query($con, $sql)) {
        echo "Tabla Credito correctamente";
    } else {
        echo "Error en la tabla Credito" . mysqli_error($con);
    }

    $sql = "CREATE TABLE Tarjeta_Credito
    (
    PID INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY(PID),
    Cupo NUMERIC(10,2),
    Sobre_cupo NUMERIC(10,2),
    Cuota_manejo NUMERIC(10,2),
    Tasa_interes NUMERIC(5,2),
    Estado CHAR(15),
    ID_CUENTA INT NOT NULL,
    FOREIGN key(ID_CUENTA) references Cuenta(PID) ON DELETE CASCADE
    )";
    if (mysqli_query($con, $sql)) {
        echo "Tabla Tarjeta_Credito creada correctamente";
    } else {
        echo "Error en la creacion Tarjeta_Credito" . mysqli_error($con);
    }


    $sql = "CREATE TABLE Compras 
        (
        PID INT NOT NULL AUTO_INCREMENT,
        PRIMARY KEY(PID),
        Fecha_compra DATE,
        totalPagar NUMERIC(10,2),
        cuotas INT,
        cuotas_pagadas INT,
        ID_TARJETA INT NOT NULL,
        FOREIGN key(ID_TARJETA) references Tarjeta_Credito(PID) ON DELETE CASCADE
        )";
    if (mysqli_query($con, $sql)) {
        echo "Tabla Compras creada correctamente";
    } else {
        echo "Error en la creacion Compras" . mysqli_error($con);
    }
?>
<input type='button'value='Regresar al index' onclick="document.location.href='index.php';"/>
