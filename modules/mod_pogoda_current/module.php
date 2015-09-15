<?php
/*******************************************************************************/
//                          InstantCMS v1.10.6                                 //
//                      http://www.instantcms.ru/                              //
//                         component "Pogoda"                                  //
//                       module "Текущая погода"                               //
//                       written by Marat Fatikhov                             //
//                      (nickname Марат on a site)                             //
//                       E-mail: f-marat@mail.ru                               //
//                                                                             //
//                      LICENSED BY GNU/GPL v2                                 //
//                                                                             //
/********************************************************************************/
function mod_pogoda_current($mod, $cfg){

    $inCore = cmsCore::getInstance();

    //Загрузка настроек компонента
    $component = $inCore->loadComponentConfig('pogoda');
    $component["name_en"] = $component["name_en"] ? $component["name_en"].'_': '';

    // Проверяем включен ли компонент и установлен ли city_id
    if (!$component['component_enabled'] || !$component['city_id'])return false;

    cmsCore::loadModel('pogoda');
    $model = new cms_model_pogoda();

    $model->setTable('current');
    $dbWeather = $model->getWeather();
    $xml = simplexml_load_string($dbWeather["xml"]);

    if(!$xml)return true;

    $current = array();
    $current["temperature"] = round($xml->temperature["value"]) . ' &#176;C';

    $current["weather"]["value"] = $xml->weather["value"];
    $current["weather"]["icon"] = $xml->weather["icon"];

    $current["lastupdate"] = date('d.m.y H:i', strtotime($xml->lastupdate["value"]) +($cfg["utc_diff"]*3600));

    cmsPage::initTemplate('modules', $cfg['tpl'])->
    assign('current', $current)->
    display($cfg['tpl']);

    return true;
}
?>