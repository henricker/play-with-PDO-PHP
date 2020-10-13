<?php

    class Person{

        private static $pdo;

        #Conection with databse
        public function __construct($host, $dbname, $user, $pass){
            try{
                self::$pdo = new PDO('mysql:host='.$host.';dbname='.$dbname, $user, $pass);
            }
            catch(PDOException $e){
                echo 'Database error: '.$e->getMessage();
                exit();
            }
            catch(Exception $e){
                echo 'Normal error: '.$e->getMessage();
                exit();
            }
        }

        #find all database
        public function findData(){

            $allRegisters = [];
            $sql = self::$pdo->query(
                "SELECT * FROM `Person` ORDER BY name"
            );

            #I use this Parameter POO::FETCH_ASSOC to save memory
            $allRegisters = $sql->fetchAll(PDO::FETCH_ASSOC);

            return $allRegisters;
        }

        #Append person information in database
        public function InsertPerson($name, $phone, $email){

            #First, check is the email is alredy register
            $sql = self::$pdo->prepare(
                "SELECT id FROM `Person` WHERE email=:e"
            );
            $sql->bindValue(':e', $email);
            $sql->execute();

            if($sql->rowCount() > 0){
                return false;
            }else{
                $sql = self::$pdo->prepare(
                    "INSERT INTO `Person` VALUES(NULL, :n, :p, :e)"
                );
                $sql->bindValue(':n', $name);
                $sql->bindValue(':p', $phone);
                $sql->bindValue(':e', $email);

                $sql->execute();

                return true;

                  /*
                    You could also add the values
                    by passing an array with the data

                    $sql = self::$pdo->prepare(
                        "INSERT INTO `Person` VALUES(null, :n, :p, :e)"
                    );

                    $sql->execute(array($name, $phone, $email));
                */
            }
        }

        #Remove person from databse
        public function DeletePerson($id){
            $sql = self::$pdo->prepare(
                "DELETE FROM `Person` WHERE id=:id"
            );
            $sql->bindValue(':id', $id);
            $sql->execute();
        }

        #Find for a specific person to edit
        public function FindPerson($id){
            $personData = [];
            $sql = self::$pdo->prepare(
                "SELECT * FROM `Person` WHERE id = :id"
            );
            $sql->bindValue('id', $id);
            $sql->execute();

            $personData = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $personData;
        }

        public function UpdatePerson($id, $name, $phone, $email){
           #First, search any id where email ==== $email and selected id, if id == $id or id == null, update!
           error_reporting(0);
           $findId = [];
           $sql = self::$pdo->prepare(
                "SELECT id FROM `Person` WHERE email=:e"
           );
           $sql->bindValue(':e', $email);
           $sql->execute();
           $findId = $sql->fetchAll();

           if($findId[0]['id'] == $id || $findID[0]['id'] == null){
               $sql = self::$pdo->prepare(
                    "UPDATE `Person` SET name = :n, phone = :p, email = :e"
               );
               $sql->bindValue(':n', $name);
               $sql->bindValue(':p', $phone);
               $sql->bindValue(':e', $email);
               $sql->execute();

               return true;
           }
           else{
               # $findId != null && findId != id
               return false;
           }
        }
    }

?>