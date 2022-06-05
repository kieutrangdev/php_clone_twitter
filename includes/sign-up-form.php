<?php



    if(isset($_POST['signup']))
    {
        $screenName = $_POST['screenName'];
        $password = $_POST['password'];
        $email = $_POST['email'];

        $error = '';

        if(empty($screenName) or empty($password) or empty($email))
        {
            $error = "All fields are required";
        }
        else {
            $email = $getFromU->checkInput($email);
            $password = $getFromU->checkInput($password);
            $screenName = $getFromU->checkInput($screenName);
            if(!filter_var($email))
            {
                $error = "Invalid email format";

            }
            else if(strlen($screenName) > 20)
            {
                $error = "Name must be betweet in 6-20 characters";

            }
            else if(strlen($password) < 5)
            {
                $error = "Password is too short";

            }
            else {
                if($getFromU->checkEmail($email) === true)
                {
                    $error = "Email is already in use";
                }
                else {
                    $id = $getFromU->create('users',array('email'=>$email,
                        'password'=>md5($password),
                        'screenName'=>$screenName,
                        'profileImage' =>'assets/images/defaultProfileImage.png',
                        'profileCover' =>'assets/images/defaultConverImage.png',

                        ));
                    $_SESSION['id'] = $id;
                    header("Location: includes/sign-up.php?step=1");
                }
            }
        }
    }

    else {
        $error = null;
    }

?>

<form method="post">
    <div class="signup-div">
        <h3>Sign up </h3>
        <ul>
            <li>
                <input type="text" name="screenName" placeholder="Full Name" <?php if (isset($_POST['screenName'])) { ?> value="<?php echo $screenName ?> " <?php } ?> >

            </li>
            <li>
                <input type="text" name="email" placeholder="Email" <?php if (isset($_POST['email'])) { ?> value="<?php echo $email ?> " <?php } ?> />
            </li>
            <li>
                <input type="password" name="password" placeholder="Password"/>
            </li>
            <li>
                <input type="submit" name="signup" Value="Signup for Twitter">
            </li>
        </ul>
       <?php if (isset($error)) { ?>
         <li class="error-li">
          <div class="span-fp-error"><?php echo $error?></div>
         </li>
        <?php } ?>
    </div>
</form>
