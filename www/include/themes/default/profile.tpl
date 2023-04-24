<!DOCTYPE html>
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
                        <h1>{$viewProfileDetails['username']}</h1>
                    </titleBar>

                    <!-- Profile card -->
                    <profileContainer>
                        <card>
                            <img class="profile-img" src="{$assetDir}/img/profile.png">
                            <stats>
                                Join date: Meow<br>
                                Total upload: 1000TiB<br>
                                Total download: 1000TiB<br>
                                Ratio: 1<br>
                                Seeding: 100<br>
                                Leeching: 5<br>
                                Likes received: 5<br>
                                Group: {$viewProfileDetails['group_name']}
                            </stats>
                        </card>
                    </profileContainer>

                    <smallSeperator></smallSeperator>

                    <!-- User uploads -->
                    <h2>Recent uploads</h2>
                    <torrentBrowser>
                        {if isset($viewProfileDetails['share_history']['uploads'])}
                            {foreach $viewProfileDetails['share_history']['uploads'] as $upload}
                                <card>
                                    <h3><a href="/?p=viewtorrent&uuid={$upload['torrent_uuid']}">{$upload['title']}</a></h3>
                                    <p>
                                        Posted in ISO -> Linux 2 days ago<br>
                                        {$upload['file_size_calc']} - {$upload['seeders']} seeders, {$upload['leechers']} leechers
                                    </p>
                                    <options>
                                        <i class="download"></i>
                                        <i class="bookmark"></i>
                                        <i class="like"></i>
                                        <i class="info"></i>
                                    </options>
                                </card>
                            {/foreach}
                        {/if}
                    </torrentBrowser>

                    <smallseperator></smallseperator>

                    <!-- User downloads -->
                    <h2>Recent downloads</h2>
                    <torrentBrowser>
                        {if isset($viewProfileDetails['share_history']['downloads'])}
                            {foreach $viewProfileDetails['share_history']['downloads'] as $download}
                                <card>
                                    <h3><a href="/?p=viewtorrent&uuid={$download['torrent_uuid']}">{$download['title']}</a></h3>
                                    <p>
                                        Posted in ISO -> Linux 2 days ago<br>
                                        {$download['file_size_calc']} - {$download['seeders']} seeders, {$download['leechers']} leechers
                                    </p>
                                    <options>
                                        <i class="download"></i>
                                        <i class="bookmark"></i>
                                        <i class="like"></i>
                                        <i class="info"></i>
                                    </options>
                                </card>
                            {/foreach}
                        {/if}
                    </torrentBrowser>
                {/if}
            </main>
        </mainContainer>
    </body>
</html>