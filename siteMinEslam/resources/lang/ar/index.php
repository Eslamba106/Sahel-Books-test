<?php

use App\Models\Lang_valuesModel;

$language = Lang_valuesModel::orderBy('id')->get();
foreach ($language as $row) {
    $lang[$row->keyword] = $row->arabic;
}
// 
$lang['cur_lang'] = "arabic";
$lang['align'] = "right";
$lang['alignX'] = "left";
$lang['dir'] = "rtl";
$lang['dirX'] = "ltr";
return $lang;
