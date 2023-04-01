<html lang="en">
    {include file='header.tpl'}
    <body class="login-background">
        <errorContainer>
            <card class="login-card error-card">
                <card class="login-card-title rounded-top-corners">
                    Error ({$exceptionCode})
                </card>

                <errorCardConainer>

                A critical error has occured. Error message:
                <smallSeperator></smallSeperator>

<pre>{$exceptionMessage}</pre>

                </errorCardContainer>
            </card>
        </errorContainer>
    </body>
</html>