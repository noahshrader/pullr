<?php
use yii\helpers\Html;
use common\assets\CommonAsset;
use common\assets\streamboard\StreamboardCommonAsset;
use common\assets\streamboard\StreamboardSourceAsset;

CommonAsset::register($this);
StreamboardCommonAsset::register($this);
StreamboardSourceAsset::register($this);

$this->title = 'Streamboard';
?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <?= $this->render('../baseHead') ?>
    <body>
		<?php $this->beginBody() ?>
		<?= $content ?>
		<?php $this->endBody() ?>
		<script>
		(function(){"use strict";function L(e,t,n){if("addEventListener"in window){e.addEventListener(t,n,false)}else if("attachEvent"in window){e.attachEvent("on"+t,n)}}function A(e){return g+"["+b+"]"+" "+e}function O(e){if(m&&"object"===typeof window.console){console.log(A(e))}}function M(e){if("object"===typeof window.console){console.warn(A(e))}}function _(){O("Initialising iFrame");D();B();H("background",n);H("padding",s);U();q();j();z();R();it("init","Init message from host page")}function D(){function u(e){return"true"===e?true:false}var t=d.substr(y).split(":");b=t[0];r=undefined!==t[1]?Number(t[1]):r;o=undefined!==t[2]?u(t[2]):o;m=undefined!==t[3]?u(t[3]):m;v=undefined!==t[4]?Number(t[4]):v;w=undefined!==t[5]?u(t[5]):w;e=undefined!==t[6]?u(t[6]):e;i=t[7];h=undefined!==t[8]?t[8]:h;n=t[9];s=t[10];T=undefined!==t[11]?Number(t[11]):T}function P(e,t){if(-1!==t.indexOf("-")){M("Negative CSS value ignored for "+e);t=""}return t}function H(e,t){if(undefined!==t&&""!==t&&"null"!==t){document.body.style[e]=t;O("Body "+e+' set to "'+t+'"')}}function B(){if(undefined===i){i=r+"px"}P("margin",i);H("margin",i)}function j(){document.documentElement.style.height="";document.body.style.height="";O('HTML & body height set to "auto"')}function F(){L(window,"resize",function(){it("resize","Window resized")})}function I(){L(window,"click",function(){it("click","Window clicked")})}function q(){if(c!==h){if(!(h in nt)){M(h+" is not a valid option for heightCalculationMethod.");h="bodyScroll"}O('Height calculation method set to "'+h+'"')}}function R(){if(true===e){F();I();V()}else{O("Auto Resize disabled")}}function U(){var e=document.createElement("div");e.style.clear="both";e.style.display="block";document.body.appendChild(e)}function z(){if(w){O("Enable public methods");window.parentIFrame={close:function(){it("close","parentIFrame.close()",0,0)},getId:function(){return b},reset:function(){ut("parentIFrame.size")},scrollTo:function(t,n){at(t,n,"scrollTo")},sendMessage:function(t,n){at(0,0,"message",t,n)},setHeightCalculationMethod:function(t){h=t;q()},setTargetOrigin:function(t){O("Set targetOrigin: "+t);S=t},size:function(t,n){var r=""+(t?t:"")+(n?","+n:"");st();it("size","parentIFrame.size("+r+")",t,n)}}}}function W(){if(0!==v){O("setInterval: "+v+"ms");setInterval(function(){it("interval","setInterval: "+v)},Math.abs(v))}}function X(e){function t(e){if(e.height===undefined||e.width===undefined||0===e.height||0===e.width){O("Attach listerner to "+e.src);L(e,"load",function(){it("imageLoad","Image loaded")})}}e.forEach(function(e){if(e.type==="attributes"&&e.attributeName==="src"){t(e.target)}else if(e.type==="childList"){var n=e.target.querySelectorAll("img");Array.prototype.forEach.call(n,function(e){t(e)})}})}function V(){function t(){var t=document.querySelector("body"),n={attributes:true,attributeOldValue:false,characterData:true,characterDataOldValue:false,childList:true,subtree:true},r=new e(function(e){it("mutationObserver","mutationObserver: "+e[0].target+" "+e[0].type);X(e)});O("Enable MutationObserver");r.observe(t,n)}var e=window.MutationObserver||window.WebKitMutationObserver;if(e){if(0>v){W()}else{t()}}else{M("MutationObserver not supported in this browser!");W()}}function $(){function e(e){function n(e){var n=/^\d+(px)?$/i;if(n.test(e)){return parseInt(e,t)}var i=r.style.left,s=r.runtimeStyle.left;r.runtimeStyle.left=r.currentStyle.left;r.style.left=e||0;e=r.style.pixelLeft;r.style.left=i;r.runtimeStyle.left=s;return e}var r=document.body,i=0;if("defaultView"in document&&"getComputedStyle"in document.defaultView){i=document.defaultView.getComputedStyle(r,null);i=null!==i?i[e]:0}else{i=n(r.currentStyle[e])}return parseInt(i,t)}return document.body.offsetHeight+e("marginTop")+e("marginBottom")}function J(){return document.body.scrollHeight}function K(){return document.documentElement.offsetHeight}function Q(){return document.documentElement.scrollHeight}function G(){var e=document.querySelectorAll("body *"),t=e.length,n=0,r=(new Date).getTime();for(var i=0;i<t;i++){if(e[i].getBoundingClientRect().bottom>n){n=e[i].getBoundingClientRect().bottom}}r=(new Date).getTime()-r;O("Parsed "+t+" HTML elements");O("LowestElement bottom position calculated in "+r+"ms");return n}function Y(){return[$(),J(),K(),Q()]}function Z(){return Math.max.apply(null,Y())}function et(){return Math.min.apply(null,Y())}function tt(){return Math.max($(),G())}function rt(){return Math.max(document.documentElement.scrollWidth,document.body.scrollWidth)}function it(e,t,n,r){function a(){if(!(e in{reset:1,resetPage:1,init:1})){O("Trigger event: "+t)}}function l(){f=i;k=s;at(f,k,e)}function c(){return N&&e in u}function p(){function e(e,t){var n=Math.abs(e-t)<=T;return!n}i=undefined!==n?n:nt[h]();s=undefined!==r?r:rt();return e(f,i)||o&&e(k,s)}function d(){return!(e in{init:1,interval:1,size:1})}function v(){return h in E}function m(){O("No change in size detected")}function g(){if(d()&&v()){ut(t)}else if(!(e in{interval:1})){a();m()}}var i,s;if(!c()){if(p()){a();st();l()}else{g()}}else{O("Trigger event cancelled: "+e)}}function st(){if(!N){N=true;O("Trigger event lock on")}clearTimeout(C);C=setTimeout(function(){N=false;O("Trigger event lock off");O("--")},a)}function ot(e){f=nt[h]();k=rt();at(f,k,e)}function ut(e){var t=h;h=c;O("Reset trigger event: "+e);st();ot("reset");h=t}function at(e,t,n,r,i){function s(){if(undefined===i){i=S}else{O("Message targetOrigin: "+i)}}function o(){var s=e+":"+t,o=b+":"+s+":"+n+(undefined!==r?":"+r:"");O("Sending message to host page ("+o+")");x.postMessage(g+o,i)}s();o()}function ft(e){function t(){return g===(""+e.data).substr(0,y)}function n(){d=e.data;x=e.source;_();l=false;setTimeout(function(){p=false},a)}function r(){if(!p){O("Page size reset by host page");ot("resetPage")}else{O("Page reset ignored by init")}}function i(){return e.data.split("]")[1]}function s(){return"iFrameResize"in window}function o(){return e.data.split(":")[2]in{"true":1,"false":1}}if(t()){if(l&&o()){n()}else if("reset"===i()){r()}else if(e.data!==d&&!s()){M("Unexpected message ("+e.data+")")}}}var e=true,t=10,n="",r=0,i="",s="",o=false,u={resize:1,click:1},a=128,f=1,l=true,c="offset",h=c,p=true,d="",v=32,m=false,g="[iFrameSizer]",y=g.length,b="",w=false,E={max:1,scroll:1,bodyScroll:1,documentElementScroll:1},S="*",x=window.parent,T=0,N=false,C=null,k=1;var nt={offset:$,bodyOffset:$,bodyScroll:J,documentElementOffset:K,scroll:Q,documentElementScroll:Q,max:Z,min:et,grow:Z,lowestElement:tt};L(window,"message",ft)})()
       </script> 
    </body>
</html>
<?php $this->endPage() ?>