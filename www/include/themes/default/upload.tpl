<html lang="en">
    {include file='header.tpl'}
    <body>
        {include file='navbar.tpl'}

        <!-- Contains the main page contents as well as navigation bar. -->
        <mainContainer>
            <!-- Contains main page content. -->
            <main>
                <titleBar>
                    <h1>Upload</h1>
                </titleBar>

                <uploadArea>
                    <card>
                        <p>
                            Your announcement URL: http://127.0.0.1/announce.php?pid=123456789abcdefghijklmnopqrstuvwxyz
                        </p>
                        <label for="torrent-file">Torrent file</label>
                        <div class="tiny-spacer"></div>
                        <input accept=".torrent" id="torrent-file" type="file" name="torrent-file" placeholder="Torrent file" required="">
                    </card>
                </uploadArea>
            </main>
        </mainContainer>
    </body>
</html>