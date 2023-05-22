<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://cdn.jsdelivr.net/npm/appwrite@11.0.0"></script>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Dashboard | Login</title>
    <link rel="stylesheet" href="./css/login.css">

    <!-- SCRIPTS -->
    <script>
        const { Client, Account, ID, Storage } = Appwrite;

        function Login() {
            const client = new Client()
                .setEndpoint('http://51.161.212.158:9191/v1') // Your API Endpoint
                .setProject('64511dda13070874dfb6');               // Your project ID

            const account = new Account(client);

            let Email = document.getElementById("Email").value;
            let Pass = document.getElementById("Password").value;

            const promise = account.createEmailSession(Email, Pass);

            promise.then(function (response) {
                console.log(response); // Success
                window.location.href = "Dashboard.php";
            }, function (error) {
                console.log(error); // Failure
            });


        }
    </script>
</head>

<body>
    <section>
        <div class="color"></div>
        <div class="color"></div>
        <div class="color"></div>
        <div class="box">
            <div class="square" style="--i:0;"></div>
            <div class="square" style="--i:1;"></div>
            <div class="square" style="--i:2;"></div>
            <div class="square" style="--i:3;"></div>
            <div class="square" style="--i:4;"></div>
            <div class="container">
                <div class="form">
                    <h2>Login Form</h2>
                    <form>
                        <div class="inputBox">
                            <input id="Email" type="text" placeholder="Email">
                        </div>
                        <div class="inputBox">
                            <input id="Password" type="password" placeholder="Password">
                        </div>
                        <div class="inputBox">

                        </div>

                    </form>

                    <button onclick="Login()">Login</button>
                    <p class="forget">Forgot Password ? <a href="#">Click Here</a></p>
                    <p class="forget">Don't have an account ? <a href="#">Sign Up</a></p>
                </div>
            </div>
        </div>
    </section>
</body>

</html>