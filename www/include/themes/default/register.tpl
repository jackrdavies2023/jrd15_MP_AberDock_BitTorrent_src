<!DOCTYPE html>
<html lang="en">
    {include file='header.tpl'}
    <body class="login-background">
        <loginContainer>
            <card class="login-card registration-card">
                <card class="login-card-title rounded-top-corners">
                    Registration
                </card>

                <smallSeperator></smallSeperator>

                <loginCardContainer>
                    <form method="POST">
                        <input type="text" id="username" name="username" placeholder="Username">
                        <smallSeperator></smallSeperator>

                        <input type="password" id="password" name="password" placeholder="Password">
                        <smallSeperator></smallSeperator>

                        <input type="password" id="password-confirmation" name="password-confirmation" placeholder="Password confirmation">
                        <smallSeperator></smallSeperator>

                        <select type="text" id="language" name="language">
                            <option value="eng">English</option>
                            <option value="cym">Cymraeg</option>
                        </select>
                        <smallSeperator></smallSeperator>

                        <input type="submit" value="Register">
                        <smallSeperator></smallSeperator>
                    </form>
                </loginCardContainer>

                <loginCardContainer>
                        <a href="/?p=login">Already have an account?</a><br>
                        <a href="/?p=recover">Forgot your password?</a>
                </loginCardContainer>
            </card>
        </loginContainer>
    </body>
</html>