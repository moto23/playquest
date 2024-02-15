<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "playquest";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture the mode name and other from the HTML form
    $mode_name=  $_POST['mode_name'];
    $champ_name= $_POST['champ_name'];
    $description = $_POST['description'];
    $qualification =   $_POST['qualification'];
    // $total_coins = $_POST['total_coins'];
    $gift = $_POST['gift'];
    $no_of_questions = $_POST['no_of_questions'];
    $time_required = $_POST['time_required'];
    $label = $_POST['label'];

    // First, query the category table to get the category_id based on champ_name
    $championship_query = "SELECT champ_id FROM championship WHERE champ_name = ?";
    $category_stmt1 = $conn->prepare($championship_query);

    if ($category_stmt1) {
        $category_stmt1->bind_param("s", $champ_name);
        $category_stmt1->execute();
        $category_stmt1->bind_result($champ_id);
        $category_stmt1->fetch();
        $category_stmt1->close();
    } else {
        die("Category query preparation failed: " . $conn->error);
    }

    $label_query = "SELECT label_id FROM label WHERE label_name = ?";
    $category_stmt2 = $conn->prepare($label_query);

    if ($category_stmt2) {
        $category_stmt2->bind_param("s", $label);
        $category_stmt2->execute();
        $category_stmt2->bind_result($label_id);
        $category_stmt2->fetch();
        $category_stmt2->close();
    } else {
        die("Category query preparation failed: " . $conn->error);
    }

    // Now that you have the category_id, you can insert the data into the championship table
    $insert_query = "INSERT INTO game_mode (mode_name,tot_coins,description,no_of_question,time_minutes,champ_id,user_qualification) VALUES (?, ?, ?, ?, ?, ?,?)";
    $insert_stmt = $conn->prepare($insert_query);

    if ($insert_stmt) {
        $insert_stmt->bind_param("sssssss",$mode_name,$total_coins,$description,$no_of_questions,$time_required,$champ_id,$qualification );
        $insert_stmt->execute();
        $insert_stmt->close();
        
        $get_modeid_query = "SELECT mode_id from game_mode where mode_name = ? and champ_id = ? limit 1";
        $get_modeid_stmt = $conn->prepare($get_modeid_query);

        if($get_modeid_stmt){
          $get_modeid_stmt->bind_param("ss", $mode_name, $champ_id);
          $get_modeid_stmt->execute();
          $get_modeid_stmt->bind_result($mode_id);
          $get_modeid_stmt->fetch();
          $get_modeid_stmt->close();

          $set_questions_query = "INSERT INTO chosen_questions(label_id, mode_id) values(?, ?)";
          $set_questions_stmt = $conn->prepare($set_questions_query);

          if($set_questions_stmt){
            $set_questions_stmt->bind_param("ss", $label_id, $mode_id);
            $set_questions_stmt->execute();
            $set_questions_stmt->close();
          } else {
            die("Insert query preparation failed: " . $conn->error);
        }

        }else {
          die("Insert query preparation failed: " . $conn->error);
      }

    } else {
        die("Insert query preparation failed: " . $conn->error);
    }
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
  <head>

    <link rel="icon" href="../assets/img/title.png" type="image/icon type" />

    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap"
      rel="stylesheet"
    />
    <!-- fonts -->


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css" />

    <script data-require="jquery" data-semver="2.1.3" src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script data-require="bootstrap" data-semver="3.3.2" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script data-require="angular.js@1.3.x" src="https://code.angularjs.org/1.3.14/angular.js" data-semver="1.3.14"></script>
    <script data-require="ui-bootstrap" src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.13.0/ui-bootstrap-tpls.min.js" data-semver="0.13.0"></script>

    <style>
            body {
              /* font-family: "Roboto", Helvetica, Arial, sans-serif; */
              font-weight: 100;
              font-size: 12px;
              line-height: 30px;
              color: #777;
              background: #4caf50;
            }

            .container {
              max-width: 400px;
              width: 100%;
              margin: 0 auto;
              position: relative;
            }


            #contact {
              background: #f9f9f9;
              padding: 25px;
              margin: 35px 0;
              box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2),
                0 5px 5px 0 rgba(0, 0, 0, 0.24);


      }
            

            #contact h3 {
              display: block;
              font-size: 30px;
              font-weight: 300;
              margin-bottom: 10px;
            }

            #contact h4 {
              margin: 5px 0 15px;
              display: block;
              font-size: 13px;
              font-weight: 400;
            }

            fieldset {
              border: medium none !important;
              margin: 0 0 10px;
              min-width: 100%;
              padding: 0;
              width: 100%;
            }

            #contact input[type="text"]{
              width: 100%;
              border: 1px solid #ff9d5c;
              background: #fff;
              margin: 0 0 5px;
              padding: 10px;
            }

            #contact input[type="text"]:hover{
              border: 1px solid #ff9d5c;
            }

            #contact textarea {
              height: 100px;
              max-width: 100%;
              resize: none;
            }

            #contact button[type="submit"] {
              cursor: pointer;
          width: 50;
          border: none;
          background: #ff9d5c;
          color: #f8f9fa;
          margin: 22px 209px 46px;
          padding: 3px 30px;
          font-size: 17px;
          border-radius: 8px;
            }

            #contact button[type="submit"]:hover {
              background: #43a047;
              -webkit-transition: background 0.3s ease-in-out;
              -moz-transition: background 0.3s ease-in-out;
              transition: background-color 0.3s ease-in-out;
            }

            #contact button[type="submit"]:active {
              box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.5);
            }

            .copyright {
              text-align: center;
            }

            #contact input:focus,
            #contact textarea:focus {
              outline: 0;
              border: 1px solid #aaa;
            }

            ::-webkit-input-placeholder {
              color: #888;
            }

            :-moz-placeholder {
              color: #888;
            }

            ::-moz-placeholder {
              color: #888;
            }

            :-ms-input-placeholder {
              color: #888;
            }
    </style>

    <!-- CSS FontAwesome -->
    <script
      src="https://kit.fontawesome.com/52f3e32cf9.js"
      crossorigin="anonymous"
    ></script>

    <meta charset="utf-8" />
    <link
      rel="apple-touch-icon"
      sizes="76x76"
      href="../assets/img/title.png"
    />
    <link rel="icon" type="image/png" href="../assets/img/title.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>CREATE NEW Game Mode | PlayQuest</title>

    <!-- tiny mce -->
    <!-- <script
      src="https://cdn.tiny.cloud/1/6x2lct5ci3repi2zerjxkto4t9ju86wfyyzn6x448cijg7g8/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    ></script> -->

    <script>
      tinymce.init({
        selector: "#mytextarea",
      });
    </script>

    <!-- tiny mce -->

    <meta
      content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no"
      name="viewport"
    />
    <!--     Fonts and icons     -->
    <link
      href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200"
      rel="stylesheet"
    />
    <link
      href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css"
      rel="stylesheet"
    />
    <!-- CSS Files -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="../assets/demo/demo.css" rel="stylesheet" />
  </head>

  <body>
    <div class="wrapper">
      <div class="sidebar" data-color="white" data-active-color="danger">
        <div
          style="display: flex; flex-direction: column; align-items: center"
          class="logo"
        >
          <!-- logo -->
          <a href="#" class="">
            <div style="width: 234px; height: 73px" class="logo-image-small">
              <img src="../assets/img/logo-small1.png" />
            </div>
          </a>
          <!-- logo -->
        </div>

        <div class="sidebar-wrapper">
          <ul class="nav">
            <!-- First Section   -->

            <!-- Main Menu -->
            <li >
              <a href="#">
                <p style="color: rgb(255, 81, 0); font-size: 15px">Main Menu</p>
              </a>
            </li>

            <!-- Dashboard -->
            <li >
              <a href="./dashboard.php">
                <i class="fas fa-th-large"></i>
                <p style="font-size: 15px; font-weight: bold">Dashboard</p>
              </a>
            </li>

            <!-- User Insights -->
            <li>
              <a href="./insights.php">
                <i class="fas fa-desktop"></i>
                <p style="font-size: 15px; font-weight: bold">User Insights</p>
              </a>
            </li>

            <!-- Manage Logins -->
 

            <!-- End First Section   -->

            <!-- Start Second Section -->

            <!--Add Information-->
            <li class="active">
              <a href="#">
                <p style="color: rgb(255, 81, 0); font-size: 15px">
                  Add Information
                </p>
              </a>
            </li>

            <!-- Create New Championship -->
            <li>
              <a href="./create_new_championship.php">
                <i class="far fa-plus-square"></i>
                <p style="font-size: 15px; font-weight: bold">
                  New Championship
                </p>
              </a>
            </li>

            <!-- New Category   -->
            <li>
              <a href="../example/new_category.php">
                <i class="fas fa-trophy"></i>
                <p style="font-size: 15px; font-weight: bold">New Category</p>
              </a>
            </li>

            <!-- New Game Mode   -->
            <li class="active">
              <a href="../example/add_new_mode.php">
                <i class="fas fa-crosshairs"></i>
                <p style="font-size: 15px; font-weight: bold">New Game Mode</p>
              </a>
            </li>
 
            <li>
              <a href="../example/add_new_faculty.php">
                <i class="fas fa-solid fa-user-plus"></i>
                <p style="font-size: 15px; font-weight: bold">New Faculty</p>
              </a>
            </li>

            <!-- New Question -->
            <li>
              <a href="../example/add_question.php">
                <i class="fas fa-align-left"></i>
                <p style="font-size: 15px; font-weight: bold">New Question</p>
              </a>
            </li>

            <!-- New Label -->
            <li>
              <a href="../example/new_label.php">
                <i class="fas fa-times-circle"></i>
                <p style="font-size: 15px; font-weight: bold">New Label</p>
              </a>
            </li>

            <!-- End Second Section   -->

            <!-- Start Third Section -->

            <!--All Information-->
            <li>
              <a href="#">
                <p style="color: rgb(255, 81, 0); font-size: 15px">
                  All Information
                </p>
              </a>
            </li>

            <!-- All Championships -->
            <li>
              <a href="../example/all_championships.php">
                <i class="fas fa-info-circle"></i>
                <p style="font-size: 15px; font-weight: bold">
                  All Championships
                </p>
              </a>
            </li>

            <!-- All Categories -->

            <li>
              <a href="../example/all_category.php">
                <i class="fas fa-th"></i>
                <p style="font-size: 15px; font-weight: bold">All Categories</p>
              </a>
            </li>



            <!-- All Labels -->
            <li>
              <a href="../example/all_labels.php">
                <i class="fas fa-align-justify"></i>
                <p style="font-size: 15px; font-weight: bold">All Labels</p>
              </a>
            </li>

             <!-- Wrong Questions -->
            <li>
              <a href="../example/wrong_questions.php">
                <i class="fas fa-align-justify"></i>
                <p style="font-size: 15px; font-weight: bold">Wrong Questions</p>
              </a>
            </li>
            <!-- End Fourth Section   -->
          </ul>
        </div>
      </div>
     
      
      <div class="main-panel" style="height: 70px;" >
        <!-- Navbar -->
        <nav
          class="
            navbar navbar-expand-lg navbar-absolute
            fixed-top
            navbar-transparent
          "
          style="height: 70px;"
        >
          <div class="container-fluid" style="margin-bottom: 15px">
            <div class="navbar-wrapper">
              <div class="navbar-toggle">
                <button type="button" class="navbar-toggler">
                  <span class="navbar-toggler-bar bar1"></span>
                  <span class="navbar-toggler-bar bar2"></span>
                  <span class="navbar-toggler-bar bar3"></span>
                </button>
              </div>
             
            </div>
            <button
              class="navbar-toggler"
              type="button"
              data-toggle="collapse"
              data-target="#navigation"
              aria-controls="navigation-index"
              aria-expanded="false"
              aria-label="Toggle navigation"
            >
              <span class="navbar-toggler-bar navbar-kebab"></span>
              <span class="navbar-toggler-bar navbar-kebab"></span>
              <span class="navbar-toggler-bar navbar-kebab"></span>
            </button>

            <div
              class="collapse navbar-collapse justify-content-end"
              id="navigation"
            >
              <ul class="navbar-nav">
                <li class="nav-item btn-rotate dropdown">
                  <a
                    class="nav-link dropdown-toggle"
                    href="http://example.com"
                    id="navbarDropdownMenuLink"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                  >
                    <i class="fas fa-user-circle"></i>
                  </a>
                  <div
                    class="dropdown-menu dropdown-menu-right"
                    aria-labelledby="navbarDropdownMenuLink"
                  >
                    <a
                      class="dropdown-item"
                      style="text-align: center"
                      href="logout.php"
                    >
                      <i class="fas fa-sign-out-alt"></i> Logout</a
                    >
                  </div>
                </li>
              </ul>
            </div>
           
          </div>
        </nav>
        <!-- End Navbar -->

        <!-- form code starts         -->

        <div class="content">
          <div class="row">
            <div class="col-md-12">
              <div class="card demo-icons">

                <!-- form start  -->

                <!-- form start  -->
                <form method="post" action="add_new_mode.php">
                    

                <div class="card-header">
                  <div class="container">
                    <!-- 1st one -->
                    <div id="contact">
                      <fieldset>
                        <p style="text-align: center;color: #ff9d5c; font-size: 30px;font-weight: bold; ">Create New Game Mode</p>
                        
                        <hr style="height:1px; border:none;  background-color:#ff9d5c;">
                        <br>
                        <br>
                      </fieldset>

                      <!-- <fieldset>
                        <p style="color: #ff9d5c; font-size: 22px;font-weight: bold; ">Championship Name</p>
                        <input
                          placeholder="Enter Championship Name"
                          id="championship_name"
                          type="text"
                          tabindex="1"
                          required
                          autofocus
                        />
                      </fieldset> -->

                      <p style="color: #ff9d5c;font-size: 22px;font-weight: bold; ">Game Mode name</p>
                      <div 
                        style=" display: grid; min-width: 89ch; font-size: 16px"
                        class="select"
                      >
                        <!-- <select id="mode_name" name="mode_name" style="border-color: #ff9d5c;" >
                          <option value="Quick_Hit">Quick Hit</option>
                          <option value="Select_Gift_&_Play">Select Gift & Play</Select></option>
                          <option value="Option 3">Option 3</option>
                          <option value="Option 4">Option 4</option>
                          <option value="Option 5">Option 5</option>
                        </select> -->
                      <fieldset >
                        <input type="radio" id="quick_hit" name="mode_name" value="quick_hit">
                        <label style="color: black;font-size: 15px;font-weight: normal; " for="html">Quick Hit</label><br>
                        <input type="radio" id="play_win_gift" name="mode_name" value="play_win_gift">
                        <label style="color: black ;font-size: 15px;font-weight: normal; "for="css">Play & Win Gift</label><br>
                      </fieldset>
                        <span class="focus"></span>

                      </div>

                      <script type="text/javascript"> 
                        document.getElementById("quick_hit").onclick = function() { 
                            document.getElementById("select_gift").style.display = "none"; 
                        } 
                  
                        document.getElementById("play_win_gift").onclick = function() { 
                            document.getElementById("select_gift").style.display = "grid"; 
                        } 
                  
                    </script> 
                      <br />
                      <!-- ended 1st one -->

                      <!-- 2nd one -->
                      <p style="color: #ff9d5c;font-size: 22px;font-weight: bold; ">Championship Name</p>
                      <div 
                        style=" display: grid; min-width: 89ch; font-size: 16px"
                        class="select"
                      >
                       
                            <?php
                                    // PHP code to retrieve category names and populate the dropdown
                                    $conn = new mysqli('localhost', 'root', '', 'playquest');

                                    if ($conn->connect_error) {
                                        die("Connection failed: " . $conn->connect_error);
                                    }

                                    $sql = "SELECT champ_name FROM championship";
                                    $result = $conn->query($sql);
                                ?>
                                 <select id="champ_name" name="champ_name" style="border-color: #ff9d5c;" >
                                    <?php
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $champ_name = $row["champ_name"];
                                                echo "<option value='$champ_name'>$champ_name</option>";
                                            }
                                        }
                                        $result->free(); // Free the result set
                                    ?>
                                </select>

                        <span class="focus"></span>

                      </div>
                      <!-- ended 2nd one -->
                      <br /><br />
                      <!-- 3rd one -->
                      <p style="color: #ff9d5c;font-size: 22px;font-weight: bold; ">Description/Rules</p>
                      <input style="border-color: #ff9d5c; width: 100%;"
                         
                          placeholder=""
                          name="description"
                          id="description"
                          type="text"
                          tabindex="1"
                          autofocus
                        />
                       <!-- <textarea id="description" style="border-color: #ff9d5c; width: 100%;" ></textarea> -->
                      <!-- ended 3rd one -->


                      <br />
                      <br />

                      <fieldset>

                        <p style="color: #ff9d5c; font-size: 22px; width: 100%;font-weight: bold; ">Required Qualification</p>
                        <div 
                        style=" display: grid; min-width: 89ch; font-size: 16px"
                        class="select"
                      >
                        <select id="qualification" name="qualification" style="border-color: #ff9d5c;" >
                          <option value="1_4">1st - 4th</option>
                          <option value="5_7">5th - 7th</option>
                          <option value="8_10">8th - 10th</option>
                          <option value="11_12">11th - 12th</option>
                          <option value="Under_Graduate">Under Graduate</option>
                          <option value="Post_Graduate">Post Graduate</option>
                        </select>

                        <!-- <input
                          placeholder="Enter Qualification."
                          id="qualification"
                          type="text"
                          tabindex="1"
                          required
                          autofocus
                        /> -->

                      </fieldset>
                      <br />


                      <div id="select_gift">
                      <p style="color: #ff9d5c;font-size: 22px;font-weight: bold; ">Select gift</p>
                      <div 
                        style=" display: grid; min-width: 89ch; font-size: 16px"
                        class="select"
                      >
                        <select id="gift" name="gift" style="border-color: #ff9d5c;" >
                          <option value="coupon">Coupon</option>
                          <option value="gift_card">Gift Card</option>
                          <!-- <option value="Option 3">Option 3</option>
                          <option value="Option 4">Option 4</option>
                          <option value="Option 5">Option 5</option> -->
                        </select>
                        <span class="focus"></span>

                      </div>

                      </div>
                      <br>

                      <fieldset>
                        <p style="color: #ff9d5c; font-size: 22px; width: 100%;font-weight: bold; ">Number of Questions</p>
                        <!-- <p style="color: #00c800;font-size: 18px">The number of questions is applicable for 'Select Gift & Play' only</p> -->

                        <input
                          placeholder="Enter Number of Questions"
                          id="n_questions"
                          type="number"
                          name="no_of_questions"
                          tabindex="1"
                          required
                          autofocus
                        />
                      </fieldset>
                      <br />

                      <fieldset>
                        <p style="color: #ff9d5c; font-size: 22px; width: 100%;font-weight: bold; ">Time Required (Enter in Minutes)</p>
                        <!-- <p style="color: #00c800;font-size: 18px">The time required is applicable for 'Select Gift & Play' only</p> -->
                        <p style="display: none;" id="temp"></p>
                        <input
                          placeholder="Enter Time Required (In Minutes)"
                          id="t_required"
                          type="text"
                          tabindex="1"
                          name="time_required"
                          required
                          autofocus
                        />
                      </fieldset>
                      <br />








                      <!-- dsaddsadsasa -->

                      <p style="color: #ff9d5c;font-size: 22px;font-weight: bold; ">Add/Edit Labels</p>
                      <p style="color: #00c800;font-size: 18px">Questions in the Championship will appear from Questions under the label selected from here</p>


                      <div 
                        style=" display: grid; min-width: 89ch; font-size: 16px"
                        class="select"
                      >

                            <?php
                                        // PHP code to retrieve category names and populate the dropdown
                                        $conn = new mysqli('localhost', 'root', '', 'playquest');

                                        if ($conn->connect_error) {
                                            die("Connection failed: " . $conn->connect_error);
                                        }

                                        $sql = "SELECT label_name FROM label";
                                        $result = $conn->query($sql);
                                    ?>
                                    <select style="border-color: #ff9d5c;" id="label" name="label">
                                        <?php
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $label_name = $row["label_name"];
                                                    echo "<option value='$label_name'>$label_name</option>";
                                                }
                                            }
                                            $result->free(); // Free the result set
                                        ?>
                                    </select>

                        <span class="focus"></span>

                      </div>

                      <!-- dsadsadsa -->
                      <br>
                      <br>
                      <!-- last one -->
                      <fieldset>
                        <!-- <button
                          name="submit"
                          id="contact-submit"
                          onclick="submit()"
                        >
                          Submit
                        </button> -->

                        <input type="submit" onclick="submit()" id="submit" value="Create Game Mode" style=" font-size: 15px; padding: 10px 25px;background-color: #ff9d5c; color: white;border-radius: 8px;border: #ff9d5c;"/>
                       
                        <!-- <button
                          name="submit"
                          id="contact-submit"
                          data-submit="...Sending"
                        >
                          Cancel
                        </button> -->

                      </fieldset>
                </form>
                      <!-- ended last one -->
                    </div>

                  </div> 
                  <!-- form end  -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </body>
</html>
