<?
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

if(!defined('VALID_CMS')) { die('ACCESS DENIED'); }

// ========================================================================== //

function info_component_pogoda(){

    //Описание компонента

    $_component['title']        = 'Погода в вашем городе';                               //название
    $_component['description']  = 'Текущая погода и прогноз погоды для вашего города';    //описание
    $_component['link']         = 'pogoda';                                              //ссылка (идентификатор)
    $_component['author']       = 'Marat Fatikhov';                                      //автор
    $_component['internal']     = '0';                                                   //внутренний (только для админки)? 1-Да, 0-Нет
    $_component['version']      = '2.0.0';                                                //текущая версия
    $_component['modules']      = array('mod_pogoda_current' => 'Текущая погода');

    //Настройки по-умолчанию

    $_component['config'] = array(
        'show_copyright' => 1
    );

    return $_component;

}

// ========================================================================== //

function install_component_pogoda(){

    $inCore = cmsCore::getInstance();
    $inDB       = cmsDatabase::getInstance();

    $inDB->importFromFile($_SERVER['DOCUMENT_ROOT'].'/components/pogoda/install.sql');

    //устанавливаем модуль компонента
    if ($inCore->loadModuleInstaller('mod_pogoda_current')) {
        $_module = call_user_func('info_module_mod_pogoda_current');
        //////////////////////////////////////
        call_user_func('install_module_mod_pogoda_current');

        if ($_module) {
            $inCore->installModule($_module);
        }
    }

    return true;

}

// ========================================================================== //

function upgrade_component_pogoda(){

    //для версии 2.0.0 апгрейд модуля == новая установка
    install_component_pogoda();

    return true;
}

// ========================================================================== //

function remove_component_pogoda(){

    $inDB       = cmsDatabase::getInstance();
    $inCore = cmsCore::getInstance();

    //удаляем модуль
    $module_id = $inCore->getModuleId('mod_pogoda_current');
    if($module_id){
        $inCore->removeModule($module_id);
    }

    //удаляем таблицы компонента
    $inDB->query("DROP TABLE IF EXISTS `cms_pogoda_5days`");
    $inDB->query("DROP TABLE IF EXISTS `cms_pogoda_16days`");
    $inDB->query("DROP TABLE IF EXISTS `cms_pogoda_current`");
    $inDB->query("DROP TABLE IF EXISTS `cms_pogoda_props`");

    //удаляем записи задач CRON
    $inDB->query("DELETE FROM `cms_cron_jobs` WHERE `component` = 'pogoda'");

    return true;
}

// ========================================================================== //
?>