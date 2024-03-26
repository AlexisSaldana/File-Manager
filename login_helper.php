<?php

$USERS = [
    [
        "id" => 1,
        "username" => "admin",  // clear text password => Admin1234
        "nombre" => "Administrador",
        "passwordEncrypted" => "17D94FA6D235D3B338FC3A696C64A186852ADEDF8A0BE87D1F62227C1192EEB345B717F228278100D690FFFEC5E48D15809E8829F6CAFD3325F3AB09E72248B0",
        "passwordSalt" => "8362C1F926583CAC4A8C617AA1D33CE23F8652C1E5406443D31D0C41808F3835",
        "esAdmin" => true
    ],
    [
        "id" => 2,
        "username" => "user01",  // clear text password => user01
        "nombre" => "Usuario 01",
        "passwordEncrypted" => "406D7E2E07B2BBEA0F931B93897600ED4C4D41DFEE46EC02E7316C9CE0E5D82DBA14160AB296A243B6BD2FE2A0179367B45A68B40869172864C996F65FB4D4D0",
        "passwordSalt" => "BCBEFB53EC209EB5F557347C970D87C649E90F9591EA4441A1B378F5A75EBA2C",
        "esAdmin" => false
    ]
];

/**
 * Función con la que validamos el usuario por su username y password. Si no se validan
 * los datos, regresara un false, en caso de que se valide correctamente el usuario,
 * regresara un assoc array con los datos del usuario.
 */
function autentificar($username, $password) {

    // Si no nos han enviado los parámetros, regresamos false.
    if (!$username || !$password) {
        return false;
    }

    global $USERS;  // para usar una variable que está fuera de la función
    $user = NULL;  // Aquí pondremos al usuario que encontramos

    // Recorremos todo el array para encontrar al usuario por el username, esto
    // para simular una consulta a DB de una tabla de usuarios
    foreach ($USERS as $u) {
        if ($username == $u["username"]) {
            $user = $u;
            break;
        }
    }

    // Si no encontramos al usuario, regresamos false
    if (!$user) {
        return false;
    }

    // Se obtiene el hash que representa el password junto con el salt
    $passwordMasSalt = $password . $user["passwordSalt"];
    $passwordEncrypted = strtoupper(hash("sha512", $passwordMasSalt));

    // Comparamos si el password encrypted del password que nos pasaron es el
    // que corresponde al password encrypted del usuario, si no es así, el
    // login es incorrecto y se regresa un false
    if ($passwordEncrypted != $user["passwordEncrypted"]) {
        return false;
    }

    // En este punto ya se realizó correctamente la autenticación, por lo que
    // regresamos un assoc array con los datos del usuario
    return [
        "id" => $user["id"],
        "username" => $user["username"],
        "nombre" => $user["nombre"],
        "esAdmin" => $user["esAdmin"]
    ];
}
