<?php

function generateToken()
{
    $caracters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $token = substr(str_shuffle($caracters), 0, 12);
    return $token;
}



function comparePasswords($password1, $password2)
{
    if ($password1 == $password2) {
    } else {
        return "Les dues contrasenyes han de ser iguals";
    }
    return null;
}

function passwordLength($password1)
{
    if (strlen($password1) >= 7) {
    } else {
        return "La contrasenya ha de tenir com a minim 8 caracters";
    }
    return null;
}

function compareUsername($username, $users)
{
    foreach ($users as $key => $user) {
        if ($user['username'] == $username) {
            return "Ja hi ha un usuari utilitzant aquest nom d'usuari";
        }
    }
}
function compareEmail($email, $users)
{
    foreach ($users as $key => $user) {
        if ($user['email'] == $email) {
            return "Ja hi ha un usuari utilitzant aquest correu";
        }
    }
    return null;
}

function emailEmpty($email)
{
    if (empty($email)) {
        return "El correu no pot estar buit";
    } else {
        return null;
    }
}

function passwordEmpty($password)
{
    if (empty($password)) {
        return "La contrasenya no pot estar buida";
    } else {
        return null;
    }
}
function password2Empty($password)
{
    if (empty($password)) {
        return "Has de posar dos cops la contrasenya";
    } else {
        return null;
    }
}

function firstNameEmpty($firstName)
{
    if (empty($firstName)) {
        return "Has de posar el teu nom";
    } else {
        return null;
    }
}
function lastNameEmpty($lastName)
{
    if (empty($lastName)) {
        return "Has de posar el teu cognom";
    } else {
        return null;
    }
}
function dateEmpty($date)
{
    if (empty($date)) {
        return "Has de posar la teva data de naixament";
    } else {
        return null;
    }
}
function imgEmpty($img)
{
    if (empty($img)) {
        return "Has de posar la teva imatge de perfil";
    } else {
        return null;
    }
}
function emailValidation($email)
{
    $pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

    if (preg_match($pattern, $email)) {
        return null;
    } else {
        return "Has de posar un correu valid.";
    }
}
