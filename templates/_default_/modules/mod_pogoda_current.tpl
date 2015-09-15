<div style="text-align: center; margin: 10px; padding: 10px;">
    {if $current}
        <div style="height: 75px;">
            <img src="http://openweathermap.org/img/w/{$current.weather.icon}.png" title="{$current.weather.value}">
        </div>
        <div>
            <strong style="font-size: 20px; color: #375e93;">{$current.temperature}</strong>
        </div>
    {else}
        <div>Прогноз погоды отсутствует</div>
    {/if}
    <div style="margin-top: 20px;">
        <a href="/pogoda">Подробный прогноз погоды</a>
    </div>
</div>