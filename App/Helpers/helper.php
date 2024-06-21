<?php 
function compararDates($data) {
$dateFormatted = new DateTime($data, new DateTimeZone('Europe/Madrid'));
$data_actual = new DateTime('now', new DateTimeZone('Europe/Madrid'));
$data_actual->format('Y-m-d H:i:s');
$diferencia = $dateFormatted->diff($data_actual);
$dies = $diferencia->d;
$hores = $diferencia->h;
$minuts = $diferencia->i;

$temps = "";

if ($dies > 0) {
    $temps = $dies . ($dies > 1 ? ' dies' : ' dia');
    return $temps;
}

if ($hores > 0) {
    $temps = $hores . ($hores > 1 ? ' hores' : ' hora');
    return $temps;
}

if ($minuts > 0) {
    $temps = $minuts . ($minuts > 1 ? ' minuts' : ' minut');
    return $temps;
}

return "1 minut";

}