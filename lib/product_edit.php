<?
require_once($_SERVER["DOCUMENT_ROOT"].'/lib/functions.php');

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    /*
    */
    if( !isset($_POST["status"]) )
        $_POST["status"] = 1;
    else
        unset($_POST["status"]);


    if(!empty($_FILES["image"]["tmp_name"]) && $_FILES["image"]["size"] > 0)
    {
        $info_file = new SplFileInfo($_FILES["image"]["name"]);
        $name_file = uniqid().'.'.$info_file->getExtension();
        $_POST['image'] = $name_file;

        $path = '../'.DIR_IMG.'/';

        if( move_uploaded_file($_FILES["image"]["tmp_name"], $path.$name_file) )
        {
            if($_POST["edit"])
                delete_image_db($pdo, $_POST["edit"]);
        }
    }

    if($_POST["edit"])
    {
        edit_product_db($pdo);
    }
    else
    {
        add_product_db($pdo);
    }

}
else
    redirect("/product_edit.php");