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
// ========================================================================== //

function info_module_mod_pogoda_current(){

    //Заголовок (на сайте)
    $_module['title']        = 'Текущая погода';

    //Название (в админке)
    $_module['name']         = 'Информер текущей погоды';

    //описание
    $_module['description']  = 'Выводит на сайте информер текущей погоды с БД компонента com_pogoda';

    //ссылка (идентификатор)
    $_module['content']         = 'mod_pogoda_current';

    //позиция
    $_module['position']     = 'sidebar';

    //автор
    $_module['author']       = 'Marat Fatikhov';

    //текущая версия
    $_module['version']      = '2.0.0';

    //
    // Настройки по-умолчанию
    //
    $_module['config'] = array();

    return $_module;

}

// ========================================================================== //

function install_module_mod_pogoda_current(){

    return true;
}

// ========================================================================== //

function upgrade_module_mod_pogoda_current(){

    return true;

}

// ========================================================================== //

?>
