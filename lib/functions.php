<?php

// config

define('DIR_IMG', 'img');
define('DEFAULT_IMG', 'no-product.jpg');

session_start();

// DB

function connect_db()
{
    $driver = 'mysql';
    $host = 'localhost';
    $db_name = 'catalog';
    $db_user = 'root';
    $db_password = '';
    $charset = 'utf8';
    $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
    $dsn = "$driver:host=$host;dbname=$db_name;charset=$charset";
    $pdo = new PDO($dsn, $db_user, $db_password, $options);
    return $pdo;
}

$pdo = connect_db();

function get_categories_db($pdo){
    $sql = "SELECT * FROM `categories`";
    $statement = $pdo->query($sql);
    if( $statement->rowCount() > 0 )
    {
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    else
        return false;
}

function get_product_db($pdo, $id_out)
{
    if( is_int( $id_out = (int)$id_out ) )
    {
        $sql = "SELECT * FROM `products` WHERE `id` = :id";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(":id", $id_out, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    else
        return false;
}

function get_products_db($pdo, $field_in = false, $category_id_out = false)
{
    $sql = "SELECT * FROM `products` WHERE `status` = '0'";

    if( $category_id_out && $field_in && is_int( $category_id_out = (int)$category_id_out ) )
    {
        $sql = $sql . " AND `$field_in` = :$field_in";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(":$field_in", $category_id_out, PDO::PARAM_INT);
        $statement->execute();
    }
    else
    {
        $statement = $pdo->query($sql);
    }

    if( $statement->rowCount() > 0 )
    {
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    else
        return false;
    
}

function get_admin_products_db($pdo)
{
    $sql = "SELECT p.id, p.name, p.description, p.text, p.image, p.status, c.name AS category
        FROM products p INNER JOIN categories c
            ON p.categories_id = c.id
                ORDER BY p.id DESC";

    $statement = $pdo->query($sql);

    if( $statement->rowCount() > 0 ) {
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    else
        return false;
}

function change_status_product_db($pdo, $status, $status_value)
{
    $sql = "UPDATE products SET status = '$status_value' WHERE id = :id";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':id', $_GET[$status], PDO::PARAM_INT);
    $statement->execute();
}

function delete_image_db($pdo, $product_id)
{
    $sql_foto = "SELECT `image` FROM `products` WHERE id = :id";
    $statement = $pdo->prepare($sql_foto);
    $statement->bindValue(':id', $product_id, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if($result['image']){

        $path = $_SERVER["DOCUMENT_ROOT"].'/'.DIR_IMG.'/'.$result['image'];

        if( file_exists( $path ) )
             unlink ($path);
    }
}

function delete_product_db($pdo)
{
    delete_image_db($pdo, $_GET["delete"]);
    
    $sql = "DELETE FROM `products` WHERE `id` = :id";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':id', $_GET["delete"], PDO::PARAM_INT);
    $statement->execute();
}

function add_product_db($pdo)
{
    $fileds = "";
    $values = "";
    $toggle = true;

    foreach ($_POST as $key => $value) {

        if($toggle)
        {
            $fileds .= $key;
            $values .= ":$key";
            $toggle = false;
        }
        else
        {
            $fileds .= ", $key";
            $values .= ", :$key";
        }
    }

    $sql = "INSERT INTO products ($fileds) VALUES ($values)";
    
    $statement = $pdo->prepare($sql);

    if( $statement->execute($_POST) )
    {

        $_SESSION['add_product_success'] = 'Товар успешно обновлен';
        redirect("/admin.php");
        return true;
    }
    else
    {
        $_SESSION['add_product_error'] = 'При добавлении товара произошла ошибка';
        redirect("/admin.php");
        return false;
    }
}

function edit_product_db($pdo)
{
    if( isset($_POST["edit"]) ){
        $_POST["id"] = $_POST["edit"];
        unset($_POST["edit"]);
    }

    $sql = "UPDATE products SET";
    $cnt = true;

    foreach($_POST as $key => $value)
    {
      if($cnt)
      {
        $sql .= " {$key} = :{$key}";
        $cnt = false;
      }
      else
        $sql .= ", {$key} = :{$key}";
    }

    $sql .= " WHERE id = :id";


    $statement = $pdo->prepare($sql);
    if($statement->execute($_POST))
    {

        $_SESSION['add_product_success'] = 'Товар успешно отредактирован';
        redirect("/admin.php");

    }
    else
    {
        $_SESSION['add_product_error'] = 'При редактировании товара произошла ошибка';
        redirect("/admin.php");
    }

}

// =================================

// View

function show_categories()
{
    global $pdo;
    ?>

        <h6>Категории:</h6>

        <ul class="nav nav-pills mb-4">

            <?php if( is_array ( $categories = get_categories_db($pdo) ) ) {?>

                <li class="nav-item">
                    <a class="nav-link<?php if( !isset($_GET["category"]) ){?> active<?php }?>"
                    href="<?php echo $_SERVER['PHP_SELF']?>">
                        Все
                    </a>
                </li>

                <?php foreach($categories as $category){?>

                    <li class="nav-item">
                        <a class="nav-link<?php if( isset($_GET["category"]) && $_GET["category"] == $category["id"] ){?> active<?php }?>"
                        href="<?php echo $_SERVER['PHP_SELF'].'?category='.$category["id"]?>">
                            <?php echo $category["name"]?>
                        </a>
                    </li>

                <?php } ?>

            <?php } else echo '<p>Нет категорий</p>';?>

        </ul>

    <?
}

function show_products()
{
    global $pdo;
    ?>
        
        <div class="row">

            <?php if( is_array( $products = get_products_db($pdo, 'categories_id', $_GET["category"]) ) ) {?>

                <?php foreach($products as $product){
                    if(!$product["image"])
                        $product["image"] = DEFAULT_IMG;
                    ?>

                <div class="col-md-3 col-sm-4 col-6">
                    <div class="card">
                        <img src="<?php echo DIR_IMG?>/<?php echo $product["image"]?>" class="card-img-top" alt="<?php echo $product["name"]?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $product["name"]?></h5>
                            <p class="card-text"><?php echo $product["description"]?></p>
                            <a href="<?php echo $_SERVER['PHP_SELF'].'?product='.$product["id"]?>" class="btn btn-primary stretched-link">Подробнее...</a>
                        </div>
                    </div>
                </div>

                <?php } ?>

            <?php } else echo '<p>В данной категории нет товаров</p>';?>

        </div>

    <?
}

function show_product()
{
    global $pdo;
    ?>

        <div class="row">

            <?php if( is_array( $products = get_products_db($pdo, 'id', $_GET["product"]) ) ) {?>

                <?php foreach($products as $product){
                    if(!$product["image"])
                        $product["image"] = DEFAULT_IMG;
                    ?>

                    <div class="col-12">
                        <div class="card">
                            <div class="row">
                                <div class="col-4">
                                    <img src="<?php echo DIR_IMG?>/<?php echo $product["image"]?>" class="card-img-top" alt="<?php echo $product["name"]?>">
                                </div>
                                <div class="col-8">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $product["name"]?></h5>
                                        <p class="card-text"><?php echo $product["text"]?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } ?>

            <?php } else echo '<p>Товар отсутсвует</p>';?>

        </div>

    <?
}

function redirect($path)
{
  header("Location: $path");
  die;
}