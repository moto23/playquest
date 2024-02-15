
   function login(){
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    if (username === "admin" && password === "admin") {
        location.href="../example/dashboard.php";
    } else {
        alert("Incorrect username or password");
    }
   }
  

    
