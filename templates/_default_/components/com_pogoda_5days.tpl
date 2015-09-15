{add_css file='components/pogoda/css/pogoda.css'}
<div class="pg_block" style="width: 100%;">
    <h1 class="con_heading">{if $props.h1}{$props.h1}{else}Прогноз погоды на {$days|spellcount:'день':'дня':'дней'} {$cfg.name_ru}{/if}</h1>
    <div class="pg_before_text">{$props.before_text}</div>
    <div class="pg_content">
        {foreach key=key item=day from=$forecast}
            <div>
                <div class="pg_5day_date"><strong class="pg_strong">{$fdates.$key}</strong></div>
                {foreach key=k item=hour from=$day}
                    <div class="pg_5day_hour_block">
                        <div>
                            <strong class="pg_strong" style="font-size: 15px;">{$k}</strong>
                        </div>
                        <div>
                            <img src="http://openweathermap.org/img/w/{$hour.weather.icon}.png"
                                 title="{$hour.weather.value}">
                        </div>
                        <div title="Температура воздуха">
                            <strong class="pg_strong">{$hour.temperature}</strong>
                        </div>
                        <div title="Ветер">
                            {$hour.wind.speed}<br>{$hour.wind.direction}
                        </div>
                        <div title="Влажность">
                            {$hour.humidity}
                        </div>
                        <div title="Атмосферное давление">
                            {$hour.pressure}<br>
                        </div>
                        <div title="Облачность">
                            {$hour.clouds}<br>
                        </div>
                        <div title="Осадки">
                            {$hour.precipitation}
                        </div>
                    </div>
                {/foreach}
                <div class="clear"></div>
            </div>
        {/foreach}
    </div>
    <div class="clear"></div>
    <div style="margin-top: 30px; width: 730px;">
        {if $days == 3}
            <div class="pg_arrow_left" style="float:left;">
                <a href="/pogoda/">Текущая погода {$cfg.name_ru}</a>
            </div>
            <div class="pg_arrow_right" style="float:right;">
                <a href="/pogoda/{$cfg.name_en}na_5_dney.html">Прогноз погоды {$cfg.name_ru} на 5 дней</a>
            </div>
        {/if}
        {if $days == 5}
            <div class="pg_arrow_left" style="float:left;">
                <a href="/pogoda/{$cfg.name_en}na_3_dnya.html">Прогноз погоды {$cfg.name_ru} на 3 дня</a>
            </div>
            <div class="pg_arrow_right" style="float:right;">
                <a href="/pogoda/{$cfg.name_en}na_7_dney.html">Прогноз погоды {$cfg.name_ru} на 7 дней</a>
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

