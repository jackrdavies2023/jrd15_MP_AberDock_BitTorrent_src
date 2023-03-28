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

                    <smallSeperator></smallSeperator>

                    <!-- Authentication -->
                    <h2>Authentication</h2>
                    <tinySeperator></tinySeperator>
                    <adminAuthenticationContainer>
                        <card>
                            <form>
                                <label for="login-required">Login required</label>
                                <input type="checkbox" name="login-required" id="login-required">

                                <smallSeperator></smallSeperator>

                                <label for="registration-enabled">Registration enabled</label>
                                <input type="checkbox" name="registration-enabled" id="registration-enabled">

                                <smallSeperator></smallSeperator>

                                <label for="registration-invite-only">Registration requires invite</label>
                                <input type="checkbox" name="registration-invite-only" id="registration-invite-only">

                                <smallSeperator></smallSeperator>

                                <label for="api-enabled">Enable API access</label>
                                <input type="checkbox" name="api-enabled" id="api-enabled">

                                <smallSeperator></smallSeperator>

                                <input type="submit" value="Save">
                            </form>
                        </card>
                    </adminAuthenticationContainer>

                    <smallSeperator></smallSeperator>

                    <!-- Groups -->
                    <h2>Groups</h2>
                    <tinySeperator></tinySeperator>
                    <adminGroupsContainer>
                        <card>
                            <form>
                                <label for="new-group-name">New group name</label>
                                <input type="text" id="new-group-name" name="new-group-name" placeholder="Group name">
                                <input type="submit" value="Add">
                            </form>

                            A table will go here!
                        </card>
                    </adminGroupsContainer>

                    <smallSeperator></smallSeperator>

                    <!-- Tracker/announcement -->
                    <h2>Tracker/announcement</h2>
                    <tinySeperator></tinySeperator>
                    <adminTrackerContainer>
                        <card>
                            Tracker settings here.
                        </card>
                    </adminTrackerContainer>
                {/if}
            </main>
        </mainContainer>
    </body>
</html>