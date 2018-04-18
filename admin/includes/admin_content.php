<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Admin
                <small>Subheading</small>
            </h1>

            <?php

            $user = Photo::find_by_id(4);
            echo $user->title;
            echo "<br>";
//            $result_set = User::find_all_users();
//            while($row = mysqli_fetch_array($result_set)) {
//                echo $row['username'] . "<br>";               ///stare
//            }



//            $id = $session->getUserId();
//            echo $id;


//              ***create test***
//            $user = new User();                        //instance of User class
//            $user->username = "adadasdasd";               //setting values
//            $user->password = "5454535";
//            $user->first_name = "werwerewrwer";
//            $user->last_name = "rrrrrrrrrrrrrrrrr";
//            $user->create();                           //call create method (db INSERT query)
            //
            ///
            ///  //***update test***//
//            $user = User::find_by_id(15);        //find user is static- no instance needed
//            $user->username = "update";
//            $user->password = "test";
//            $user->first_name = "update";
//            $user->last_name = "test";                  //set value
//            $user->update();                                //call method (db UPDATE query)

//           $user = new User();
//           $user->getProperties();
//           $user->username = "slav";
//            $user->save();

            //  ***delete test***
            //if($user) { $user->delete(); };

//            $user = new User();
//            $user->username = "NEW USER";
//            $user->save();
            //$user->password = "123456";             //nadanie tylko w instancji
            //$user->save();                          //wrzucenie do bazy



//            $found_user = User::find_by_id(2);       //nowe OK
//            echo $found_user->username;


//            $user = User::instantiation($found_user);
//            echo $user->username;


//            $users = User::find_all();                //nowe OK
//            foreach($users as $user) {
//                echo $user->username . "<br>";
//            }
//            $photo = new Photo();                        //instance of class
//            $photo->title = "eee";               //setting values
//            $photo->description = "aaa";
//            $photo->size = 20;
//            $photo->create();
//
            //$photo = Photo::find_by_id(1);
            //$photo->delete();
            echo INCLUDES_PATH;
            echo '<br>';
            $photos = Photo::find_all();                //nowe OK
            foreach($photos as $photo) {
                echo $photo->title . "<br>";
            }

            ?>

            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  <a href="index.html">Dashboard</a>
                </li>
                <li class="active">
                    <i class="fa fa-file"></i> Blank Page
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->