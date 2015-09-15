<?php
/*******************************************************************************/
//                          InstantCMS v1.10.6                                 //
//                      http://www.instantcms.ru/                              //
//                         component "Pogoda"                                  //
//                       written by Marat Fatikhov                             //
//                      (nickname Марат on a site)                             //
//                       E-mail: f-marat@mail.ru                               //
//                                                                             //
//                      LICENSED BY GNU/GPL v2                                 //
//                                                                             //
/********************************************************************************/
if (!defined('VALID_CMS')) {
    die('ACCESS DENIED');
}
/*
 * Переводит направление ветера на рус.
 * @param str $direction
 * return string;
 */
function directToRus($direction){
    $en = array('NW', 'SW', 'NE', 'SE','W', "S", 'E', 'N');
    $ru = array(' С-В', ' Ю-В', ' С-З', ' Ю-З', ' В', ' Ю', ' З', ' С');
    return str_replace($en, $ru, $direction);
}

/*
 * Переводит облачность на рус.
 * @param str $clouds
 * return string;
 */
function cloudsToRus($clouds){
    $en = array('few clouds', 'clear sky', 'overcast clouds', 'broken clouds', 'scattered clouds');
    $ru = array('кучевые облака', 'ясное небо', 'облачно', 'сплошная облачность', 'рассеянные облака');
    return str_replace($en, $ru, $clouds);
}

/*
 * Переводит осадки на рус.
 * @param str $prec
 * return string;
 */
function precipitationToRus($prec){
    $en = array('no', 'rain', 'snow');
    $ru = array('нет', 'дождь', 'снег');
    return str_replace($en, $ru, $prec);
}

/*
 * Переводит дни недели и названия месяца на рус.
 * @param str $date
 * return string;
 */
function getRusDate($date){
    $date = str_replace('January', 'Января', $date);
    $date = str_replace('February', 'Февраля', $date);
    $date = str_replace('March', 'Марта', $date);
    $date = str_replace('April', 'Апреля', $date);
    $date = str_replace('May', 'Мая', $date);
    $date = str_replace('June', 'Июня', $date);
    $date = str_replace('July', 'Июля', $date);
    $date = str_replace('August', 'Августа', $date);
    $date = str_replace('September', 'Сентября', $date);
    $date = str_replace('October', 'Октября', $date);
    $date = str_replace('November', 'Ноября', $date);
    $date = str_replace('December', 'Декабря', $date);

    //заменяем дни недели
    $date = str_replace('Mon', 'Пн', $date);
    $date = str_replace('Tue', 'Вт', $date);
    $date = str_replace('Wed', 'Ср', $date);
    $date = str_replace('Thu', 'Чтв', $date);
    $date = str_replace('Fri', 'Пт', $date);
    $date = str_replace('Sat', 'Сб', $date);
    $date = str_replace('Sun', 'Вск', $date);

    return $date;
}

function pogoda()
{
    $inCore = cmsCore::getInstance();
    $inPage = cmsPage::getInstance();
    $inDB = cmsDatabase::getInstance();

    $inCore->loadModel('pogoda');
    $model = new cms_model_pogoda();



    //Загрузка настроек компонента
    $cfg = $inCore->loadComponentConfig('pogoda');
    $cfg["name_en"] = $cfg["name_en"] ? $cfg["name_en"].'_': '';

    // Проверяем включен ли компонент и установлен ли city_id
    if (!$cfg['component_enabled'] || !$cfg['city_id']) {
        cmsCore::error404();
    }

    //Получаем входные параметры
    $do = $inCore->request('do', 'str', 'current');
    $days = $inCore->request('days', 'int', 1);

    $model->setTable($do);
    $dbWeather = $model->getWeather();
    $xml = simplexml_load_string($dbWeather["xml"]);

    $props = $model->getPagesProps($days);
    $props = $props[$days];

    $title = $props['title'] ? $props['title'] : 'Прогноз погоды на '.cmsCore::spellCount($days, 'день', 'дня', 'дней');
    if(!$props['title'] && $days == 1){
        $title = 'Текущая погода';
    }
    $inPage->setTitle($title);

    $keys = $props['meta_keys'] ? $props['meta_keys'] : 'прогноз, погода, '.cmsCore::spellCount($days, 'день', 'дня', 'дней');
    $inPage->setKeywords($keys);

    $desc = $props['meta_desc'] ? $props['meta_desc'] : 'Прогноз погоды в '.$cfg["name_ru"].' на '.cmsCore::spellCount($days, 'день', 'дня', 'дней');
    $inPage->setDescription($desc);

    if($days == 1){
        $inPage->addPathway('Текущая погода', '/pogoda/');
    }else{
        $inPage->addPathway('Погода', '/pogoda/');
        $inPage->addPathway('Прогноз погоды на '.cmsCore::spellCount($days, 'день', 'дня', 'дней'));
    }


    //========================================  CURRENT WEATHER    ====================================================//

    if ($do == 'current') {

        $current = array();
        $current["temperature"] = round($xml->temperature["value"]) . ' &#176;C';

        $current["humidity"] = $xml->humidity["value"] . ' ' . $xml->humidity["unit"];

        $current["pressure"] = round($xml->pressure["value"] * 0.7500637554192) . ' мм. рт. ст.';

        $current["wind"]["speed"] = $xml->wind->speed["value"] . ' м\с';
        $current["wind"]["direction"] = directToRus($xml->wind->direction["code"]);

        $current["clouds"] = cloudsToRus($xml->clouds["name"]);

        $current["precipitation"] = precipitationToRus($xml->precipitation["mode"]) .' ' . ($xml->precipitation["value"] ? round((float)$xml->precipitation["value"], 1).' мм' : '');

        $current["weather"]["value"] = $xml->weather["value"];
        $current["weather"]["icon"] = $xml->weather["icon"];

        $current["lastupdate"] = date('d.m.y H:i', strtotime($xml->lastupdate["value"]) +($cfg["utc_diff"]*3600));

        cmsPage::initTemplate('components', 'com_pogoda_current')->
        assign('current', $current)->
        assign('cfg', $cfg)->
        assign('props', $props)->
        display('com_pogoda_current.tpl');
    }


    //========================================  FORECAST 3,5 DAYS    ==================================================//
    if($do == '5_days'){

        $forecast = array();

        foreach($xml->forecast->time as $v){

            $to = explode('T', $v["to"]);
            $from = explode('T', $v["from"]);

            $to_utime = strtotime($to[0].' '.$to[1])+($cfg["utc_diff"]*3600);
            $from_utime = strtotime($from[0].' '.$from[1])+($cfg["utc_diff"]*3600);

            if($to_utime < time())continue;
            if($from_utime > strtotime(date('d-m-Y'))+$days*24*3600)continue;

            $to[0] = date('d.m.Y', $to_utime);
            $to[1] = date('H:i', $to_utime);

            $from[0] = date('d.m.Y', $from_utime);
            $from[1] = date('H:i', $from_utime);

            $forecast[$from[0]][$from[1]]["temperature"] = round($v->temperature["value"]) . ' &#176;C';
            $forecast[$from[0]][$from[1]]["humidity"] = $v->humidity["value"] . ' ' . $v->humidity["unit"];
            $forecast[$from[0]][$from[1]]["pressure"] = round($v->pressure["value"] * 0.7500637554192) . ' мм. рт. ст.';
            $forecast[$from[0]][$from[1]]["wind"]["speed"] = $v->windSpeed["mps"] . ' м\с';
            $forecast[$from[0]][$from[1]]["wind"]["direction"] = directToRus($v->windDirection["code"]);
            $forecast[$from[0]][$from[1]]["clouds"] = cloudsToRus($v->clouds["value"]);
            $forecast[$from[0]][$from[1]]["precipitation"] = precipitationToRus($v->precipitation["type"]) .' ' . ($v->precipitation["value"] ? round((float)$v->precipitation["value"], 1).' мм' : '');
            $forecast[$from[0]][$from[1]]["weather"]["value"] = (string)$v->symbol["name"];
            $forecast[$from[0]][$from[1]]["weather"]["icon"] = (string)$v->symbol["var"];

        }

        $counts = $fdates = array();
        foreach ($forecast as $k=>$v) {
            $counts[$k] = count($v);
            $fdates[$k] = getRusDate(date("j F, D", strtotime($k)));
        }


        $counts['days'] = count($forecast);

        cmsPage::initTemplate('components', 'com_pogoda_5days')->
        assign('forecast', $forecast)->
        assign('days', $days)->
        assign('counts', $counts)->
        assign('fdates', $fdates)->
        assign('cfg', $cfg)->
        assign('props', $props)->
        display('com_pogoda_5days.tpl');
    }

    //========================================  FORECAST 7,10,14 DAYS    ==================================================//
    if($do == '16_days'){

        $forecast = array();
        foreach($xml->forecast->time as $v){

            $date = $v["day"];

            if(strtotime($date) < strtotime(date('d-m-Y')))continue;
            if(strtotime($date) >= strtotime(date('d-m-Y'))+$days*24*3600)continue;

            $forecast["$date"]["temperature"]["day"] = round($v->temperature["day"]) . ' &#176;C';
            $forecast["$date"]["temperature"]["night"] = round($v->temperature["night"]) . ' &#176;C';
            $forecast["$date"]["humidity"] =  $v->humidity["value"] != 0 ? $v->humidity["value"] . ' ' . $v->humidity["unit"] : ' - ';
            $forecast["$date"]["pressure"] = round($v->pressure["value"] * 0.7500637554192) . ' мм. рт. ст.';
            $forecast["$date"]["wind"]["speed"] = $v->windSpeed["mps"] . ' м\с';
            $forecast["$date"]["wind"]["direction"] = directToRus($v->windDirection["code"]);
            $forecast["$date"]["clouds"] = cloudsToRus($v->clouds["value"]);
            $forecast["$date"]["precipitation"] = precipitationToRus($v->precipitation["type"]) .' ' . ($v->precipitation["value"] ? round((float)$v->precipitation["value"], 1).' мм' : '');
            $forecast["$date"]["weather"]["value"] = (string)$v->symbol["name"];
            $forecast["$date"]["weather"]["icon"] = (string)$v->symbol["var"];

        }

        $fdates = array();
        foreach ($forecast as $k=>$v) {
            $fdates[$k] = getRusDate(date("j F, D", strtotime($k)));
        }

        cmsPage::initTemplate('components', 'com_pogoda_16days')->
        assign('forecast', $forecast)->
        assign('days', $days)->
        assign('fdates', $fdates)->
        assign('cfg', $cfg)->
        assign('props', $props)->
        display('com_pogoda_16days.tpl');
    }
}

?>