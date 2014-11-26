// google fonts
function requireGoogleFont(fontFamily){
   if (!fontFamily){
       return;
   }
   WebFont.load({
       google: {
           families: [fontFamily]
        }
   });
}
