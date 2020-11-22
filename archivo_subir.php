<meta charset="UTF-8">

<?php
	$str_pagina = "";
	if ($_FILES["arch"]["error"] > 0){
		$str_pagina.="Error: " . $_FILES["arch"]["error"] . "<br>";
	}
	else  {
		$str_pagina.= "Nombre: " . $_FILES["arch"] ["name"] . "<br>";
		$str_pagina.= "Tipo: " . $_FILES["arch"]["type"] . "<br>";
		$str_pagina.= "Tamaño: " . ($_FILES["arch"]["size"] / 1024) . " kB<br>";
		$str_pagina.= "Guardado en: " . $_FILES["arch"]["tmp_name"];
	}
	echo $str_pagina;
        if (!file_exists('subidos/')) {
           mkdir('subidos/',0777,true);
        }
        move_uploaded_file($_FILES["arch"]["tmp_name"],"subidos/".$_FILES["arch"]["name"]);
        echo "Guardado en: " . "subidos/" . $_FILES["arch"]["name"];
?>
<input type='button'value='Regresar al index' onclick="document.location.href='index.php';"/>
