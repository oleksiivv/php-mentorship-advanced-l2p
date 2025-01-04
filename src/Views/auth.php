<h3>Login</h3>

<form action="/auth/login" method="post">
    <div>
        <label for="email">Login:</label>
        <input type="text" id="email" name="email" placeholder="Login">
    </div>
    <br/>

    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Password">
    </div>
    <br/>

    <br/>

    <input type="submit" value="Login">

</form>

<hr/>

<h3>Register</h3>

<form action="/auth/register" method="post">
    <div>
        <label for="email">Login:</label>
        <input type="text" id="email" name="email" placeholder="Login">
    </div>
    <br/>

    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Password">
    </div>
    <br/>

    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="Name">
    </div>
    <br/>

    <br/>

    <input type="submit" value="Register">

</form>