<!DOCTYPE html>
<html lang="en">
    {include file='header.tpl'}
    <body>
        {include file='navbar.tpl'}
        <!-- Contains the main page contents as well as navigation bar. -->
        <mainContainer>
            <!-- Contains main page content. -->
            <main>
                <titleBar>
                    <h1>Bookmarks</h1>
                </titleBar>

                <torrentBrowser>
                    {foreach $torrentList as $torrent}
                        <card>
                            <h3><a href="/?p=viewtorrent&uuid={$torrent['torrent_uuid']}">{$torrent['title']}</a></h3>
                            <p>
                                Posted in [{$torrent['category_name']}] {$torrent['upload_time']}
                                {if $torrent['anonymous'] eq 1}
                                    by Anonymous<br>
                                {else}
                                    by <a class="profile-link" href="/?p=profile&uuid={$torrent['uploader']['uuid']}">{$torrent['uploader']['username']}</a><br>
                                {/if}
                                {$torrent['file_size_calc']} - {$torrent['seeders']} seeders, {$torrent['leechers']} leechers
                            </p>
                            <options>
                                <a href="/?p=viewtorrent&uuid={$torrent['torrent_uuid']}&download"><i class="download"></i></a>
                                <a href="/?p=viewtorrent&uuid={$torrent['torrent_uuid']}"> <i class="info"></i></a>
                                <a href="/?p=bookmarks&uuid={$torrent['torrent_uuid']}&bookmarkdelete"> <i class="delete"></i></a>
                            </options>
                        </card>
                    {/foreach}
                </torrentBrowser>
            </main>
        </mainContainer>
    </body>
</html>