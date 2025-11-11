<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Record Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: Arial, sans-serif;
        }

        .page-container {
            display: flex;
            flex-direction: column;
            height: 100%;
            background-image: url('121.jpg'); /* Make sure this image is in the same folder */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        header {
            padding: 20px 30px;
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.7); 
            backdrop-filter: blur(4px); 
            border-bottom: 1px solid rgba(240, 240, 240, 0.5);
            position: fixed; /* Fix the header to the top */
            width: 100%;
            z-index: 10;
        }

        .company-title {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50; 
            margin-left: 15px;
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .hamburger-icon {
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 30px;
            height: 21px;
        }

        .hamburger-icon .bar {
            height: 3px;
            width: 100%;
            background-color: #2c3e50; 
            border-radius: 10px;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 5px;
            margin-top: 10px;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .show {
            display: block;
        }

        main {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            /* Added padding to account for fixed header */
            padding-top: 80px; 
        }

        /* Styles for the Welcome Text */
        .welcome-text {
            color: white;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.5); /* Dark overlay for better text readability */
            padding: 40px 20px;
            border-radius: 10px;
        }

        .welcome-text h1 {
            font-size: 3em;
            margin-bottom: 10px;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        }

        .welcome-text p {
            font-size: 1.2em;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
        }
        
    </style>
</head>
<body>

    <div class="page-container">
        <header>
            <div class="dropdown">
                <div class="hamburger-icon" onclick="toggleDropdown()">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
                <div id="myDropdown" class="dropdown-content">
                    <a href="admin/">Admin Login</a>
                    <a href="employee/">Employee Login</a>
                </div>
            </div>
            <span class="company-title">Employee Record Managment</span>
        </header>

        <main>
            <div class="welcome-text">
                <h1>Welcome,<br>Employee Management Record</h1>
                <p>Streamlining HR processes with efficiency and ease.</p>
            </div>
        </main>
    </div>

    <script>
        function toggleDropdown() {
            document.getElementById("myDropdown").classList.toggle("show");
        }

        window.onclick = function(event) {
            // Check if the click was outside the dropdown button
            if (!event.target.closest('.hamburger-icon')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>

</body>
</html>