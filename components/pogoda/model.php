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

class cms_model_pogoda
{
    private $table = 'cms_pogoda_current';
    private $city_id = 0;

    function __construct()
    {
        $this->inDB = cmsDatabase::getInstance();

        $inCore = cmsCore::getInstance();
        $cfg = $inCore->loadComponentConfig('pogoda');
        $this->city_id = $cfg['city_id'];
    }

    /**
     * Парсит и заносит данные в БД
     * @param $target
     * @return bool
     */
    private function parse($target){

        if(!$target || !$this->city_id)return false;
        switch($target){
            case 'current':
                $url = "http://api.openweathermap.org/data/2.5/weather?mode=xml&lang=ru&units=metric&id=" . $this->city_id;
                $this->table = 'cms_pogoda_current';
                break;

            case '5_days':
                $url = "http://api.openweathermap.org/data/2.5/forecast?mode=xml&lang=ru&units=metric&id=" . $this->city_id;
                $this->table = 'cms_pogoda_5days';
                break;

            case '16_days':
                $url = "http://api.openweathermap.org/data/2.5/forecast/daily?mode=xml&lang=ru&units=metric&cnt=16&id=" . $this->city_id;
                $this->table = 'cms_pogoda_16days';
                break;

            default:
                $url = "http://api.openweathermap.org/data/2.5/weather?mode=xml&lang=ru&units=metric&id=" . $this->city_id;
                $this->table = 'cms_pogoda_current';
                break;
        }

        $data = simplexml_load_file($url);
        if(!$data)return false;

        $insert = array();

        $insert["xml"] = $data->asXML();
        $insert["city_id"] = $this->city_id;
        $insert["date"] = date("Y-m-d H:i:s");

        $last_insert_id = $this->inDB->insert($this->table, $insert);
        if(!$last_insert_id){return false;}

        $this->inDB->delete("{$this->table}", " `id` < {$last_insert_id} ");

        return true;
    }

    /*
     * задача для CRON
     * парсер текущей погоды  с сайта http://openweathermap.org
     * return bool
     */
    public function parseCurrentWeather(){
        if(!$this->parse('current'))return false;
        return true;
    }

    /*
     * задача для CRON
     * парсер прогноза погоды на 5 дней  с сайта http://openweathermap.org
     * return bool
     */
    public function parse5daysForecastWeather(){
        if(!$this->parse('5_days'))return false;
        return true;
    }

    /*
     * задача для CRON
     * парсер прогноза погоды на 5 дней  с сайта http://openweathermap.org
     * return bool
     */
    public function parse16daysForecastWeather(){
        if(!$this->parse('16_days'))return false;
        return true;
    }

    /*
     * Устанавливает таблицу
     * @param $table str
     */
    public function setTable($target){
        switch($target){
            case 'current':
                $this->table = 'cms_pogoda_current';
                break;

            case '5_days':
                $this->table = 'cms_pogoda_5days';
                break;

            case '16_days':
                $this->table = 'cms_pogoda_16days';
                break;

            default:
                $this->table = 'cms_pogoda_current';
                break;
        }
    }

    /*
     * Выбирает данные с таблиц компонента
     * return bool | array
     */
    public function getWeather(){
        return $this->inDB->get_fields($this->table, " 1=1 ", ' * ', " `id` DESC ");
    }

    /*
     * Выбирает свойства страниц компонента
     * return false | array
     */
    public function getPagesProps($days=0){
        $props = array();
        $where = $days ? " `days` = {$days} " : "1=1";
        $dbProps = $this->inDB->get_table('cms_pogoda_props', $where, '*');
        if(!$dbProps)return false;
        foreach($dbProps as $v){
            $props["{$v['days']}"] = $v;
        }
        return $props;
    }

    /*
     * Сохраняет свойства страниц компонента
     * @param array $props
     * return bool
     */
    public function savePagesProps($props){

        $last_id = $this->inDB->get_field('`cms_pogoda_props`', " 1=1 ORDER BY id DESC ", '`id`');

        $sql = "INSERT INTO `cms_pogoda_props` (`days`, `h1`, `title`, `meta_keys`, `meta_desc`, `before_text`, `after_text`)
                VALUES  ";
        $i = 0;
        foreach($props as $k=>$v){
            $i++;
            $sql .= "( '{$k}' , '{$v['h1']}', '{$v['title']}', '{$v['meta_keys']}', '{$v['meta_desc']}', '{$v['before_text']}', '{$v['after_text']}')";
            if($i < count($props)) $sql .= ', ';
        }

        if($this->inDB->query($sql) && $last_id){
            $this->inDB->delete('`cms_pogoda_props`', " id <= {$last_id} ", 20);
        }

    }
}

?>