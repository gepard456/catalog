<?php
    require_once($_SERVER["DOCUMENT_ROOT"].'/lib/functions.php');

    if( isset($_GET['forbid']) && is_int( $_GET["forbid"] = (int)$_GET["forbid"] ) )
        change_status_product_db($pdo, 'forbid', 1);

    if( isset($_GET['allow']) && is_int( $_GET["allow"] = (int)$_GET["allow"] ) )
        change_status_product_db($pdo, 'allow', 0);

    if( isset($_GET['delete']) && is_int( $_GET["delete"] = (int)$_GET["delete"] ) )
        delete_product_db($pdo);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Админка</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="css/app.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css" integrity="2hfp1SzUoho7/TsGGGDaFdsuuDL0LX2hnUp6VkX3CUQ2K4K+xjboZdsXyp4oUHZj" crossorigin="anonymous">
</head>
<body>

    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                Каталог
            </a>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">Админка</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header"><h3>Товары</h3></div>

                            <?php
                            /** Flash success**/
                            if(isset($_SESSION['add_product_success']))
                            {
                            ?>
                                <div class="alert alert-success" role="alert">
                                    <?php echo $_SESSION['add_product_success']?>
                                </div>
                            <?php
                            unset($_SESSION['add_product_success']);
                            }
                            ?>

                            <?php
                            /** Flash error **/
                            if(isset($_SESSION['add_product_error']))
                            {
                            ?>
                                <div class="alert alert-danger" role="alert">
                                <?php echo $_SESSION['add_product_error']?>
                                </div>
                            <?php
                              unset($_SESSION['add_product_error']);
                            }
                            ?>

                            <a href="product_edit.php" class="btn btn-outline-success ml-4 mt-4">+ Добавить товар</a>

                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Фото</th>
                                            <th>Название</th>
                                            <th>Описание</th>
                                            <th>Текст</th>
                                            <th>Категория</th>
                                            <th>Действия</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php if( is_array ( $products = get_admin_products_db($pdo) ) ) {?>

                                            <?php foreach($products as $product){

                                                if(!$product["image"])
                                                    $product["image"] = DEFAULT_IMG;
                                                ?>

                                                <tr>
                                                    <td>
                                                        <img src="<?php echo DIR_IMG?>/<?php echo $product["image"]?>" alt="<?php echo $product["name"]?>" class="img-fluid" width="64" height="64">
                                                    </td>
                                                    <td><?php echo $product["name"]?></td>
                                                    <td><?php echo $product["description"]?></td>
                                                    <td><?php echo $product["text"]?></td>
                                                    <td><?php echo $product["category"]?></td>
                                                    <td>
                                                        
                                                        <a href="product_edit.php?edit=<?=$product['id']?>" class="btn btn-info">Редактировать</a>

                                                        <?php if($product['status'] == 0){?>
                                                            <a href="<?php echo $_SERVER['PHP_SELF'];?>?forbid=<?=$product['id']?>" class="btn btn-warning">Скрыть</a>
                                                        <?php } else {?>
                                                            <a href="<?php echo $_SERVER['PHP_SELF'];?>?allow=<?=$product['id']?>" class="btn btn-success">Показать</a>
                                                        <?php } ?>

                                                        <a href="<?php echo $_SERVER['PHP_SELF'];?>?delete=<?=$product['id']?>" onclick="return confirm('are you sure?')" class="btn btn-danger">Удалить</a>
                                                    </td>
                                                </tr>

                                            <?php } ?>

                                        <?php } ?>



                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js" integrity="sha384-THPy051/pYDQGanwU6poAc/hOdQxjnOEXzbT+OuUAFqNqFjL+4IGLBgCJC3ZOShY" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.2.0/js/tether.min.js" integrity="sha384-Plbmg8JY28KFelvJVai01l8WyZzrYWG825m+cZ0eDDS1f7d/js6ikvy1+X+guPIB" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/js/bootstrap.min.js" integrity="VjEeINv9OSwtWFLAtmc4JCtEJXXBub00gtSnszmspDLCtC0I4z4nqz7rEFbIZLLU" crossorigin="anonymous"></script>

</body>
</html>
