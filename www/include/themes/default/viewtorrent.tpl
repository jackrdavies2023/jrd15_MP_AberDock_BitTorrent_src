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
                    <h1>{$torrentDetails['title']}</h1>
                </titleBar>

                <!-- Torrent card -->
                <torrentContainer>
                    <card>
                        <card class="torrent-stats no-background">
                            <img class="torrent-cover-img" src="{$assetDir}/img/torrent-cover.png">
                            <stats>
                                Uploaded by:
                                {if $torrentDetails['anonymous'] eq 1}
                                    Anonymous<br>
                                {else}
                                    <a class="profile-link" href="/?p=profile&uuid={$torrentDetails['uploader']['uuid']}">{$torrentDetails['uploader']['username']}</a><br>
                                {/if}
                                Size: {$torrentDetails['file_size_calc']}<br>
                                Category: {$torrentDetails['category_name']}<br>
                                Uploaded: {$torrentDetails['upload_time']}<br>
                                Seeders: {$torrentDetails['seeders']}<br>
                                Leechers: {$torrentDetails['leechers']}<br>
                                Peers: {$torrentDetails['peers']}<br>
                                Likes: 0<br>
                                Downloads: 0<br>

                                <smallSeperator></smallSeperator>
                                <form id="form-download" method="GET">
                                    <input type="hidden" name="p" value="viewtorrent"/>
                                    <input type="hidden" name="download"/>
                                    <input type="hidden" name="uuid" value="{$torrentDetails['torrent_uuid']}"/>
                                </form>

                                <button form="form-download" type="submit">Download</button>
                                <button>Bookmark</button>
                                <button>Like</button>
                            </stats>
                        </card>
                        <smallSeperator></smallSeperator>
                        Description:
                        <tinySeperator></tinySeperator>
                        <pre class="torrent-description">{$torrentDetails['description']}</pre>

                        <smallSeperator></smallSeperator>
                        File list:<br>
                        <tinySeperator></tinySeperator>
                        <pre class="torrent-files">
- /
    - aPicture.jpg
    - someAudio.mp3
    - anotherDirectory
        - someMoreJunk.txt
</pre>
                    </card>
                </torrentContainer>
            </main>
        </mainContainer>
    </body>
</html>