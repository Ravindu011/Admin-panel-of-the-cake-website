<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        
        <!--DB actions -->
        <?php

        @include 'config.php';

        if(isset($_POST['add_product'])){
        $p_name = $_POST['p_name'];
        $p_price = $_POST['p_price'];
        $p_image = $_FILES['p_image']['name'];
        $p_image_tmp_name = $_FILES['p_image']['tmp_name'];
        $p_image_folder = 'uploaded_img/'.$p_image;

        $insert_query = mysqli_query($conn, "INSERT INTO festival(name,price,image)VALUES('$p_name','$p_price','$p_image')") ;

        if($insert_query){
            move_uploaded_file($p_image_tmp_name, $p_image_folder);
            $message[] = 'product add succesfully';
        }else{
            $message[] = 'could not add the product';
        }
        };

        if(isset($_GET['delete'])){
        $delete_id = $_GET['delete'];
        $delete_query = mysqli_query($conn, "DELETE FROM festival WHERE id = $delete_id ");
        if($delete_query){
            header('location:addfestival.php');
            $message[] = 'product has been deleted';
        }else{
            header('location:addfestival.php');
            $message[] = 'product could not be deleted';
        };
        };

        if(isset($_POST['update_product'])){
        $update_p_id = $_POST['update_p_id'];
        $update_p_name = $_POST['update_p_name'];
        $update_p_price = $_POST['update_p_price'];
        $update_p_image = $_FILES['update_p_image']['name'];
        $update_p_image_tmp_name = $_FILES['update_p_image']['tmp_name'];
        $update_p_image_folder = 'uploaded_img/'.$update_p_image;

        $update_query = mysqli_query($conn, "UPDATE festival SET name = '$update_p_name', price = '$update_p_price', image = '$update_p_image' WHERE id = '$update_p_id'");

        if($update_query){
            move_uploaded_file($update_p_image_tmp_name, $update_p_image_folder);
            $message[] = 'product updated succesfully';
            header('location:addfestival.php');
        }else{
            $message[] = 'product could not be updated';
            header('location:addfestival.php');
        }

        }

        ?>
        <!--DB actions over-->


        <?php

        if(isset($message)){
        foreach($message as $message){
            echo '<div class="message"><span>'.$message.'</span> <i class="fas fa-times" onclick="this.parentElement.style.display = `none`;"></i> </div>';
        };
        };

        ?>

        <?php include 'header.php'; ?>
        <br><br>

        <div class="container">

        <section>

        <form action="" method="post" class="add-product-form md-5" enctype="multipart/form-data">
        <h3>add a new product</h3>
        <input type="text" name="p_name" placeholder="enter the product name" class="box " required>
        <input type="number" name="p_price" min="0" placeholder="enter the product price" class="box" required>
        <input type="file" name="p_image" accept="image/png, image/jpg, image/jpeg" class="box " required>
        <input type="submit" value="add the product" name="add_product" class="btn btn-primary">
        </form>

        </section>

        <section class="display-product-table">

        <table class="table">

            <thead>
                <th>product image</th>
                <th>product name</th>
                <th>product price</th>
                <th>action</th>
            </thead>

            <tbody>
                <?php
                
                    $select_products = mysqli_query($conn, "SELECT * FROM festival");
                    if(mysqli_num_rows($select_products) > 0){
                    while($row = mysqli_fetch_assoc($select_products)){
                ?>

                <tr>
                    <td><img src="uploaded_img/<?php echo $row['image']; ?>" height="100" alt=""></td>
                    <td><?php echo $row['name']; ?></td>
                    <td>$<?php echo $row['price']; ?>/-</td>
                    <td>
                    <a href="addfestival.php?delete=<?php echo $row['id']; ?>" class="delete-btn btn btn-danger" onclick="return confirm('are your sure you want to delete this?');"> <i class="fas fa-trash"></i> delete </a>
                    <a href="addfestival.php?edit=<?php echo $row['id']; ?>" class="option-btn btn btn-primary"> <i class="fas fa-edit"></i> update </a>
                    </td>
                </tr>

                <?php
                    };    
                    }else{
                    echo "<div class='empty'>no product added</div>";
                    };
                ?>
            </tbody>
        </table>

        </section>

        <section class="edit-form-container">

        <?php
        
        if(isset($_GET['edit'])){
            $edit_id = $_GET['edit'];
            $edit_query = mysqli_query($conn, "SELECT * FROM festival WHERE id = $edit_id");
            if(mysqli_num_rows($edit_query) > 0){
                while($fetch_edit = mysqli_fetch_assoc($edit_query)){
        ?>
       
        <form action="" method="post" enctype="multipart/form-data">
            <img src="uploaded_img/<?php echo $fetch_edit['image']; ?>" height="200" alt="">
            <input type="hidden" name="update_p_id" value="<?php echo $fetch_edit['id']; ?>">
            <input type="text" class="box" required name="update_p_name" value="<?php echo $fetch_edit['name']; ?>">
            <input type="number" min="0" class="box" required name="update_p_price" value="<?php echo $fetch_edit['price']; ?>">
            <input type="file" class="box" required name="update_p_image" accept="image/png, image/jpg, image/jpeg">
            <input type="submit" value="update the prodcut" name="update_product" class="btn">
            <input type="reset" value="cancel" id="close-edit" class="option-btn">
        </form>
        
        

        <?php
                    };
                };
                echo "<script>document.querySelector('.edit-form-container').style.display = 'flex';</script>";
            };
        ?>

        </section>

        </div>


                
                <!-- JavaScript Libraries -->
                <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
                <script src="lib/easing/easing.min.js"></script>
                <script src="lib/waypoints/waypoints.min.js"></script>
                <script src="lib/counterup/counterup.min.js"></script>
                <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    </body>
</html>