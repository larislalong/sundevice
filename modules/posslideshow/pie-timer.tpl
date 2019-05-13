{assign var='pietimer' value=($slideOptions.pause_time/2.1) }
<style type="text/css">
.pie_out {
  position: absolute;
  opacity: 0;
  bottom: 15px;
  right: 15px;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background-color: #000;
  overflow: hidden;
  z-index: 17;
  -webkit-animation: piein 1000ms ease forwards ; 
  -moz-animation: piein 1000ms ease forwards ; 
  -ms-animation: piein 1000ms ease forwards ; 
  -o-animation: piein 1000ms ease forwards ; 
  animation: piein 1000ms ease forwards ;
}
.demi-droit,
.demi-gauche {
  position: absolute;
  top: 0;
  bottom: 0;
  background-color: transparent;
  overflow: hidden;
}
.demi-droit:after,
.demi-gauche:after {
  content: '';
  position: absolute;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: #c92c61;
}
.demi-droit {
  left: 50%;
  right: 0;
}
.demi-droit:after {
  right: 100%;
  transform-origin: center right;
  animation: turn {if $slideOptions.pause_time != ''}{$pietimer}{else}2500{/if}ms linear forwards;
}
.demi-gauche {
  left: 0;
  right: 50%;
}
.demi-gauche:after {
  left: 100%;
  transform-origin: center left;
  animation: turn {if $slideOptions.pause_time != ''}{$pietimer}{else}2500{/if}ms {if $slideOptions.pause_time != ''}{$pietimer}{else}2500{/if}ms linear forwards;
}
@-moz-keyframes turn {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(180deg);
  }
}
@-webkit-keyframes turn {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(180deg);
  }
}
@-o-keyframes turn {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(180deg);
  }
}
@keyframes turn {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(180deg);
  }
}
</style>