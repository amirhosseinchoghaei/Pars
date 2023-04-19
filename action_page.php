<?php

//The name of the folder.
$folder = 'files';

//Get a list of all of the file names in the folder.
$files = glob($folder . '/*');

//Loop through the file list.
foreach($files as $file){
    //Make sure that this is a file and not a directory.
    if(is_file($file)){
        //Use the unlink function to delete the file.
        unlink($file);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Refresh" content="1.5;url=index.php">
</head>

<body>
<center><img src="tenor.gif">
<div style="background-color:red">
<h1 style="color:white"> ! Deleted ! </h1>
</div>

</center>

</center>
</body>

</html>
