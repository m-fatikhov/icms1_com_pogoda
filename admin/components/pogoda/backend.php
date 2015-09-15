<?php
if (!defined('VALID_CMS_ADMIN')) {
    die('ACCESS DENIED');
}
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

$opt = cmsCore::request('opt', 'str', 'list');

$toolmenu[] = array('icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.optform.submit();');
$toolmenu[] = array('icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => '?view=components');

cpToolMenu($toolmenu);

$inCore->loadModel('pogoda');
$model = new cms_model_pogoda();

$cfg = $inCore->loadComponentConfig('pogoda');
$props = $model->getPagesProps();

$days = array(
    1 => ' текущая',
    3 => '3 дня',
    5 => '5 дней',
    7 => '7 дней',
    10 => '10 дней',
    14 =>'2 недели'
);

if ($opt == 'saveconfig') {

    if (!cmsCore::validateForm()) {
        cmsCore::error404();
    }

    $cfg['city_id'] = cmsCore::request('city_id', 'int', 0);
    $cfg['utc_diff'] = cmsCore::request('utc_diff', 'int', 0);
    $cfg['name_ru'] = cmsCore::request('name_ru', 'str', '');
    $cfg['name_en'] = cmsCore::request('name_en', 'str', '');
    $cfg['show_copyright'] = cmsCore::request('show_copyright', 'int', 1);

    foreach($days as $k=>$day){
        $props[$k]['h1'] = cmsCore::request($k.'_h1', 'str', '');
        $props[$k]['title'] = cmsCore::request($k.'_title', 'str', '');
        $props[$k]['meta_keys'] = cmsCore::request($k.'_meta_keys', 'str', '');
        $props[$k]['meta_desc'] = cmsCore::request($k.'_meta_desc', 'str', '');
        $props[$k]['before_text'] = cmsCore::request($k.'_before_text', 'str', '');
        $props[$k]['after_text'] = cmsCore::request($k.'_after_text', 'str', '');
    }

    cmsCore::addSessionMessage("Настройки успешно сохранены", 'success');

    $inCore->saveComponentConfig('pogoda', $cfg);
    $model->savePagesProps($props);

    cmsCore::redirectBack();
}
?>
<form action="index.php?view=components&amp;do=config&amp;id=<?php echo $id ?>" method="post" name="optform"
      target="_self" id="optform">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>"/>

    <div id="config_tabs" style="margin-top:12px;" class="uitabs">

        <ul id="tabs">
            <li><a href="#basic"><span><?php echo "Общие"; ?></span></a></li>
            <?php
                foreach($days as $k=>$day){
                    ?>
                    <li><a href="#<?php echo $k; ?>days"><span><?php echo $day; ?></span></a></li>
                    <?
                }
            ?>
        </ul>

        <div id="basic">
            <table width="750px" border="0" cellpadding="10" cellspacing="0" class="proptable">
                <tr>
                    <td width="400px">
                        <strong><?php echo "ID города в системе OpenWeatherMap"; ?>:</strong><br>
                        <a href="/components/pogoda/instruction.txt" target="_blank">Как узнать ID города?</a>
                    </td>
                    <td>
                        <input type="text" style="width:300px" name="city_id" value="<?php echo $cfg['city_id']; ?>">
                    </td>
                </tr>
                <tr>
                    <td width="400px">
                        <strong><?php echo "Название города в предложном падеже(где?)"; ?>:</strong><br>
                        Например, "в Москве".
                    </td>
                    <td>
                        <input type="text" style="width:300px" name="name_ru" value="<?php echo $cfg['name_ru']; ?>">
                    </td>
                </tr>
                <tr>
                    <td width="400px">
                        <strong><?php echo "Название города в предложном падеже(где?) в транслите"; ?>:</strong><br>
                        Например, "v_moskve". Будет подставляться в URI страниц компонента.
                    </td>
                    <td>
                        <input type="text" style="width:300px" name="name_en" value="<?php echo $cfg['name_en']; ?>">
                    </td>
                </tr>
                <tr>
                    <td width="400px">
                        <strong><?php echo "Смещение от Всемирного времени(UTC) в часах"; ?>:</strong><br>
                        Например для Москвы это +3. Указывается только знак "-". Знак "+" можно не указывать.
                    </td>
                    <td>
                        <input type="text" style="width:300px" name="utc_diff" value="<?php echo $cfg['utc_diff']; ?>">
                    </td>
                </tr>
                <tr>
                    <td width="400px">
                        <strong><?php echo "Показывать ссылку на сервис"; ?>:</strong><br>
                        По условиям лицензии СС вы должны указывать ссылку на сервис http://openweathermap.org
                    </td>
                    <td>
                        <input type="radio" name="show_copyright" <?php if ($cfg['show_copyright']) { echo 'checked="checked"'; } ?> value="1">Да
                        <input type="radio" name="show_copyright" <?php if (!$cfg['show_copyright']) { echo 'checked="checked"'; } ?> value="0">Нет
                    </td>
                </tr>
            </table>
        </div>

        <?php
        foreach($days as $k=>$day){
            ?>
            <div id="<?php echo $k; ?>days">
                <table width="750px" border="0" cellpadding="10" cellspacing="0" class="proptable">
                    <tr>
                        <td width="400px">
                            <strong><?php echo "Заголовок страницы(тег H1)"; ?>:</strong><br>
                        </td>
                        <td>
                            <input type="text" style="width:300px" name="<?php echo $k; ?>_h1" value="<?php echo $props[$k]['h1']; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td width="400px">
                            <strong><?php echo "Заголовок страницы(тег title)"; ?>:</strong><br>
                        </td>
                        <td>
                            <input type="text" style="width:300px" name="<?php echo $k; ?>_title" value="<?php echo $props[$k]['title']; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td width="400px">
                            <strong><?php echo "Ключевые слова(meta_keys)"; ?>:</strong><br>
                        </td>
                        <td>
                            <textarea style="width:300px" rows="6" name="<?php echo $k; ?>_meta_keys"><?php echo $props[$k]['meta_keys']; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td width="400px">
                            <strong><?php echo "Описание (meta_desc)"; ?>:</strong><br>
                        </td>
                        <td>
                            <textarea style="width:300px" rows="6" name="<?php echo $k; ?>_meta_desc"><?php echo $props[$k]['meta_desc']; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td width="400px">
                            <strong><?php echo "Текст до блока погоды"; ?>:</strong><br>
                        </td>
                        <td>
                            <?php $inCore->insertEditor($k.'_before_text', $props[$k]['before_text'], '300', '800'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="400px">
                            <strong><?php echo "Текст после блока погоды"; ?>:</strong><br>
                        </td>
                        <td>
                            <?php $inCore->insertEditor($k.'_after_text', $props[$k]['after_text'], '300', '800'); ?>
                        </td>
                    </tr>
                </table>
            </div>
            <?
        }
        ?>



        <p>
            <input name="opt" type="hidden" value="saveconfig"/>
            <input name="save" type="submit" id="save" value="<?php echo $_LANG['SAVE']; ?>"/>
            <input name="back" type="button" id="back" value="<?php echo $_LANG['CANCEL']; ?>"
                   onclick="window.location.href='index.php?view=components';"/>
        </p>
</form>