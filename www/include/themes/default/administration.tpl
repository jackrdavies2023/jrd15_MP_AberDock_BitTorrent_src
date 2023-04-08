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
                        <h1>Administration</h1>
                    </titleBar>

                    <smallSeperator></smallSeperator>

                    <!-- Authentication -->
                    <h2>Authentication</h2>
                    <tinySeperator></tinySeperator>
                    <adminAuthenticationContainer>
                        <card>
                            <form method="POST">
                                <label for="login-required">Login required</label>
                                <input type="checkbox" name="login-required" id="login-required" {if $config['login_required'] eq 1}checked{/if}>

                                <smallSeperator></smallSeperator>

                                <label for="registration-enabled">Registration enabled</label>
                                <input type="checkbox" name="registration-enabled" id="registration-enabled" {if $config['registration_enabled'] eq 1}checked{/if}>

                                <smallSeperator></smallSeperator>

                                <label for="registration-invite-only">Registration requires invite</label>
                                <input type="checkbox" name="registration-invite-only" id="registration-invite-only" {if $config['registration_req_invite'] eq 1}checked{/if}>

                                <smallSeperator></smallSeperator>

                                <label for="api-enabled">Enable API access</label>
                                <input type="checkbox" name="api-enabled" id="api-enabled" {if $config['api_enabled'] eq 1}checked{/if}>

                                <smallSeperator></smallSeperator>

                                <input type="submit" value="Save">
                            </form>
                        </card>
                    </adminAuthenticationContainer>

                    <smallSeperator></smallSeperator>

                    <!-- User groups -->
                    <h2> User groups</h2>
                    <tinySeperator></tinySeperator>
                    <adminGroupsContainer class="invert-input-background-mobile">
                        <card>
                            <form method="POST">
                                <label for="new-group-name">New group name</label>
                                <input type="text" class="no-invert-input-background-mobile" id="new-group-name" name="new-group-name" placeholder="Group name">
                                <input type="submit" value="Add">
                            </form>
                            <smallSeperator></smallSeperator>
                            <form method="POST">
                                <table>
                                    <tr class="table-header">
                                        <th class="left-align">Name</th>
                                        <th>Colour (hex)</th>
                                        <th>Guest</th>
                                        <th>New account</th>
                                        <th>Disabled</th>
                                        <th>Upload</th>
                                        <th>Download</th>
                                        <th>Delete</th>
                                        <th>Modify</th>
                                        <th>View profiles</th>
                                        <th>View statistics</th>
                                        <th>Can comment</th>
                                        <th>Can invite</th>
                                        <th>Administrator</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <td class="left-align group-name">Administrator</td>
                                        <td><label>Colour</label><input type="text" value="ffffff"></td>
                                        <td><label>Guest</label><input type="checkbox"></td>
                                        <td><label>New account</label><input type="checkbox"></td>
                                        <td><label>Disabled</label><input type="checkbox"></td>
                                        <td><label>Upload</label><input type="checkbox"></td>
                                        <td><label>Download</label><input type="checkbox"></td>
                                        <td><label>Delete</label><input type="checkbox"></td>
                                        <td><label>Modify</label><input type="checkbox"></td>
                                        <td><label>View profiles</label><input type="checkbox"></td>
                                        <td><label>View statistics</label><input type="checkbox"></td>
                                        <td><label>Can comment</label><input type="checkbox"></td>
                                        <td><label>Can invite</label><input type="checkbox"></td>
                                        <td><label>Administrator</label><input type="checkbox"></td>
                                        <td class="right-align">
                                            <button>Delete</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="left-align group-name">User</td>
                                        <td><label>Colour</label><input type="text" value="ffffff"></td>
                                        <td><label>Guest</label><input type="checkbox"></td>
                                        <td><label>New account</label><input type="checkbox"></td>
                                        <td><label>Disabled</label><input type="checkbox"></td>
                                        <td><label>Upload</label><input type="checkbox"></td>
                                        <td><label>Download</label><input type="checkbox"></td>
                                        <td><label>Delete</label><input type="checkbox"></td>
                                        <td><label>Modify</label><input type="checkbox"></td>
                                        <td><label>View profiles</label><input type="checkbox"></td>
                                        <td><label>View statistics</label><input type="checkbox"></td>
                                        <td><label>Can comment</label><input type="checkbox"></td>
                                        <td><label>Can invite</label><input type="checkbox"></td>
                                        <td><label>Administrator</label><input type="checkbox"></td>
                                        <td class="right-align">
                                            <button>Delete</button>
                                        </td>
                                    </tr>
                                </table>
                            </form>

                            <smallSeperator></smallSeperator>

                            <button>Save</button>
                        </card>
                    </adminGroupsContainer>

                    <smallSeperator></smallSeperator>

                    <!-- Tracker/announcement -->
                    <h2>Tracker/announcement</h2>
                    <tinySeperator></tinySeperator>
                    <adminTrackerContainer>
                        <card>
                            <form method="POST">
                                <label for="announcement-interval">Announcement interval (seconds)</label>
                                <input type="number" id="announcement-interval" name="announcement-interval" placeholder="Seconds" value="{$config['announcement_interval']}">

                                <smallSeperator></smallSeperator>

                                <label for="announcement-allow-guests">Guests can connect</label>
                                <input type="checkbox" name="announcement-allow-guests" id="announcement-allow-guests" {if $config['announcement_allow_guest'] eq 1}checked{/if}>

                                <smallSeperator></smallSeperator>

                                <label for="announcement-url-default">Announcement URL</label>
                                <input type="text" id="announcement-url-default" name="announcement-url-default" placeholder="URL" value="{$config['announcement_url']}">

                                <smallSeperator></smallSeperator>

                                <input type="submit" value="Save">
                            </form>
                        </card>
                    </adminTrackerContainer>

                    <smallSeperator></smallSeperator>

                    <!-- Interface -->
                    <h2>Interface</h2>
                    <tinySeperator></tinySeperator>
                    <adminInterfaceContainer>
                        <card>
                            <form method="POST">
                                <label for="interface-default-language">Default language</label>
                                <select type="text" id="interface-default-language" name="interface-default-language">
                                    {foreach $languages as $language}
                                        <option value="{$language['language_short']}">{$language['language_long']}</option>
                                    {/foreach}
                                </select>

                                <smallSeperator></smallSeperator>

                                <label for="interface-default-theme">Default theme</label>
                                <select type="text" id="interface-default-theme" name="interface-default-theme">
                                    <option value="default">default</option>
                                </select>

                                <smallSeperator></smallSeperator>

                                <input type="submit" value="Save">
                            </form>
                        </card>
                    </adminInterfaceContainer>

                    <smallSeperator></smallSeperator>

                    <!-- Torrent categories -->
                    <h2>Torrent categories</h2>
                    <tinySeperator></tinySeperator>
                    <adminTorrentCategoryContainer class="invert-input-background-mobile">
                        <card>
                            <form method="POST">
                                <label for="new-category-name">New category name</label>
                                <input type="text" class="no-invert-input-background-mobile" id="new-category-name" name="new-category-name" placeholder="Category name">
                                <input type="submit" value="Add">
                            </form>
                            <smallSeperator></smallSeperator>
                            <form method="POST">
                                <input type="hidden" name="update-categories">
                                <table>
                                    <tr class="table-header">
                                        <th class="left-align">Name</th>
                                        <th>Parent</th>
                                        <th>Child</th>
                                        <th>Child of</th>
                                        <th></th>
                                    </tr>
                                    {foreach $categories as $category}
                                        <tr>
                                            <td class="left-align group-name"><input type="text" name="name-categoryID{$category['category_index']}" value="{$category['category_name']}"></td>
                                            <td><label>Parent</label><input type="radio" name="is-parent-categoryID{$category['category_index']}" value=1 checked/></td>
                                            <td><label>Child</label><input type="radio" name="is-parent-categoryID{$category['category_index']}" value=0></td>
                                            <td>
                                                <label>Child of</label>
                                                <select name="is-child-of-categoryID{$category['category_index']}">
                                                    {foreach $categories as $categorySelect}
                                                        <option value="{$categorySelect['category_index']}" {if $category['category_index'] eq $categorySelect['category_index']} selected{/if}>{$categorySelect['category_name']}</option>
                                                    {/foreach}
                                                </select>
                                            </td>
                                            <td class="right-align">
                                                <button>Delete</button>
                                            </td>
                                        </tr>

                                        {foreach $category['category_sub'] as $subcategory}
                                        <tr>
                                            <td class="left-align group-name"><input type="text" name="name-categoryID{$subcategory['category_index']}" value="{$subcategory['category_name']}"></td>
                                            <td><label>Parent</label><input type="radio" name="is-parent-categoryID{$subcategory['category_index']}" value=1></td>
                                            <td><label>Child</label><input type="radio" name="is-parent-categoryID{$subcategory['category_index']}" value=0 checked/></td>
                                            <td>
                                                <label>Child of</label>
                                                <select name="is-child-of-categoryID{$subcategory['category_index']}">
                                                    {foreach $categories as $categorySelect}
                                                        <option value={$categorySelect['category_index']} {if $category['category_index'] eq $categorySelect['category_index']} selected{/if}>{$categorySelect['category_name']}</option>
                                                    {/foreach}
                                                </select>
                                            </td>
                                            <td class="right-align">
                                                <button>Delete</button>
                                            </td>
                                        </tr>
                                        {/foreach}
                                    {/foreach}

                                </table>

                                <smallSeperator></smallSeperator>

                                <input type="submit" value="Save">
                            </form>
                        </card>
                    </adminTorrentCategoryContainer>
                {/if}
            </main>
        </mainContainer>
    </body>
</html>