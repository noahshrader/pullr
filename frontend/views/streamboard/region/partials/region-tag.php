<div
    class="widget-tags-message"            
    ng-style="{'color': region.widgetTags.fontColor, 'font-size': region.widgetTags.fontSize, 'font-family': region.widgetTags.fontStyle, 'font-weight': region.widgetTags.fontWeight, 'position':'absolute'}"
    ng-class='{fontUppercase:region.widgetTags.fontUppercase}'            
    interaction
    draggable
    draggable-widget="tagPreference"
    draggable-region="region"
    draggable-config="{containment:getContainmentByRegion(region)}"
    draggable-fields="{widgetLeftAttribute:'messagePositionX', widgetTopAttribute:'messagePositionY'}"
    resizable
    resizable-config="{containment: getContainmentByRegion(region)}"
    resizable-region="region"
    resizable-callback="onResizeTag"
    tag-type="{{tagPreference.tagType}}"
    resizable-size="{width:tagPreference.width, height: tagPreference.height}"                
    >
<?php echo $content;?>
</div>