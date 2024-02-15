
<?php
 
// $dataPoints = array( 
// 	array("y" => 3373.64, "label" => "Germany" ),
// 	array("y" => 2435.94, "label" => "France" ),
// 	array("y" => 1842.55, "label" => "China" ),

// );

$conn = mysqli_connect("localhost","root","");
mysqli_select_db($conn,"playquest");
 

if (isset($_POST['user_name'])) {
  // If the key exists, assign its value to the $user_name variable
  $user_name = $_POST['user_name'];

  // Now you can use $user_name safely
  // ...

}

$user_query = "SELECT user_id FROM user WHERE user_name = ?";
$category_stmt2 = $conn->prepare($user_query);

if ($category_stmt2) {
    $category_stmt2->bind_param("s", $user_name);
    $category_stmt2->execute();
    $category_stmt2->bind_result($user_id);
    $category_stmt2->fetch();
    $category_stmt2->close();
  }
 else {
    die("Category query preparation failed: " . $conn->error);
}


// $championship_query = "SELECT champ_id FROM championship WHERE champ_name = ?";
// $category_stmt1 = $conn->prepare($championship_query);

// if ($category_stmt1) {
//     $category_stmt1->bind_param("s", $champ_name);
//     $category_stmt1->execute();
//     $category_stmt1->bind_result($champ_id);
//     $category_stmt1->fetch();
//     $category_stmt1->close();
// } else {
//     die("Category query preparation failed: " . $conn->error);
// }



$res = mysqli_query($conn, "SELECT championship.champ_name as names, SUM(user_points.points) as total_points 
                            FROM championship 
                            INNER JOIN game_mode ON championship.champ_id = game_mode.champ_id 
                            INNER JOIN user_points ON game_mode.mode_id = user_points.mode_id 
                            WHERE user_points.user_id = '$user_id' group by championship.champ_id");

$test = array();

$count = 0;

while($row = mysqli_fetch_array($res)){
  
	$test[$count]["label"] = $row["names"];
	$test[$count]["y"] = $row["total_points"];
	$count = $count + 1;
}
// $count = 0;
// while($row = mysqli_fetch_array($res2)){
// 	//$test[$count]["label"] = $row["points"];
// 	$test2[$count]["y"] = $row["user_id"];
// 	$count = $count + 1;
// }

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

	<script>
		window.onload = function() {

    var user_name = <?php echo json_encode($user_name); ?>;
		
		var chart = new CanvasJS.Chart("chartContainer", {
			animationEnabled: true,
			theme: "light2",
			title:{
				text: "",

			},
			axisY: {
				title: "Total points "
			},
			data: [{
				type: "column",
				yValueFormatString: "#,##0.## tonnes",
				dataPoints: <?php echo json_encode($test, JSON_NUMERIC_CHECK);
				
				?>
			}]
		});
		chart.render();
		
		}
	</script>
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
    <title>USER INSIGHTS | PlayQuest</title>

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
            <li class="active">
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
            <li class="active">
              <a href="./insights.php">
                <i class="fas fa-desktop"></i>
                <p style="font-size: 15px; font-weight: bold">User Insights</p>
              </a>
            </li>

            <!-- Manage Logins -->
 

            <!-- End First Section   -->

            <!-- Start Second Section -->

            <!--Add Information-->
            <li>
              <a href="#">
                <p style="color: rgb(255, 81, 0); font-size: 15px">
                  Add Information
                </p>
              </a>
            </li>

            <!-- Create New Championship -->
            <li class="#">
              <a href="./create_new_championship.php">
                <i class="far fa-plus-square"></i>
                <p style="font-size: 15px; font-weight: bold">
                  New Championship
                </p>
              </a>
            </li>

            <!-- New Category   -->
            <li >
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
            

            <!-- New Question -->
            <li>
              <a href="../example/add_question.php">
                <i class="fas fa-question"></i>
                <p style="font-size: 15px; font-weight: bold">New Question</p>
              </a>
            </li>


            <!-- New Label -->
            <li ">
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

            <!-- Question Bank   -->
            <li>
              <a href="../example/question_bank.php">
                <i class="fas fa-question-circle"></i>
                <p style="font-size: 15px; font-weight: bold">Question Bank</p>
              </a>
            </li>

            <!-- All Labels -->
            <li>
              <a href="../example/all_labels.php">
                <i class="fas fa-plus-square"></i>
                <p style="font-size: 15px; font-weight: bold">All Labels</p>
              </a>
            </li>


            <li>
              <a href="../example/add_new_faculty.php">
                <i class="fas fa-solid fa-user-plus"></i>
                <p style="font-size: 15px; font-weight: bold">New Faculty</p>
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

			  <form method="post" action="insights.php">

<div class="card-header">
  <div class="container">
	<!-- 1st one -->
	<div id="contact">
	  <fieldset>
		<p style="text-align: center;color: #ff9d5c; font-size: 30px;font-weight: bold; ">User Information</p>
		
		<hr style="height:1px; border:none;  background-color:#ff9d5c;">
		<br>
		<br>
	  </fieldset>


	   <!-- dsaddsadsasa -->

	  <p style="color: #ff9d5c;font-size: 22px;font-weight: bold; ">Select user to get information</p>
	  

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

						$sql = "SELECT user_name FROM user";
						$result = $conn->query($sql);
				 ?>
					<select style="border-color: #ff9d5c;" id="user_name" name="user_name">
						<?php
              echo "<option value='$user_name'></option>"; 
							if ($result->num_rows > 0) {
								while ($row = $result->fetch_assoc()) {
									$user_name = $row["user_name"];
									echo "<option value='$user_name'>$user_name</option>";
								}
							}
							$result->free(); // Free the result set
						?>
					</select>

			<span class="focus"></span>

	  	</div>

	  <!-- dsadsadsa -->
	  <!-- ended 2nd one -->
	  <br><br>
			
                <!-- last one -->
				 <fieldset>
                      
                      <input type="submit" onclick="submit()" id="submit" value="Search" style=" font-size: 15px; padding: 10px 25px;background-color: #ff9d5c; color: white;border-radius: 8px;border: #ff9d5c;"/>

                 </fieldset>
                      
                     
                    </div>
                  </div> 
                </form>
                  <!-- form end  -->

         <?php
						
						$conn = new mysqli('localhost', 'root', '', 'playquest');

						if ($conn->connect_error) {
							die("Connection failed: " . $conn->connect_error);
						}

            if (isset($_POST['user_name'])) {
              // If the key exists, assign its value to the $user_name variable
              $user_name = $_POST['user_name'];
          
              // Now you can use $user_name safely
              // ...
          
          }
            echo "<p style='text-align: center; color: #ff9d5c; font-size: 30px; font-weight: bold;'>$user_name</p>";

            //<p style="text-align: center;color: #ff9d5c; font-size: 30px;font-weight: bold; ">$user_name</p>


				 ?>


				  	<div id="chartContainer" style="height: 370px; width: 100%;"></div>
					<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


  </body>
</html>




