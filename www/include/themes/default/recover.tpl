<!DOCTYPE html>
<html lang="en">
    {include file='header.tpl'}
    <body class="login-background">
        <loginContainer>
            <card class="login-card">
                <card class="login-card-title rounded-top-corners">
                    Recover account
                </card>

                <smallSeperator></smallSeperator>

                <loginCardContainer>
                    <form method="POST">
                        <input type="text" id="username" name="username" placeholder="Username">
                        <smallSeperator></smallSeperator>

                        <input type="password" id="recovery-key" name="recovery-key" placeholder="Recovery key">
                        <smallSeperator></smallSeperator>

                        <input type="submit" value="Recover">
                        <smallSeperator></smallSeperator>
                    </form>
                </loginCardContainer>

                <loginCardContainer>
                        <a href="/?p=register">Don't have an account?</a><br>
                        <a href="/?p=login">Remember your password?</a>
                </loginCardContainer>
            </card>
        </loginContainer>
    </body>
</html>