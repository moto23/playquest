<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "SilentRoot11";
$database = "playquest";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Capture the form data
  $label = $_POST['label'];
  // $question_text = $_POST['q_text'];
  $question_text = strip_tags($_POST['q_text']);
  $question_image = $_POST['img0'];
  $option1_text = strip_tags($_POST['option_1']);
  $option2_text = strip_tags($_POST['option_2']);
  $option3_text = strip_tags($_POST['option_3']);
  $option4_text = strip_tags($_POST['option_4']);
  $option1_img = $_POST['img1'];
  $option2_img = $_POST['img2'];
  $option3_img = $_POST['img3'];
  $option4_img = $_POST['img4'];
  $total_coins = $_POST['total_coins'];
  $correct_opts = $_POST['correct_option']; // Updated to handle multiple options

  // Convert correct options to numeric values
  $correct_options = [];
  foreach ($correct_opts as $correct_opt) {
      switch ($correct_opt) {
          case "option_1":
              $correct_options[] = 1;
              break;
          case "option_2":
              $correct_options[] = 2;
              break;
          case "option_3":
              $correct_options[] = 3;
              break;
          case "option_4":
              $correct_options[] = 4;
              break;
      }
  }

  // Get label_id
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

  // Insert question into the database
  $insert_query = "INSERT INTO question (label_id, question_text, question_image, option1_text, option2_text, option3_text, option4_text, option1_img, option2_img, option3_img, option4_img, total_coins) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $insert_stmt = $conn->prepare($insert_query);

  if ($insert_stmt) {
      $insert_stmt->bind_param("ssssssssssss", $label_id, $question_text, $question_image, $option1_text, $option2_text, $option3_text, $option4_text, $option1_img, $option2_img, $option3_img, $option4_img, $total_coins);
      $insert_stmt->execute();
      $insert_stmt->close();
  } else {
      die("Insert query preparation failed: " . $conn->error);
  }

  // Get question_id
  $questionid_query = "SELECT question_id from question where label_id = ? and question_text = ?";
  $questionid_stmt = $conn->prepare($questionid_query);

  if ($questionid_stmt) {
      $questionid_stmt->bind_param("ss", $label_id, $question_text);
      $questionid_stmt->execute();
      $questionid_stmt->bind_result($question_id);
      $questionid_stmt->fetch();
      $questionid_stmt->close();
  } else {
      die("Category query preparation failed: " . $conn->error);
  }

  // Insert correct options into the database
  foreach ($correct_options as $correct_opt) {
      $correct_query = "INSERT INTO answer (question_id, correct_answer) VALUES (?, ?)";
      $correct_stmt = $conn->prepare($correct_query);

      if ($correct_stmt) {
          $correct_stmt->bind_param("ss", $question_id, $correct_opt);
          $correct_stmt->execute();
          $correct_stmt->close();
      } else {
          die("Correct answer query preparation failed: " . $conn->error);
      }
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
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap" rel="stylesheet" />
  <!-- fonts -->


  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,300,0,0" />



  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.css" integrity="sha512-0nkKORjFgcyxv3HbE4rzFUlENUMNqic/EzDIeYCgsKa/nwqr2B91Vu/tNAu4Q0cBuG4Xe/D1f/freEci/7GDRA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


  <script data-require="jquery" data-semver="2.1.3" src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
  <script data-require="bootstrap" data-semver="3.3.2" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
  <script data-require="angular.js@1.3.x" src="https://code.angularjs.org/1.3.14/angular.js" data-semver="1.3.14"></script>
  <script data-require="ui-bootstrap" src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.13.0/ui-bootstrap-tpls.min.js" data-semver="0.13.0"></script>
  
  <script src="https://cdn.tiny.cloud/1/wvjltc6mnndu8zso5u6bp1ekge1z947lxhtw8j43k1byzm1i/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>


  <!-- Choose multiple options -->
  


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

    #contact input[type="text"] {
      width: 100%;
      border: 1px solid #ff9d5c;
      background: #fff;
      margin: 0 0 5px;
      padding: 10px;
    }

    #contact input[type="text"]:hover {
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

    #contact #btn{
      display: flex;
    }

    .select_label_popup #close-btn{
      margin-left:auto;
      margin-right:5px;
    }

    #submit_btn{
      margin-top:7px;
      margin-bottom:10px;
      margin-left:26.5rem;
    }

    .select_label_popup{
      overflow:hidden;
      clear:both;
      position:absolute;
      top:0;
      /* left:50%; */
      transform:translate(-50%,-50%) scale(0.1);
      transition:transform 0.4s, top 0.4s;
      visibility: hidden;
    }

    .open-popup{
      margin-top:50px;
      position:fixed;
      z-index: 2;
      top: 30%;
      left: 60%;
      visibility: visible;
      width:600px;
      transform:translate(-50%,-50%) scale(1);
    }

    .active{
      cursor:move;
      user-select:none;
    }

    #search_label{
      width: 100%;
      border: 1px solid #ff9d5c;
      background: #fff;
      margin: 0 0 5px;
      padding: 10px;
    }

    /* #label_select{
      cursor:pointer !important;
    } */

    #contact #btn button[type="button"]:hover{
      background-color: #e65100;
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
  <script src="https://kit.fontawesome.com/52f3e32cf9.js" crossorigin="anonymous"></script>

  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/title.png" />
  <link rel="icon" type="image/png" href="../assets/img/title.png" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>ADD NEW QUESTION | PlayQuest</title>

  <!-- <script>
    tinymce.init({
      selector: "#mytextarea",
    });
  </script> -->

  <!-- tiny mce -->

  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no" name="viewport" />
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../assets/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="../assets/demo/demo.css" rel="stylesheet" />
</head>

<body>
  <div class="wrapper">

    <div class="sidebar" data-color="white" data-active-color="danger">
      <div style="display: flex; flex-direction: column; align-items: center" class="logo">
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
          <li>
            <a href="#">
              <p style="color: rgb(255, 81, 0); font-size: 15px">Main Menu</p>
            </a>
          </li>

          <!-- Dashboard -->
          <li>
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
              <i class="fas fa-align-justify"></i>
              <p style="font-size: 15px; font-weight: bold">New Category</p>
            </a>
          </li>

          <!-- New Game Mode   -->
          <li>
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
          <li class="active">
            <a href="../example/add_question.php">
              <i class="fas fa-question"></i>
              <p style="font-size: 15px; font-weight: bold">New Question</p>
            </a>
          </li>

          <!-- New Label -->
          <li>
            <a href="../example/new_label.php">
              <i class="fas fa-tag"></i>
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
              <i class="fas fa-trophy"></i>
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
              <i class="fas fa-plus-square"></i>
              <p style="font-size: 15px; font-weight: bold">All Labels</p>
            </a>
          </li>

          <!-- Wrong Questions -->
          <li>
            <a href="../example/wrong_questions.php">
              <i class="fas fa-times-circle"></i>
              <p style="font-size: 15px; font-weight: bold">Wrong Questions</p>
            </a>
          </li>
          <!-- End Fourth Section   -->
        </ul>
      </div>
    </div>



    <div class="main-panel" style="height: 70px;">
      <!-- Navbar -->
      <nav class="
            navbar navbar-expand-lg navbar-absolute
            fixed-top
            navbar-transparent
          " style="height: 70px;">
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
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <ul class="navbar-nav">
              <li class="nav-item btn-rotate dropdown">
                <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-user-circle"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                  <a class="dropdown-item" style="text-align: center" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout</a>
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
              
            <div class="select_label_popup" id="select_label_popup" style="z-index:999;box-shadow:2px 2px 3px 3px rgba(0,0,0,0.15);border-radius:5px;background-color:#fff;">
              <div onclick="closePopup()" id="close-btn" style="cursor:pointer;margin-top:5px;margin-bottom:8px;width:20px;text-align:center;height:20px;background:#000;color:#eee;line-height:20px;border-radius:17px;">&times;</div>
              <form action="add_question.php" method="post">  
                <div style=" display: grid; width:74.5ch;margin-left:6px;margin-right:5px;font-size: 16px;" class="select">
                  <?php
                  // PHP code to retrieve category names and populate the dropdown
                  $conn = new mysqli('localhost', 'root', 'SilentRoot11', 'playquest');

                  if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                  }

                  $sql = "SELECT label_name FROM label";
                  $result = $conn->query($sql);
                  ?>
                  <select class="js-example-basic-multiple" style="cursor:pointer;" id="label_name" name="labels[]" multiple="multiple">
                    <?php
                    if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        $cat_name = $row["label_name"];
                        echo "<option value='$cat_name'>$cat_name</option>";
                      }
                    }
                    $result->free(); // Free the result set
                    ?>
                  </select>

                  <?php
                  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Access the selected category from the dropdown using $_POST
                    $selected_category = $_POST['label_name'];

                    // Process the selected category as needed
                  }
                  ?>

                  <span class="focus"></span>

                </div>
                </form>
              <!-- <fieldset style="display:flex;align-items:center;">
                <input placeholder="Search Label" id="search_label" type="text" style="margin-left:10px;margin-top:7px;margin-right:7px;" tabindex="1" name="label" required autofocus /> <br><br>
                <button id="submit_btn" style="align-items:center;font-size: 20px; padding: 2px 5px;background-color: #ff9d5c; color: white;border-radius: 5px;border: #ff9d5c;">Search</button>
              </fieldset> -->
              <div class="container-fluid" style="height:100%;margin-bottom:10px;">
                
              </div>
              <button id="submit_btn" style="align-items:center;font-size: 20px; padding: 2px 5px;background-color: #ff9d5c; color: white;border-radius: 5px;border: #ff9d5c;" value="Select" name="select">Select</button>
            </div>
            <!-- <div class="tiny_mce_editor" id="tiny_mce_editor" style="box-shadow:2px 2px 3px 3px rgba(0,0,0,0.15);border-radius:5px;background-color:#fff;">
              <div onclick="closeEditor()" id="close-btn" style="cursor:pointer;margin-top:5px;margin-bottom:5px;width:20px;text-align:center;height:20px;background:#000;color:#eee;line-height:20px;border-radius:17px;">&times;</div>
            <script>
            const images_upload_handler_callback = (blobInfo, progress) => new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', 'upload.php');
            xhr.upload.onprogress = (e) => {
                progress(e.loaded / e.total * 100);
            };
            xhr.onload = () => {
                if (xhr.status == 403) {
                    reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
                    return;
                }
                if (xhr.status < 200 || xhr.status >= 300) {
                    reject('HTTP Error: ' + xhr.status);
                    return;
                }

                const json = JSON.parse(xhr.responseText);
                if (!json || typeof json.location != 'string') {
                    reject('INVALID Json: ' + xhr.responseText);
                    return;
                }
                resolve(json.location);
            };

            xhr.onerror = () => {
                reject('Image upload failed' + xhr.status);
            }

            const formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            xhr.send(formData);
            })

                tinymce.init({
                    selector: '#mytextarea',
                    height: 175,
                    plugins: 'mentions anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat | image',
                    images_upload_url: 'upload.php',
                    images_upload_handler: images_upload_handler_callback
                });
            </script>
            <textarea id="mytextarea"></textarea>
              <button id="submit_btn" style="align-items:center;font-size: 20px; padding: 2px 5px;background-color: #ff9d5c; color: white;border-radius: 5px;border: #ff9d5c;">Submit</button>  
              </div> -->

              <!-- form start  -->
              <form method="post" action="add_question.php">

                <div class="card-header">
                  <div class="container">
                    <!-- 1st one -->
                    <div id="contact">
                      <fieldset>
                        <p style="text-align: center;color: #ff9d5c; font-size: 30px;font-weight: bold; ">Add New Question</p>

                        <hr style="height:1px; border:none;  background-color:#ff9d5c;">
                        <br>
                        <br>
                      </fieldset>


                      <!-- dsaddsadsasa -->

                      <p style="color: #ff9d5c;font-size: 22px;font-weight: bold; ">Add/Edit Labels</p>
                      <p style="color: #00c800;font-size: 18px">Questions in the Championship will appear from Questions under the label selected from here</p>
                      
                      <button onclick="openPopup()" style=" font-size: 15px;font-weight:bold; padding: 10px 12px;background-color: #ff9d5c; color: white;border-radius: 8px;border: #ff9d5c;" type="button">Select Labels</button>
                      <br>
                      <fieldset>
                        <?php
                          if(isset($_POST['select'])){
                            if(!empty($_POST['labels'])){
                              foreach($_POST['labels'] as $labels){
                                echo "<div style='z-index:99;'>$labels</div>";
                              }
                            }
                          }
                        ?>
                      </fieldset>
                      <br>
                      <!-- <div style=" display: grid; min-width: 89ch; font-size: 16px" class="select">
                        <?php
                        // PHP code to retrieve category names and populate the dropdown
                        // $conn = new mysqli('localhost', 'root', 'SilentRoot11', 'playquest');


                        // if ($conn->connect_error) {
                        //   die("Connection failed: " . $conn->connect_error);
                        // }

                        // $sql = "SELECT label_name FROM label";
                        // $result = $conn->query($sql);
                        // ?>
                        // <select style="border-color: #ff9d5c;" id="label" name="label">
                        //   <?php
                        //   if ($result->num_rows > 0) {
                        //     while ($row = $result->fetch_assoc()) {
                        //       $label_name = $row["label_name"];
                        //       echo "<option value='$label_name'>$label_name</option>";
                        //     }
                        //   }
                        //   $result->free(); // Free the result set
                          ?>
                        </select>

                        <span class="focus"></span>

                      </div> -->
                      <!-- dsadsadsa -->
                      <!-- ended 2nd one -->
                      <br><br>
                      <!-- 3rd one -->
                      <!-- <fieldset id="btn"> -->
                        <p style="color: #ff9d5c;font-size: 22px;font-weight: bold;">Question Text</p>
                        <!-- <button onclick="openEditor()" class="editor" style="margin-left:15px;margin-bottom: 10px;display:inline-flex;align-items:center;gap:7px; font-size: 20px; padding: 0px 5px;background-color: #ff9d5c; color: white;border-radius: 5px;border: #ff9d5c;" type="button">
                          <span class="material-symbols-outlined">edit_square</span>
                          <span>Editor</span>
                        </button> -->
                      <!-- </fieldset> -->

                      <!-- editor -->

                      <fieldset>
                      <script>
            const images_upload_handler_callback = (blobInfo, progress) => new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', 'upload.php');
            xhr.upload.onprogress = (e) => {
                progress(e.loaded / e.total * 100);
            };
            xhr.onload = () => {
                if (xhr.status == 403) {
                    reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
                    return;
                }
                if (xhr.status < 200 || xhr.status >= 300) {
                    reject('HTTP Error: ' + xhr.status);
                    return;
                }

                const json = JSON.parse(xhr.responseText);
                if (!json || typeof json.location != 'string') {
                    reject('INVALID Json: ' + xhr.responseText);
                    return;
                }
                resolve(json.location);
            };

            xhr.onerror = () => {
                reject('Image upload failed' + xhr.status);
            }

            const formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            xhr.send(formData);
            })

                tinymce.init({
                    selector: '#mytextarea',
                    height: 175,
                    plugins: 'mentions anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat | image',
                    images_upload_url: 'upload.php',
                    images_upload_handler: images_upload_handler_callback
                });
            </script>
            <textarea id="mytextarea" name="q_text"></textarea>
            </fieldset>
            <br>
                      <!-- <input placeholder="" id="question_text" type="text" style="margin-top:7px;" tabindex="1" name="q_text" required autofocus /> <br><br> -->

                      <p style="color: #ff9d5c;font-size: 22px;font-weight: bold; ">Add Question image</p>

                      <input type="file" id="img" name="img0" accept="image/*">

                      <!-- ended 3rd one -->
                      <br>
                      <br>

                      <fieldset>
                        <!-- <fieldset id="btn"> -->
                          <p style="color: #ff9d5c; font-size: 22px;font-weight: bold;margin-bottom:0.7rem;">Option 1</p>
                          <!-- <button onclick="openEditor()" class="editor" style="margin-left:15px;margin-bottom: 10px;display:inline-flex;align-items:center;gap:7px; font-size: 20px; padding: 0px 5px;background-color: #ff9d5c; color: white;border-radius: 5px;border: #ff9d5c;" type="button">
                            <span class="material-symbols-outlined">edit_square</span>
                            <span>Editor</span>
                          </button> -->
                        <!-- </fieldset> -->
                        <fieldset style="z-index:0;">
                      <script>
                            const images_upload_handler_callback = (blobInfo, progress) => new Promise((resolve, reject) => {
                            const xhr = new XMLHttpRequest();
                            xhr.withCredentials = false;
                            xhr.open('POST', 'upload.php');
                            xhr.upload.onprogress = (e) => {
                                progress(e.loaded / e.total * 100);
                            };
                            xhr.onload = () => {
                                if (xhr.status == 403) {
                                    reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
                                    return;
                                }
                                if (xhr.status < 200 || xhr.status >= 300) {
                                    reject('HTTP Error: ' + xhr.status);
                                    return;
                                }

                                const json = JSON.parse(xhr.responseText);
                                if (!json || typeof json.location != 'string') {
                                    reject('INVALID Json: ' + xhr.responseText);
                                    return;
                                }
                                resolve(json.location);
                            };

                            xhr.onerror = () => {
                                reject('Image upload failed' + xhr.status);
                            }

                            const formData = new FormData();
                            formData.append('file', blobInfo.blob(), blobInfo.filename());

                            xhr.send(formData);
                            })

                                tinymce.init({
                                    selector: '#mytextarea',
                                    height: 175,
                                    plugins: 'mentions anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
                                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat | image',
                                    images_upload_url: 'upload.php',
                                    images_upload_handler: images_upload_handler_callback
                                });
                            </script>
                            <textarea id="mytextarea" name="option_1"></textarea>
                            </fieldset>
                          <!-- <input placeholder="Enter Option 1" id="option_1" name="option_1" type="text" tabindex="1" required autofocus /> -->
                      </fieldset>
                      <br>
                      <p style="color: #ff9d5c;font-size: 22px;font-weight: bold;">option 1 image</p>

                      <input type="file" id="img" style="margin-bottom:12px;" name="img1" accept="image/*">
                      <br>
                      <br>

                      <fieldset>
                        <!-- <fieldset id="btn"> -->
                          <p style="color: #ff9d5c; font-size: 22px;font-weight: bold;">Option 2</p>
                          <!-- <button onclick="openEditor()" class="editor" style="margin-left:15px;margin-bottom: 10px;display:inline-flex;align-items:center;gap:7px; font-size: 20px; padding: 0px 5px;background-color: #ff9d5c; color: white;border-radius: 5px;border: #ff9d5c;" type="button">
                            <span class="material-symbols-outlined">edit_square</span>
                            <span>Editor</span>
                          </button> -->
                        <!-- </fieldset> -->
                        <fieldset>
                          <script>
                            const images_upload_handler_callback = (blobInfo, progress) => new Promise((resolve, reject) => {
                            const xhr = new XMLHttpRequest();
                            xhr.withCredentials = false;
                            xhr.open('POST', 'upload.php');
                            xhr.upload.onprogress = (e) => {
                                progress(e.loaded / e.total * 100);
                            };
                            xhr.onload = () => {
                                if (xhr.status == 403) {
                                    reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
                                    return;
                                }
                                if (xhr.status < 200 || xhr.status >= 300) {
                                    reject('HTTP Error: ' + xhr.status);
                                    return;
                                }

                                const json = JSON.parse(xhr.responseText);
                                if (!json || typeof json.location != 'string') {
                                    reject('INVALID Json: ' + xhr.responseText);
                                    return;
                                }
                                resolve(json.location);
                            };

                            xhr.onerror = () => {
                                reject('Image upload failed' + xhr.status);
                            }

                            const formData = new FormData();
                            formData.append('file', blobInfo.blob(), blobInfo.filename());

                            xhr.send(formData);
                            })

                                tinymce.init({
                                    selector: '#mytextarea',
                                    height: 175,
                                    plugins: 'mentions anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
                                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat | image',
                                    images_upload_url: 'upload.php',
                                    images_upload_handler: images_upload_handler_callback
                                });
                            </script>
                            <textarea id="mytextarea" name="option_2"></textarea>
                            </fieldset>
                            <br>
                        <!-- <input placeholder="Enter Option 2" id="option_2" name="option_2" type="text" tabindex="1" required autofocus /> -->
                        <!-- <br> -->
                        <p style="color: #ff9d5c;font-size: 22px;font-weight: bold; ">option 2 image</p>

                        <input type="file" id="img" style="margin-bottom:12px;" name="img2" accept="image/*">
                      </fieldset>
                      <br>
                      <br>

                      <fieldset>
                        <!-- <fieldset id="btn"> -->
                          <p style="color: #ff9d5c; font-size: 22px;font-weight: bold;margin-bottom:2px; ">Option 3</p>
                          <!-- <button onclick="openEditor()" class="editor" style="margin-left:15px;margin-bottom: 10px;display:inline-flex;align-items:center;gap:7px; font-size: 20px; padding: 0px 5px;background-color: #ff9d5c; color: white;border-radius: 5px;border: #ff9d5c;" type="button">
                            <span class="material-symbols-outlined">edit_square</span>
                            <span>Editor</span>
                          </button> -->
                        <!-- </fieldset> -->
                        <fieldset>
                          <script>
                            const images_upload_handler_callback = (blobInfo, progress) => new Promise((resolve, reject) => {
                            const xhr = new XMLHttpRequest();
                            xhr.withCredentials = false;
                            xhr.open('POST', 'upload.php');
                            xhr.upload.onprogress = (e) => {
                                progress(e.loaded / e.total * 100);
                            };
                            xhr.onload = () => {
                                if (xhr.status == 403) {
                                    reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
                                    return;
                                }
                                if (xhr.status < 200 || xhr.status >= 300) {
                                    reject('HTTP Error: ' + xhr.status);
                                    return;
                                }

                                const json = JSON.parse(xhr.responseText);
                                if (!json || typeof json.location != 'string') {
                                    reject('INVALID Json: ' + xhr.responseText);
                                    return;
                                }
                                resolve(json.location);
                            };

                            xhr.onerror = () => {
                                reject('Image upload failed' + xhr.status);
                            }

                            const formData = new FormData();
                            formData.append('file', blobInfo.blob(), blobInfo.filename());

                            xhr.send(formData);
                            })

                                tinymce.init({
                                    selector: '#mytextarea',
                                    height: 175,
                                    plugins: 'mentions anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
                                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat | image',
                                    images_upload_url: 'upload.php',
                                    images_upload_handler: images_upload_handler_callback
                                });
                            </script>
                            <textarea id="mytextarea" name="option_3"></textarea>
                            </fieldset>
                        <!-- <input placeholder="Enter Option 3" id="option_3" style="margin-bottom:0px;" type="text" name="option_3" tabindex="1" required autofocus /> -->
                      </fieldset>
                      <br>
                      <p style="color: #ff9d5c;font-size: 22px;font-weight: bold;margin-top:0px ">option 3 image</p>
                      <input type="file" id="img" style="margin-bottom:12px;" name="img3" accept="image/*">
                      <br>
                      <br>

                      <fieldset>
                        <!-- <fieldset id="btn"> -->
                          <p style="color: #ff9d5c; font-size: 22px;font-weight: bold; ">Option 4</p>
                          <!-- <button onclick="openEditor()" class="editor" style="margin-left:15px;margin-bottom: 10px;display:inline-flex;align-items:center;gap:7px; font-size: 20px; padding: 0px 5px;background-color: #ff9d5c; color: white;border-radius: 5px;border: #ff9d5c;" type="button">
                            <span class="material-symbols-outlined">edit_square</span>
                            <span>Editor</span> 
                          </button> -->
                        <!-- </fieldset> -->
                        <fieldset>
                          <script>
                            const images_upload_handler_callback = (blobInfo, progress) => new Promise((resolve, reject) => {
                            const xhr = new XMLHttpRequest();
                            xhr.withCredentials = false;
                            xhr.open('POST', 'upload.php');
                            xhr.upload.onprogress = (e) => {
                                progress(e.loaded / e.total * 100);
                            };
                            xhr.onload = () => {
                                if (xhr.status == 403) {
                                    reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
                                    return;
                                }
                                if (xhr.status < 200 || xhr.status >= 300) {
                                    reject('HTTP Error: ' + xhr.status);
                                    return;
                                }

                                const json = JSON.parse(xhr.responseText);
                                if (!json || typeof json.location != 'string') {
                                    reject('INVALID Json: ' + xhr.responseText);
                                    return;
                                }
                                resolve(json.location);
                            };

                            xhr.onerror = () => {
                                reject('Image upload failed' + xhr.status);
                            }

                            const formData = new FormData();
                            formData.append('file', blobInfo.blob(), blobInfo.filename());

                            xhr.send(formData);
                            })

                                tinymce.init({
                                    selector: '#mytextarea',
                                    height: 175,
                                    plugins: 'mentions anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
                                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat | image',
                                    images_upload_url: 'upload.php',
                                    images_upload_handler: images_upload_handler_callback
                                });
                            </script>
                            <textarea id="mytextarea" name="option_4"></textarea>
                            </fieldset>
                            <br>
                        <!-- <input placeholder="Enter Option 4" id="option_4" name="option_4" type="text" tabindex="1" required autofocus /> -->
                      </fieldset>
                      <p style="color: #ff9d5c;font-size: 22px;font-weight: bold; ">option 4 image</p>

                      <input type="file" id="img" style="margin-bottom:12px;" name="img4" accept="image/*">
                      <br>
                      <br>

                      <!-- dsaddsadsasa -->
                      <fieldset>
                        <p style="color: #ff9d5c; font-size: 22px; font-weight: bold;">Correct Option</p>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); min-width: 89ch; font-size: 16px; grid-column-gap: 5px;" class="select" multiple>
                            <div>
                                <input style="border-color: #ff9d5c;margin-left:7rem" type="checkbox" id="correct_option_1" name="correct_option[]" value="option_1">
                                <label for="correct_option_1" style="color:black;">Option 1</label><br>
                            </div>
                            <div>
                                <input style="border-color: #ff9d5c;margin-left:0rem" type="checkbox" id="correct_option_2" name="correct_option[]" value="option_2">
                                <label for="correct_option_2" style="color:black;">Option 2</label><br>
                            </div>
                            <div style="grid-column: 1;">
                                <input style="border-color: #ff9d5c;margin-left:7rem" type="checkbox" id="correct_option_3" name="correct_option[]" value="option_3">
                                <label for="correct_option_3" style="color:black;">Option 3</label><br>
                            </div>
                            <div style="grid-column: 2;">
                                <input style="border-color: #ff9d5c;margin-left:0rem" type="checkbox" id="correct_option_4" name="correct_option[]" value="option_4">
                                <label for="correct_option_4" style="color:black;">Option 4</label><br>
                            </div>
                        </div>
                    </fieldset>
                      <br>

                      <!-- dsadsadsa -->

                      <fieldset>
                        <p style="color: #ff9d5c; font-size: 22px; width: 100%;font-weight: bold; ">Total Coins</p>
                        <input placeholder="Enter Number of Coins" id="total_coins" type="text" name="total_coins" tabindex="1" required autofocus />
                      </fieldset>

                      <br>
                      <br>
                      <!-- last one -->
                      <fieldset>

                        <input type="submit" onclick="submit()" id="submit" value="Add Question" style=" font-size: 15px; padding: 10px 25px;background-color: #ff9d5c; color: white;border-radius: 8px;border: #ff9d5c;" />

                      </fieldset>
                      <!-- ended last one -->
                    </div>

                  </div>
              </form>
              <!-- form end  -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!--   Core JS Files   -->
  <script>
    let popup=document.getElementById("select_label_popup");
    let popup_window=document.querySelector(".select_label_popup");
    
    function openPopup(){
      popup.classList.add("open-popup");
    }

    function closePopup(){
      popup.classList.remove("open-popup");
    }

    function onDrag({movementX,movementY}){
      let getStyle=window.getComputedStyle(popup_window);
      let leftValue=parseInt(getStyle.left);
      let topValue=parseInt(getStyle.top);
      console.log(leftValue);

      popup_window.style.left=`${leftValue+movementX}px`;
      popup_window.style.top=`${topValue+movementY}px`;
    }

    popup_window.addEventListener('mousedown',()=>{
      popup_window.classList.add("active");
      popup_window.addEventListener('mousemove',onDrag);
    })

    document.addEventListener('mouseup',()=>{
      popup_window.classList.remove("active");
      popup_window.removeEventListener('mousemove',onDrag);
    })
  </script>
  <script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2({
          placeholder: 'Select a Label',
          allowClear: true,
          scrollAfterSelect:true
        });
    });
  </script>

</body>

</html>