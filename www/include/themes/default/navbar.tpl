<!-- Contains the left navigation bar -->
<navigationBar>
    <img id="aberdock_logo" src="{$assetDir}/img/logo.jpg">

    <mainLinks>
        <a href="/?p=browse"><i class="search"></i> Browse</a>
        <a href="/?p=upload"><i class="upload"></i> Upload</a>
        <a href="/?p=statistics"><i class="statistics"></i> Statistics</a>

        {if $accountInfo['is_guest'] eq 1}
            <a href="/?p=login"><i class="login"></i> Login</a>
        {else}
            <a href="/?p=login&logout"><i class="login"></i> Logout</a>
        {/if}
    </mainLinks>

    <bottomUserOptions>
        <userName>
            <img class="navbar-profile-img" src="{$assetDir}/img/profile.png">
            <span><a href="/?p=profile&user={$accountInfo['uid_long']}">{$accountInfo['username']}</a></span>
        </userName>
    </bottomUserOptions>
</navigationBar>