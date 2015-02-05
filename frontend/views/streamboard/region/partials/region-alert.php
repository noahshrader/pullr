<div ng-show="region.toShow.alert.message && region.toShow.alert.type == preference.preferenceType  && region.widgetType == 'widget_alerts'"
    class='widget-alerts {{preference.preferenceType}}'
    interaction
    draggable
    draggable-widget="preference"
    draggable-region="region"
    draggable-config="{containment:getRegionSelector(region) }"
    draggable-fields="{widgetLeftAttribute:'imagePositionX', widgetTopAttribute:'imagePositionY'}"
    resizable
    resizable-config="{containment: getRegionSelector(region)}"
    resizable-callback="onResizeAlertImage"
    alert-type="{{preference.preferenceType}}"
    resizable-region="region"
    resizable-size="{width:preference.imageWidth, height: preference.imageHeight}"
    >
    <img ng-src="{{region.toShow.alert.image}}"
        ng-show='region.toShow.alert.image != null'
        ng-hide='region.toShow.alert.preference.hideAlertImage'
        ng-class='region.toShow.alert.animationDirection'
    />
</div>
<div
    class="widget-alerts-message {{region.toShow.alert.animationDirection}}"
    ng-show="region.toShow.alert.message && region.widgetType == 'widget_alerts' && region.toShow.alert.type == preference.preferenceType && region.toShow.alert.preference.hideAlertText==false"
    ng-style="{'color': region.toShow.alert.preference.fontColor, 'font-size': region.toShow.alert.preference.fontSize, 'font-family': region.toShow.alert.preference.fontStyle, 'font-weight': region.toShow.alert.preference.fontWeight, 'position':'absolute', 'text-align':region.toShow.alert.preference.textAlignment}"
    ng-class='{fontUppercase:region.toShow.alert.preference.fontUppercase, textShadow:region.toShow.alert.preference.textShadow}'
    interaction
    draggable
    draggable-widget="preference"
    draggable-region="region"
    draggable-config="{containment:getRegionSelector(region)}"
    draggable-fields="{widgetLeftAttribute:'messagePositionX', widgetTopAttribute:'messagePositionY'}"
    resizable
    resizable-config="{containment: getRegionSelector(region)}"
    alert-type="{{preference.preferenceType}}"
    resizable-region="region"
    resizable-callback="onResizeAlertMessage"
    resizable-size="{width:preference.messageWidth, height: preference.messageHeight}">
    <span ng-bind-html="formatMsgHtml(region.toShow.alert.message)"></span>
</div>