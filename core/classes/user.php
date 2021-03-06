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

        public function checkUserName($username)
        {
            $stmt = $this->pdo->prepare("SELECT `name` FROM `users` WHERE `email` = :email ");
            $stmt->bindParam(":name", $username, PDO::PARAM_STR);
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
            $stmt->bindParam(":password", md5($password),PDO::PARAM_STR);



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
            header('location: '.BASE_URL.'/index.php');
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

       public function create($table, $fields = array())
       {
            $columns  = implode(',', array_keys($fields));
            $values = ":".implode(', :',array_keys($fields));
            $sql = "INSERT INTO {$table} ({$columns}) values ({$values})";
//            var_dump($sql);
           if($stmt = $this->pdo->prepare($sql))
           {
               foreach ($fields as $key => $data)
               {
                   $stmt->bindValue(':'.$key, $data);
               }
               $stmt->execute();
               return $this->pdo->lastInsertId();
           }
       }

       public function update($table, $id, $fields = array())
       {
           $columns = '';
            $i =1;

           foreach ($fields as $name => $value)
           {
               $columns .= "`{$name}` = :{$name}";
               if($i < count($fields))
               {
                   $columns .= ', ';

               }
               $i++;
           }
           $sql = "UPDATE {$table} SET {$columns} Where `id`= {$id}";
           if($stmt = $this->pdo->prepare($sql))
           {
               foreach ($fields as $key => $value)
               {
                   $stmt->bindValue(':'.$key, $value);
               }

               $stmt->execute();
           }
       }

       public function userIdByUserName($name)
       {
           $stmt = $this->pdo->prepare("SELECT `id` FROM `users`
                WHERE `name` =:name
            ");

           $stmt->bindParam(":name",$name,PDO::PARAM_STR);
           $stmt->execute();
           $user = $stmt->fetch(PDO::FETCH_OBJ);
           return $user->id;
       }

       public function loggedIn()
       {
           return (isset($_SESSION['id'])) ? true : false;
       }




    }
?>
