<?php
  $teade="";
  if(isSet($_FILES["f1"])){
   if($_REQUEST["p1"]=="katkys"){
    $teade="OK";
    echo $_FILES["f1"]["name"];
    $fnimi=explode(".", $_FILES["f1"]["name"]);
    if(count($fnimi)>2){
      $teade="Failinimi sisaldab rohkem kui ühte punkti";
    } else {
      if($fnimi[1]!="kys"){
        $teade="Faili laiend pole kys";
      } else {
        $teade="Fail ".$fnimi[0];
        move_uploaded_file($_FILES["f1"]["tmp_name"], "Taring/kysimused/$fnimi[0].kys");
        $teade="Fail ".$fnimi[0]." salvestatud. <br />".
         "<a href='laud1.php?teema=$fnimi[0]'>Ava</a>";
      }
    }
   } else {
     $teade="Vigane parool";
   }
  }
?>
<!doctype html>
<html>
  <head>
    <title>Küsimuste faili laadimine</title>
  </head>
  <body>
    <?php echo $teade; ?>
    <form action="?" method="post" enctype="multipart/form-data">
       <dl>
         <dt>Vali fail:</dt>
         <dd><input type="file" name="f1" />
         <dt>Parool:</dt>
         <dd><input type="password" name="p1"/>
         <dt><input type="submit" value="Saada" /></dt>
       </dl>
    </form>
  </body>
</html>