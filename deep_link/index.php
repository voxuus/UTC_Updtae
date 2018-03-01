<html>
<head> 
</head>
<body>
<script type="text/javascript">
  
var IS_IPAD = navigator.userAgent.match(/iPad/i) != null,
IS_IPHONE = !IS_IPAD && ((navigator.userAgent.match(/iPhone/i) != null) || (navigator.userAgent.match(/iPod/i) != null)),
IS_IOS = IS_IPAD || IS_IPHONE,
IS_ANDROID = !IS_IOS && navigator.userAgent.match(/android/i) != null,
IS_MOBILE = IS_IOS || IS_ANDROID;
if(IS_IPAD||IS_IPHONE)
{
 window.location='https://itunes.apple.com/us/app/u-nite/id1172143199?ls=1&mt=8';
}
else
if(IS_MOBILE)
{
 
 window.location='https://play.google.com/store/apps/details?id=com.utc.app';
 document.write("Content Not Available");
}
else if(!IS_MOBILE)
{
  
 window.location='';
 document.write("Content Not Available");
}


</script>
</body>
</html> 