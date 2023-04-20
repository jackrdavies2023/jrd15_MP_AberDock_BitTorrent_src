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
                                Uploaded by: <a class="profile-link" href="/?p=profile&uuid={$torrentDetails['uploader_uid']}">{$torrentDetails['uploader']}</a><br>
                                Size: 2.3GiB<br>
                                Category: {$torrentDetails['category_name']}<br>
                                Date: 01/01/2023<br>
                                Seeders: 0<br>
                                Leechers: 0<br>
                                Peers: 0<br>
                                Likes: 0<br>
                                Downloads: 0<br>

                                <smallSeperator></smallSeperator>

                                <button>Download</button>
                                <button>Bookmark</button>
                                <button>Like</button>
                            </stats>
                        </card>
                        <smallSeperator></smallSeperator>
                        Description:
                        <tinySeperator></tinySeperator>
                        <pre>{$torrentDetails['description']}</pre>

                        <smallSeperator></smallSeperator>
                        File list:<br>
                        <tinySeperator></tinySeperator>
                        <pre>
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