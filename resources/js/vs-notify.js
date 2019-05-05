/*=============================================================================
  Created by NxtChg (admin@nxtchg.com), 2017. License: Public Domain.
=============================================================================*/
var s = document.createElement("style"); s.type = 'text/css';

document.getElementsByTagName('head')[0].appendChild(s);

s.innerHTML =
		'.vs-notify{ position:fixed; width:400px; z-index:9999; }'+
		'.vs-notify .ntf{ font-size:16px; padding:10px; margin:0 5px 5px; color:#fff; background:#44A4FC; border-left:5px solid #187FE7; box-sizing:border-box; text-align:left; cursor:pointer; }'+
		'.vs-notify .warn   { background:#ffb648; border-left-color:#f48a06; }'+
		'.vs-notify .error  { background:#E54D42; border-left-color:#B82E24; }'+
		'.vs-notify .success{ background:#68CD86; border-left-color:#42A85F; }'+

		'.ntf-left-enter-active, .ntf-left-leave-active, .ntf-right-enter-active, .ntf-right-leave-active, .ntf-top-enter-active, .ntf-top-leave-active,'+
		'.ntf-bottom-enter-active, .ntf-bottom-leave-active{ transition: all 0.3s; }'+
		'.ntf-left-enter,  .ntf-left-leave-to { opacity:0; transform:translateX(-300px); }'+
		'.ntf-right-enter, .ntf-right-leave-to{ opacity:0; transform:translateX(300px); }'+
		'.ntf-fade-enter-active, .ntf-fade-leave-active{ transition: opacity 0.5s; }'+
		'.ntf-fade-enter, .ntf-fade-leave-to{ opacity: 0; }'+
		'.ntf-top-enter,  .ntf-top-leave-to{ opacity:0; transform: translateY(-120px); }'+
		'.ntf-bottom-enter, .ntf-bottom-leave-to{ opacity:0; transform: translateY(120px); }';
//_____________________________________________________________________________

