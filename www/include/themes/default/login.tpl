<!DOCTYPE html>
<html lang="en">
    {include file='header.tpl'}
    <body class="login-background">
        <loginContainer>
            <card class="login-card">
                <card class="login-card-title rounded-top-corners">
                    Login
                </card>

                <smallSeperator></smallSeperator>

                <loginCardContainer>
                    <form method="POST">
                        <input type="text" id="username" name="username" placeholder="Username">
                        <smallSeperator></smallSeperator>
                        <input type="password" id="password" name="password" placeholder="Password">
                        <smallSeperator></smallSeperator>
                        <label for="remember">Remember session</label>
                        <input type="checkbox" name="remember" id="remember">
                        <input type="submit" value="Login">
                        <smallSeperator></smallSeperator>
                    </form>
                </loginCardContainer>

                <loginCardContainer>
                        <a href="/?p=register">Don't have an account?</a><br>
                        <a href="/?p=recover">Forgot your password?</a>
                </loginCardContainer>
            </card>
        </loginContainer>
    </body>
</html>