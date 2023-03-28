<html lang="en">
    {include file='header.tpl'}
    <body>
        {include file='navbar.tpl'}

        <!-- Contains the main page contents as well as navigation bar. -->
        <mainContainer>
            <!-- Contains main page content. -->
            <main>
                {if isset($exceptionCode)}
                    <card>
                        {$exceptionMessage}
                    </card>
                {else}
                    <titleBar>
                        <h1>Administration</h1>
                    </titleBar>
                {/if}
            </main>
        </mainContainer>
    </body>
</html>