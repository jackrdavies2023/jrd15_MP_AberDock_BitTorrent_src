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

                        <form>
                            <label for="torrent-file">Torrent file</label>
                            <tinySeperator></tinySeperator>
                            <input accept=".torrent" id="torrent-file" type="file" name="torrent-file" placeholder="Torrent file" required="">

                            <smallSeperator></smallSeperator>

                            <label for="torrent-title">Title</label>
                            <tinySeperator></tinySeperator>
                            <input type="text" id="torrent-title" name="torrent-title" placeholder="Title">

                            <smallSeperator></smallSeperator>

                            <label for="torrent-category">Category</label>
                            <tinySeperator></tinySeperator>
                            <select type="text" id="torrent-category" name="torrent-category">
                                <option value="0">[Select a category]</option>

                                <optgroup label="First parent category">
                                    <option value="1">First child category</option>
                                    <option value="1">Second child category</option>
                                    <option value="1">Third child category</option>
                                    <option value="1">Fourth child category</option>
                                    <option value="1">Fifth child category</option>
                                </optgroup>

                                <optgroup label="Second parent category">
                                    <option value="1">First child category</option>
                                    <option value="1">Second child category</option>
                                    <option value="1">Third child category</option>
                                    <option value="1">Fourth child category</option>
                                    <option value="1">Fifth child category</option>
                                </optgroup>
                            </select>

                            <smallSeperator></smallSeperator>

                            <label for="torrent-cover">Cover image</label>
                            <tinySeperator></tinySeperator>
                            <input name="torrent-cover" id="torrent-cover" type="file" accept=".jpg, .jpeg, .png">

                            <smallSeperator></smallSeperator>

                            <label for="torrent-description">Description</label>
                            <tinySeperator></tinySeperator>
                            <textarea name="torrent-description" id="torrent-description"></textarea>

                            <smallSeperator></smallSeperator>

                            <label for="torrent-anonymous">Anonymous upload</label>
                            <input type="checkbox" name="torrent-anonymous" id="torrent-anonymous">

                            <smallSeperator></smallSeperator>

                            <input type="submit" value="Upload">
                        </form>
                    </card>
                </uploadArea>
            </main>
        </mainContainer>
    </body>
</html>