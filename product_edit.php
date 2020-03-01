<?php
    require_once($_SERVER["DOCUMENT_ROOT"].'/lib/functions.php');

    if( isset( $_GET["edit"] ) && is_int( $_GET["edit"] = (int)$_GET["edit"] ) ) {
        $product = get_product_db($pdo, $_GET["edit"]);
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Редактирование товара</title>

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
                        <div class="card-header">

                            <?php if( isset( $_GET["edit"] ) ){ ?>

                                <h3>Редактирование товара</h3>

                            <?php } else { ?>

                                <h3>Добавить товар</h3>

                            <?php } ?>

                        </div>

                        <div class="card-body">

                            <form action="lib/product_edit.php" method="post" enctype="multipart/form-data">

                                <?php if( isset( $_GET["edit"] ) ){ ?>

                                    <input type="hidden" name="edit" value="<?php echo $_GET["edit"]?>">

                                <?php } ?>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="name">Название</label>
                                            <input type="text" class="form-control" name="name" id="name"
                                                value="<?php if( isset($product["name"]) ) echo $product["name"]?>">
                                           
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Описание</label>
                                            <input type="text" class="form-control" name="description" id="description"
                                                value="<?php if( isset($product["description"]) ) echo $product["description"]?>">

                                            <!-- class="is-invalid"
                                            <span class="text text-danger">
                                                Ошибка валидации
                                            </span>
                                            -->
                                           
                                        </div>

                                        <div class="form-group">
                                            <label for="text">Полное описание</label>
                                            <textarea id="text" name="text" class="form-control" rows="3"><?php if( isset($product["text"]) ) echo $product["text"]?></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="categories_id">Категрия</label>
                                            <select id="categories_id" name="categories_id" class="form-control">

                                                <?php if( is_array ( $categories = get_categories_db($pdo) ) ) {?>

                                                    <?php foreach($categories as $category){?>

                                                        <?php if( isset($product["categories_id"]) && $product["categories_id"] == $category["id"] ) {?>

                                                            <option value="<?php echo $category["id"]?>" selected="selected"><?php echo $category["name"]?></option>

                                                        <?php } else {?>

                                                            <option value="<?php echo $category["id"]?>"><?php echo $category["name"]?></option>
                                                        
                                                        <?php } ?>

                                                    <?php } ?>

                                                <?php } ?>

                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="status">Показать</label>

                                            <?php if( isset($product["status"]) ) {?>

                                                <?php if( $product["status"] == 0 ) {?>
                                                    
                                                    <input type="checkbox" class="form-control" name="status" id="status" checked="checked">

                                                <?php } else {?>

                                                    <input type="checkbox" class="form-control" name="status" id="status">


                                                <?php } ?>

                                            <?php } else {?>

                                                <input type="checkbox" class="form-control" name="status" id="status" checked="checked">

                                            <?php } ?>

                                        </div>

                                        <div class="form-group">
                                            <label for="image">Фото</label>
                                            <input type="file" class="form-control" name="image" id="image">
                                        </div>
                                    </div>
                                    <div class="col-md-4">

                                        <?php if( isset($product["image"]) ) {?>

                                            <img src="<?php echo DIR_IMG.'/'.$product["image"]?>" alt="<?php echo $product["name"]?>" class="img-fluid">

                                        <?php } else {?>

                                            <img src="<?php echo DIR_IMG.'/'.DEFAULT_IMG?>" alt="Нет фото" class="img-fluid">

                                        <?php } ?>
                                        
                                    </div>

                                    <div class="col-md-12">

                                        <?php if( isset( $_GET["edit"] ) ){ ?>

                                            <button class="btn btn-warning">Редактировать товар</button>

                                        <?php } else { ?>

                                            <button class="btn btn-warning">Добавить товар</button>

                                        <?php } ?>
                                        
                                    </div>
                                </div>
                            </form>
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
