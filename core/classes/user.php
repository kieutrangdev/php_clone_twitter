<?php
    class User {
        protected $pdo;

        public function __construct($pdo)
        {
            $this->pdo = $pdo;
        }

        public function checkInput($var)
        {
            $var = htmlspecialchars($var);
            $var = trim($var);
            $var = stripcslashes($var);
            return $var;
        }

        public function checkEmail($email)
        {
            $stmt = $this->pdo->prepare("SELECT `email` FROM `users` WHERE `email` = :email ");
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();

            $count = $stmt->rowCount();
            if($count >0) {
                return true;
            }
            else return false;
        }

        public function login($email, $password)
        {
            $stmt = $this->pdo->prepare("
                SELECT `id` FROM `users` 
                WHERE `email` = :email and `password` = :password
            ");

            $stmt->bindParam(":email", $email,PDO::PARAM_STR);
            $stmt->bindParam(":password", $password,PDO::PARAM_STR);


            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_OBJ);
            $count = $stmt->rowCount();

            if($count > 0)
            {
                $_SESSION['id'] = $user->id;

                header("location: home.php");
            }
            else {
                return false;
            }
        }


        public  function logout()
        {
            $_SESSION = array();
            session_destroy();
            header("location: ../index.php");
        }


        public function userData($id)
        {
            $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE `id` = :id ");
            $stmt->bindParam(":id",$id,PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ);

        }

       public function register($email, $screenName, $password)
       {
           $stmt = $this -> pdo-> prepare("INSERT INTO `users` (`email`,`password`,`screenName`,`profileImage`,`profileCover`)
            VALUES (:email,:password, :screenName, 'assets/images/defaultprofileimage.png','assets/images/defaultCoverImage.png')
            ");


           $stmt->bindParam(":email", $email ,PDO::PARAM_STR);
           $stmt->bindParam(":password", md5($password) ,PDO::PARAM_STR);
           $stmt->bindParam(":screenName", $screenName ,PDO::PARAM_STR);

            $stmt->execute();
           $id = $this->pdo->lastInsertId();
           $_SESSION['id'] = $id;

       }





    }
?>
