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

function routes_pogoda(){

    $inCore = cmsCore::getInstance();
    $cfg = $inCore->loadComponentConfig('pogoda');
    $cfg["name_en"] = $cfg["name_en"] ? $cfg["name_en"].'_': '';

    $routes[] = array(
        '_uri'  => '/^pogoda\/'.$cfg["name_en"].'na_3_dnya.html$/i',
        'do'    => '5_days',
        'days'  => 3
    );

    $routes[] = array(
        '_uri'  => '/^pogoda\/'.$cfg["name_en"].'na_5_dney.html$/i',
        'do'    => '5_days',
        'days'  => 5
    );

    $routes[] = array(
        '_uri'  => '/^pogoda\/'.$cfg["name_en"].'na_7_dney.html$/i',
        'do'    => '16_days',
        'days'  => 7
    );

    $routes[] = array(
        '_uri'  => '/^pogoda\/'.$cfg["name_en"].'na_10_dney.html$/i',
        'do'    => '16_days',
        'days'  => 10
    );

    $routes[] = array(
        '_uri'  => '/^pogoda\/'.$cfg["name_en"].'na_14_dney.html$/i',
        'do'    => '16_days',
        'days'  => 14
    );

    return $routes;
}
?>