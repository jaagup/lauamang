<!doctype html>
<html>
  <head>
    <title>Lauamängu seaded</title>
  </head>
  <body>
     <h1>Küsimustega lauamäng</h1>
     <form action="laud1.php">
       Mitu mängijat?<br />
       <input type="radio" name="mangijaid" value="1" />1
       <input type="radio" name="mangijaid" value="2" checked="checked"/>2
       <input type="radio" name="mangijaid" value="3" />3
       <input type="radio" name="mangijaid" value="4" />4
       <br />
       Teema:<br />
       <select name="teema">
         <?php
           $fd=scandir("Taring/kysimused");
           sort($fd);
           foreach($fd as $f){
             $m=explode(".", $f);
             if(strlen($m[0])>1){
               echo "<option value='$m[0]' >$m[0]</option>\n";
             }
           }
         ?>
       </select>
       <input type="submit" value="Alusta" />
     </form>
  </body>
</html>