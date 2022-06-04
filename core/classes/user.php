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

        public function userData($id)
        {
            $stmt = $this->pdo->

        }
    }
?>
