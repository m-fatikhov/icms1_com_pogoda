{add_css file='components/pogoda/css/pogoda.css'}
<div class="pg_block">
    <h1 class="con_heading">{if $props.h1}{$props.h1}{else}Текущая погода {$cfg.name_ru}{/if}</h1>
    <div class="pg_before_text">{$props.before_text}</div>
    <div class="pg_content">
        <div class="pg_current_div">
            <div class="pg_current_div" style="height: 75px;">
                <img src="http://openweathermap.org/img/w/{$current.weather.icon}.png" title="{$current.weather.value}">
            </div>
            <div class="pg_current_temperature">
                <strong class="pg_strong">{$current.temperature}</strong>
            </div>
        </div>
        <div style="width: 65%;">
            <div>
                <strong class="pg_title">Влажность: </strong>{$current.humidity}
            </div>
            <div>
                <strong class="pg_title">Атмосферное давление: </strong>{$current.pressure}
            </div>
            <div>
                <strong class="pg_title">Ветер: </strong>{$current.wind.speed} {$current.wind.direction}
            </div>
            <div>
                <strong class="pg_title">Облачность: </strong>{$current.clouds}
            </div>
            <div>
                <strong class="pg_title">Осадки: </strong>{$current.precipitation}
            </div>
        </div>
        <div class="clear"></div>
        <div>
            <p> Обновлено: {$current.lastupdate}</p>
        </div>
    </div>
    <div style="margin-top: 50px; width: 730px;">
            <div class="pg_arrow_right" style="float:right;">
                <a href="/pogoda/{$cfg.name_en}na_3_dnya.html">Прогноз погоды {$cfg.name_ru} на 3 дня</a>
            </div>
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
