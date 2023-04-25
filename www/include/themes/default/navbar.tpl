<navigationContainer>
    <input type="checkbox" id="open-navbar" role="button">
    <label for="open-navbar" class="open-navbar-button"><i class="hamburger-menu"></i></label>
    <label for="open-navbar" class="close-navbar-button"></label>

    <!-- Contains the left navigation bar -->
    <navigationBar>
        <img id="aberdock_logo" src="{$assetDir}/img/logo.jpg">

        <mainLinks>
            <a href="/?p=browse"><i class="search"></i> Browse</a>

            {if $accountInfo['can_upload'] eq 1}
                <a href="/?p=upload"><i class="upload"></i> Upload</a>
            {/if}

            {if $accountInfo['can_viewstats'] eq 1}
                <a href="/?p=statistics"><i class="statistics"></i> Statistics</a>
            {/if}

            {if $accountInfo['is_guest'] eq 0}
                <a href="/?p=bookmarks"><i class="bookmark"></i> Bookmarks</a>
            {/if}

            {if $accountInfo['is_admin'] eq 1}
                <a href="/?p=administration"><i class="administration"></i> Administration</a>
            {/if}

            {if $accountInfo['is_guest'] eq 1}
                <a href="/?p=login"><i class="login"></i> Login</a>
            {else}
                <a href="/?p=login&logout"><i class="login"></i> Logout</a>
            {/if}
        </mainLinks>

        <bottomUserOptions>
            <userName>
                <a href="/?p=profile&uuid={$accountInfo['uid_long']}">
                    <img class="navbar-profile-img" src="{$assetDir}/img/profile.png">
                </a>
                <span><a href="/?p=profile&uuid={$accountInfo['uid_long']}">{$accountInfo['username']}</a></span>
            </userName>
        </bottomUserOptions>
    </navigationBar>
</navigationContainer>

<!-- This is to provide spacing in mobile mode, when the menu is open.
         Without this, the page contents behind the menu will jump around when
         we hide the hamburger menu. -->
<navigationBarToggleSpacer></navigationBarToggleSpacer>