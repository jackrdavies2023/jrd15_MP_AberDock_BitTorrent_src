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
                    <h1>Upload</h1>
                </titleBar>

                <uploadArea>
                    <card>
                        <p>
                            Your announcement URL: {$announcementUrl}
                        </p>

                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="torrent-upload">
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
                                {foreach $categories as $category}
                                    <optgroup label="{$category['category_name']}">
                                        {foreach $category['category_sub'] as $subcategory}
                                            <option value="{$subcategory['category_index']}">{$subcategory['category_name']}</option>
                                        {/foreach}
                                    </optgroup>
                                {/foreach}
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