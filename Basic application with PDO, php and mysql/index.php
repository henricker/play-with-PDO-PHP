<?php
    require_once('models/person.class.php');

    $person = new Person('localhost', 'PlayPDO', 'root', '');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/style.css">
        <title>Play with php and mysql</title>
    </head>
    <body>
        <?php
            if(isset($_POST['submit'])){

                #-----------Update----------------
                if(isset($_GET['id_update']) && !empty($_GET['id_update'])){
                    $id_update = addslashes($_GET['id_update']);
                    $name = addslashes($_POST['name']);
                    $phone = addslashes($_POST['phone']);
                    $email = addslashes($_POST['email']);

                    if(!empty($name) && !empty($phone) && !empty($email)){
                        if(!$person->UpdatePerson($id_update, $name, $phone,$email)){
                            echo 'Email alredy registered!';
                        }
                        else{
                            header('location: index.php');
                        }
                    }
                }
                #-----------Register--------------
                else{
                    $name = addslashes($_POST['name']);
                    $phone = addslashes($_POST['phone']);
                    $email = addslashes($_POST['email']);

                    if(!empty($name) && !empty($phone) && !empty($email)){
                        if(!$person->InsertPerson($name, $phone, $email)){
                            echo 'Email alredy registered!';
                        }
                    }
                }
            }
        ?>

        <?php
            if(isset($_GET['id_update'])){
                $id = addslashes($_GET['id_update']);
                $personData = $person->FindPerson($id);
            }
        ?>
        <section id="section-left">
            <form method="POST">
                <h2>Register person</h2>
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Write your name" autocomplete="off"
                value="<?php if(isset($personData)){ echo $personData[0]['name'];}?>" required>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Write your email" autocomplete="off" 
                value="<?php if(isset($personData)){ echo $personData[0]['email'];}?>" required>
                <label for="phone">Fone</label>
                <input type="text" id="phone" name="phone" placeholder="(99) 99999-9999" autocomplete="off"
                value="<?php if(isset($personData)){ echo $personData[0]['phone'];}?>" required>
                <input type="submit" name="submit"
                value="<?php if(isset($personData)){echo 'Update';}else{ echo 'Submit';}?>" required>
            </form>
        </section><!--Section register or update-->
        <section id="section-right">

            <table>
                <tr id="data-title">
                    <td>NAME</td>
                    <td>PHONE</td>
                    <td colspan="2">EMAIL</td>
                </tr>
                
                <?php
                    $data = $person->findData();
    
                    if(count($data) > 0){
                        for($i = 0; $i < count($data); $i++){
                            echo '<tr class="data-row">';
                            foreach($data[$i] as $key => $value){
                                if($key != 'id'){
                                    echo '<td>'.$value.'</td>';
                                }
                            }
                ?> 
                        <td>
                        <a href="index.php?id_update=<?php echo $data[$i]['id'];?>">Update</a>
                        <a href="index.php?id=<?php echo $data[$i]['id'];?>" name="delete">Delete</a>
                        <!--
                            Above, when i make href="index.php?id< ? php $data[$i]['id']?>
                            I create a $_GET['id] method automaticaly
                        -->
                        </td>

                <?php
                            echo '</tr>';
                        }
                    }
                    else{
                       echo 'Empty database, waiting for data!';
                    }
                ?>
            </table>
        </section><!--Section view all registers, update or delete-->
    </body>
</html>

<?php

    #Delete data using method $_GET['str'];

    if(isset($_GET['id'])){
        $id = addslashes($_GET['id']);
        $person->DeletePerson($id);
        header('location: index.php');

        #Above, the function header redirects the page for index.php
    }

?>