<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="static/css/styles.css">
</head>

<body>
    <br />
    <br />
    <h1>SQL Injection workshop</h3>
        <h2 style="text-align: center;">Goal: Login as admin</h2>
        <form class="form-login" method="post">
            <div class="container">
                <label for="uname"><b>Username</b></label>
                <input type="text" placeholder="Enter Username" name="username" required>

                <label for="psw"><b>Password</b></label>
                <input type="password" placeholder="Enter Password" name="password" required>

                <label for="ema"><b>Email</b></label>
                <input type="Email" placeholder="Enter Email" name="Email" required>
                <span>
                    <?php if (isset($message)) echo $message; ?>
                </span>
                <button type="submit">Login</button>
            </div>
        </form>
</body>

</html>