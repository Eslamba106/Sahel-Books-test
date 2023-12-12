<?php

use App\Models\Lang_valuesModel;

$language = Lang_valuesModel::orderBy('id')->get();
foreach ($language as $row) {
    $lang[$row->keyword] = $row->english;
}
// 
$lang['cur_lang'] = "english";
$lang['align'] = "left";
$lang['alignX'] = "right";
$lang['dir'] = "ltr";
$lang['dirX'] = "rtl";
return $lang;
