<?php
  $kfail="tln-aj-kys1";
  if(isSet($_REQUEST["teema"])){
     if(file_exists("Taring/kysimused/$_REQUEST[teema].kys")){
       $kfail=$_REQUEST["teema"];
     }
  }
  $tfail="kosmos";
  if(isSet($_REQUEST["taust"])){
     if(file_exists("Taring/images/back/$_REQUEST[taust].jpg")){
       $tfail=$_REQUEST["taust"];
     }
  }
  $mangijaid=2;
  if(isSet($_REQUEST["mangijaid"])){
     $mangijaid=intval($_REQUEST["mangijaid"]);
  }
?>
<!doctype html>
<html>
  <head>
    <title>Laud</title>
   <script>
    var xhr=new XMLHttpRequest();
    xhr.onreadystatechange=andmedSaabusid;
    var kohad=[];
    var kysimused=[];
    var kfail="<?php echo $kfail; ?>";
    var tfail="<?php echo $tfail; ?>";
    var t, g;
    var nihex=15, nihey=35;
    var taustapilt = new Image();
    taustapilt.src = 'Taring/images/back/'+tfail+'.jpg';
    var seisundid=["alustab", "veeretab", "astub", "kysib", "kysimusejargne"];
    var seisund=0;
    var mangijaid=<?php echo $mangijaid; ?>
//    var mangijad=[new Mangija(), new Mangija()];  
    var toonid=["red", "blue", "green", "yellow", "gray", "pink", "orange"];
    var mangijad=[];
    for(var i=0; i<mangijaid; i++){
       mangijad.push(new Mangija());
       mangijad[i].toon=toonid[i];
    }    
    var kellekord=0;
//    mangijad[1].toon="red";

    
    function juhukaugus(){
       return -10+20*Math.random();
    }
    function andmedSaabusid(){
       if(xhr.readyState==4){
          console.log(xhr);
          if(isNaN(xhr.responseText.split(";")[0])){
            salvestaKysimused(xhr.responseText);
          } else {
            salvestaKohad(xhr.responseText);
          }
       }
    }
    function salvestaKysimused(t){
       var m=t.split("\n");
       kysimused=[];
       for(var i=0; i<m.length; i++){
          kysimused.push(m[i].split(";"))
       }
       console.log(kysimused);
       seisund=1;
       mangijad[0].veereta(3);
    }
    function salvestaKohad(t){
       var m=t.split('\n');
       kohad=[];
       for(var i=0; i<m.length; i++){
          kohad.push(m[i].split(";").map(Math.round));
       }
       for(var i=0; i<mangijad.length; i++){
          mangijad[i].paigutaKohale();
       }
 //      console.log(kohad);
       joonista();
       
       xhr.open("GET", "Taring/kysimused/"+kfail+".kys", true);
       xhr.send(true);
    }
    function joonistaKohad(){
       g.font="10pt Verdana";
       g.textAlign = 'center';
       for(var i=0; i<kohad.length; i++){
          g.fillStyle="black";
          g.fillText(i+1, kohad[i][0]+nihex, kohad[i][1]+3+nihey);
          g.fillStyle="white";
          g.fillText(i+1, kohad[i][0]+nihex+2, kohad[i][1]+3+nihey+2);
          g.beginPath();
          g.arc(kohad[i][0]+nihex, kohad[i][1]+nihey, 15, 0, 2*Math.PI, true);
          g.stroke();
       }
    }
    function joonistaMangijad(){
       for(var i=0; i<mangijad.length; i++){
          g.beginPath();
          g.fillStyle=mangijad[i].toon;
          g.arc(mangijad[i].x, mangijad[i].y, 5, 0, 2*Math.PI, true);
          g.fill();
       }
       for(var i=0; i<mangijad.length; i++){
          g.beginPath();
          g.fillStyle=mangijad[i].toon;
          g.arc(i*40+nihex, 20, 5, 0, 2*Math.PI, true);
          g.fill();           
       }
       g.beginPath();
       g.rect(kellekord*40+nihex-10, 10, 20, 20);
       g.stroke();
    }
    function joonistaKysimus(knr){
      g.fillStyle="white";
      g.fillRect(0, 10, 800, 30);
      g.fillRect(0, 110, 800, 30);
      g.fillRect(0, 210, 800, 30);
      g.fillRect(0, 310, 800, 30);
      g.fillRect(0, 410, 800, 30);
      g.font="12pt Verdana";
      g.fillStyle="black";
      g.fillText(kysimused[knr][1], 345, 30);
      g.fillText(kysimused[knr][2], 345, 130);
      g.fillText(kysimused[knr][3], 345, 230);
      g.fillText(kysimused[knr][4], 345, 330);
      g.fillText(kysimused[knr][5], 345, 430);
    }
    function joonista(){
      if(seisundid[seisund]!="kysib"){
        g.globalAlpha=1;
      } else {
       g.fillStyle="white";
       g.fillRect(0, 0, t.width, t.height);
        g.globalAlpha=0.5;
      }
      g.drawImage(taustapilt, 0, 0, t.width, t.height);
      joonistaKohad();
      joonistaMangijad();
      g.globalAlpha=1;
      if(seisundid[seisund]=="kysib"){
         joonistaKysimus(mangijad[kellekord].koht);
      }
    }    
    function algus(){
       t=document.getElementById("tahvel");
       g=t.getContext("2d");
       xhr.open("GET", "Taring/koord.txt", true);
       xhr.send(true);
       taustapilt.onload =joonista;
       setInterval(liigu, 1000);
    }


    function Mangija(){
       this.koht=0; 
       this.vanakoht=0;
       this.uuskoht=0;
       this.toon="blue"; 
       this.x=0; 
       this.y=0;
       this.veereta=function(kogus){
          this.vanakoht=this.koht;
          this.uuskoht=this.koht+kogus;
          seisund=2;
       }       
       this.astu=function(){
          if(this.koht<this.uuskoht){
            this.koht++;
            this.paigutaKohale();
          }
       }
       this.paigutaKohale=function(){
          this.x=kohad[this.koht][0]+juhukaugus()+nihex;
          this.y=kohad[this.koht][1]+juhukaugus()+nihey;
       }
       this.tagasi=function(){
          this.koht=this.uuskoht=this.vanakoht;
          this.paigutaKohale();
       }
       this.kohal=function(){
          return this.koht==this.uuskoht;
       }
    }
    function arvuta(){
      if(seisundid[seisund]=="astub"){
        if(!mangijad[kellekord].kohal()){
          mangijad[kellekord].astu();
          joonista();
          if(mangijad[kellekord].kohal()){
            seisund=3;
            joonista();            
            //alert(kysimused[mangijad[kellekord].koht][1]);
          }
        }
      }
    }
    function kontrolliVastust(e){
         var tahvlikoht=t.getBoundingClientRect();
         var hx=(e.clientX-tahvlikoht.left)*(750/tahvlikoht.width);
         var hy=(e.clientY-tahvlikoht.top)*(550/tahvlikoht.height);
         console.log(hy);
         if(hy>500 || hy<100){return false;}
         var valik=Math.floor(hy/100);
         console.log(valik, kellekord, mangijad[kellekord]);
         if(valik!=kysimused[mangijad[kellekord].koht][6]){
            mangijad[kellekord].tagasi();
            seisund=1;
            joonista();
            console.log("tagasi");
            console.log(mangijad[kellekord]);
         }
         return true;
    }
    function jargmine(e){
//       console.log(mangijad[kellekord].kohal());
       if(!mangijad[kellekord].kohal()){return;}
       if(!kontrolliVastust(e)){return; } //ei vastanud
       kellekord++;
       if(kellekord>=mangijad.length){kellekord=0;}
       mangijad[kellekord].veereta(5);
    }
    function liigu(){
      if(kohad.length>0){
        arvuta();
        joonista();
      }
    }
    function hiirAlla(e){
       jargmine(e);
    }
   </script>
   <style>
      body, html {height: 97%; width: 97%;}
   </style>
  </head>
  <body onload="algus()">
    <canvas id="tahvel" width="750" height="550" style="background-color: white; width: 100%; height: 100%"
      onmousedown="hiirAlla(event)"></canvas>
  </body>
</html>