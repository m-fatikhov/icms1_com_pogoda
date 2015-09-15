{add_css file='components/pogoda/css/pogoda.css'}
<div class="pg_block" style="width: 100%;">
    <h1 class="con_heading">{if $props.h1}{$props.h1}{else}Прогноз погоды на {$days|spellcount:'день':'дня':'дней'} {$cfg.name_ru}{/if}</h1>
    <div class="pg_before_text">{$props.before_text}</div>
    <div class="pg_content">
        {foreach key=key item=day from=$forecast}
            <div class="pg_16day_day_block">
                <div style="margin:10px;"><strong class="pg_strong">{$fdates.$key}</strong></div>
                <div class="pg_16day_temperature_block">
                    <div>
                        <img src="http://openweathermap.org/img/w/{$day.weather.icon}.png"
                             title="{$day.weather.value}">
                    </div>
                    <div title="Температура воздуха">
                        <strong class="pg_strong" style="color: #2c92f2" title="Днем">{$day.temperature.day} </strong>
                        <strong style="font-size: 20px;"> | </strong>
                        <strong class="pg_strong" title="Ночью">{$day.temperature.night}</strong>
                    </div>
                </div>
                <div style="height: 150px;">
                    <div title="Ветер">
                        <strong class="pg_title">Ветер: </strong>{$day.wind.speed} {$day.wind.direction}
                    </div>
                    <div title="Влажность">
                        <strong class="pg_title">Влажность: </strong>{$day.humidity}
                    </div>
                    <div title="Атмосферное давление">
                        <strong class="pg_title">Атмосферное давление: </strong>{$day.pressure}<br>
                    </div>
                    <div title="Облачность">
                        <strong class="pg_title">Облачность: </strong>{$day.clouds}<br>
                    </div>
                    <div title="Осадки">
                        <strong class="pg_title">Осадки: </strong>{$day.precipitation}
                    </div>
                </div>
            </div>
        {/foreach}
    </div>
    <div class="clear"></div>
    <div style="margin-top: 30px; width: 730px;">
        {if $days == 7}
            <div class="pg_arrow_left" style="float:left;">
                <a href="/pogoda/{$cfg.name_en}na_5_dney.html">Прогноз погоды {$cfg.name_ru} на 5 дней</a>
            </div>
            <div class="pg_arrow_right" style="float:right;">
                <a href="/pogoda/{$cfg.name_en}na_10_dney.html">Прогноз погоды {$cfg.name_ru} на 10 дней</a>
            </div>
        {/if}
        {if $days == 10}
            <div class="pg_arrow_left" style="float:left;">
                <a href="/pogoda/{$cfg.name_en}na_7_dney.html">Прогноз погоды {$cfg.name_ru} на 7 дней</a>
            </div>
            <div class="pg_arrow_right" style="float:right;">
                <a href="/pogoda/{$cfg.name_en}na_14_dney.html">Прогноз погоды {$cfg.name_ru} на 2 недели</a>
            </div>
        {/if}
        {if $days == 14}
            <div class="pg_arrow_left" style="float:left;">
                <a href="/pogoda/{$cfg.name_en}na_10_dney.html">Прогноз погоды {$cfg.name_ru} на 10 дней</a>
            </div>
            <div class="pg_arrow_right" style="float:right;">
                <a href="/pogoda/">Текущая погода {$cfg.name_ru}</a>
            </div>
        {/if}
    </div>
    <div class="clear"></div>
    <div class="pg_after_text">{$props.after_text}</div>
    {if $cfg.show_copyright}
        <div class="pg_copyright">
            <a href="http://openweathermap.org">Openweathermap.org</a>
        </div>
    {/if}
    <div class="clear"></div>
</div>