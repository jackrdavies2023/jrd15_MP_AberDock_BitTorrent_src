<html lang="en">
    {include file='header.tpl'}
    <body>
        <!-- Contains the left navigation bar -->
        <navigationBar>
            <img id="aberdock_logo" src="{$assetDir}/img/logo.jpg">

            <mainLinks>
                <a href="/?p=browse">Browse</a>
                <a href="/?p=upload">Upload</a>
                <a href="/?p=statistics">Statistics</a>
            </mainLinks>

            <bottomUserOptions>
                <userName>
                    <img class="navbar-profile-img" src="{$assetDir}/img/profile.png">
                    <span>Guest account</span>
                </userName>
            </bottomUserOptions>
        </navigationBar>

        <!-- Contains the main page contents as well as navigation bar. -->
        <mainContainer>
            <!-- Contains main page content. -->
            <main>
                <titleBar>
                    <h1>Browse</h1>
                </titleBar>

                <!-- Container for the search feature -->
                <searchEngine>
                    <card>
                        <form>
                            <center>
                                <input type="text" name="query" id="search-query" placeholder="Search for something...">
                                <input type="submit" value="Search!">
                            </center>
                        </form>
                    </card>
                </searchEngine>
            </main>
        </mainContainer>
    </body>
</html>