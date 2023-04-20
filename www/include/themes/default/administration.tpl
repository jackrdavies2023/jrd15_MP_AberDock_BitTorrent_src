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
                    <h2 id="authentication">Authentication</h2>
                    <tinySeperator></tinySeperator>
                    <adminAuthenticationContainer>
                        <card>
                            <form method="POST" action="#authentication">
                                <input type="hidden" name="update-global">
                                <input type="hidden" name="authentication-configuration">
                                <label for="login_required">Login required</label>
                                <input type="checkbox" value="1" name="login_required" id="login_required" {if $config['login_required'] eq 1}checked{/if}>

                                <smallSeperator></smallSeperator>

                                <label for="registration_enabled">Registration enabled</label>
                                <input type="checkbox" value="1" name="registration_enabled" id="registration_enabled" {if $config['registration_enabled'] eq 1}checked{/if}>

                                <smallSeperator></smallSeperator>

                                <label for="registration_req_invite">Registration requires invite</label>
                                <input type="checkbox" value="1" name="registration_req_invite" id="registration_req_invite" {if $config['registration_req_invite'] eq 1}checked{/if}>

                                <smallSeperator></smallSeperator>

                                <label for="api_enabled">Enable API access</label>
                                <input type="checkbox" value="1" name="api_enabled" id="api_enabled" {if $config['api_enabled'] eq 1}checked{/if}>

                                <smallSeperator></smallSeperator>

                                <input type="submit" value="Save">
                            </form>
                        </card>
                    </adminAuthenticationContainer>

                    <smallSeperator></smallSeperator>

                    <!-- User groups -->
                    <h2 id="user-groups"> User groups</h2>
                    <tinySeperator></tinySeperator>
                    <adminGroupsContainer class="invert-input-background-mobile">
                        <card>
                            <form method="POST" action="#user-groups">
                                <label for="new-group-name">New group name</label>
                                <input type="text" class="no-invert-input-background-mobile" id="new-group-name" name="new-group-name" placeholder="Group name">
                                <input type="submit" class="no-invert-input-background-mobile" value="Add">
                            </form>
                            <smallSeperator></smallSeperator>
                            <form method="POST" action="#user-groups" id="form-user-groups"></form>
                            <input form="form-user-groups" type="hidden" name="update-groups">
                            <table>
                                <tr class="table-header">
                                    <th class="left-align">Name</th>
                                    <th>Colour</th>
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
                                    <th>API access</th>
                                    <th>Administrator</th>
                                    <th></th>
                                </tr>

                                {foreach $groups as $group}
                                <tr>
                                    <td class="left-align group-name">
                                        <input form="form-user-groups" type="text" name="group_name-groupID{$group['group_id']}" value="{$group['group_name']}">
                                    </td>
                                    <td>
                                        <label>Colour</label>
                                        <input form="form-user-groups" type="color" value="{$group['group_color']}" name="group_color-groupID{$group['group_id']}">
                                    </td>
                                    <td>
                                        <label>Guest</label>
                                        <input form="form-user-groups" value="1" type="checkbox" name="is_guest-groupID{$group['group_id']}" {if $group['is_guest'] eq 1}checked{/if}>
                                    </td>
                                    <td>
                                        <label>New account</label>
                                        <input form="form-user-groups" value="1" type="checkbox" name="is_new-groupID{$group['group_id']}" {if $group['is_new'] eq 1}checked{/if}>
                                    </td>
                                    <td>
                                        <label>Disabled</label>
                                        <input form="form-user-groups" value="1" type="checkbox" name="is_disabled-groupID{$group['group_id']}" {if $group['is_disabled'] eq 1}checked{/if}>
                                    </td>
                                    <td>
                                        <label>Upload</label>
                                        <input form="form-user-groups" value="1" type="checkbox" name="can_upload-groupID{$group['group_id']}" {if $group['can_upload'] eq 1}checked{/if}>
                                    </td>
                                    <td>
                                        <label>Download</label>
                                        <input form="form-user-groups" value="1" type="checkbox" name="can_download-groupID{$group['group_id']}" {if $group['can_download'] eq 1}checked{/if}>
                                    </td>
                                    <td>
                                        <label>Delete</label>
                                        <input form="form-user-groups" value="1" type="checkbox" name="can_delete-groupID{$group['group_id']}" {if $group['can_delete'] eq 1}checked{/if}>
                                    </td>
                                    <td>
                                        <label>Modify</label>
                                        <input form="form-user-groups" value="1" type="checkbox" name="can_modify-groupID{$group['group_id']}" {if $group['can_modify'] eq 1}checked{/if}>
                                    </td>
                                    <td>
                                        <label>View profiles</label>
                                        <input form="form-user-groups" value="1" type="checkbox" name="can_viewprofile-groupID{$group['group_id']}" {if $group['can_viewprofile'] eq 1}checked{/if}>
                                    </td>
                                    <td>
                                        <label>View statistics</label>
                                        <input form="form-user-groups" value="1" type="checkbox" name="can_viewstats-groupID{$group['group_id']}" {if $group['can_viewstats'] eq 1}checked{/if}>
                                    </td>
                                    <td>
                                        <label>Can comment</label>
                                        <input form="form-user-groups" value="1" type="checkbox" name="can_comment-groupID{$group['group_id']}" {if $group['can_comment'] eq 1}checked{/if}>
                                    </td>
                                    <td>
                                        <label>Can invite</label>
                                        <input form="form-user-groups" value="1" type="checkbox" name="can_invite-groupID{$group['group_id']}" {if $group['can_invite'] eq 1}checked{/if}>
                                    </td>
                                    <td>
                                        <label>API access</label>
                                        <input form="form-user-groups" value="1" type="checkbox" name="can_useapi-groupID{$group['group_id']}" {if $group['can_useapi'] eq 1}checked{/if}>
                                    </td>
                                    <td>
                                        <label>Administrator</label>
                                        <input form="form-user-groups" value="1" type="checkbox" name="is_admin-groupID{$group['group_id']}" {if $group['is_admin'] eq 1}checked{/if}>
                                    </td>
                                    <td class="right-align">
                                        <form method="POST" action="#user-groups">
                                            <input type="hidden" name="delete-group" value="{$group['group_id']}">
                                            <input type="submit" value="Delete">
                                        </form>
                                    </td>
                                </tr>
                                {/foreach}
                            </table>

                            <smallSeperator></smallSeperator>

                            <input type="submit" form="form-user-groups" class="no-invert-input-background-mobile" value="Save">
                        </card>
                    </adminGroupsContainer>

                    <smallSeperator></smallSeperator>

                    <!-- Tracker/announcement -->
                    <h2 id="tracker-announcement">Tracker/announcement</h2>
                    <tinySeperator></tinySeperator>
                    <adminTrackerContainer>
                        <card>
                            <form method="POST" action="#tracker-announcement">
                                <input type="hidden" name="update-global">
                                <input type="hidden" name="tracker-configuration">
                                <label for="announcement_interval">Announcement interval (seconds)</label>
                                <input type="number" id="announcement_interval" name="announcement_interval" placeholder="Seconds" value="{$config['announcement_interval']}" min="60" max="600">

                                <smallSeperator></smallSeperator>

                                <label for="announcement_allow_guest">Guests can connect</label>
                                <input type="checkbox" name="announcement_allow_guest" id="announcement_allow_guest" {if $config['announcement_allow_guest'] eq 1}checked{/if}>

                                <smallSeperator></smallSeperator>

                                <label for="announcement_url">Announcement URL</label>
                                <input type="text" id="announcement_url" name="announcement_url" placeholder="URL" value="{$config['announcement_url']}">

                                <smallSeperator></smallSeperator>

                                <input type="submit" value="Save">
                            </form>
                        </card>
                    </adminTrackerContainer>

                    <smallSeperator></smallSeperator>

                    <!-- Interface -->
                    <h2 id="interface">Interface</h2>
                    <tinySeperator></tinySeperator>
                    <adminInterfaceContainer>
                        <card>
                            <form method="POST" action="#interface">
                                <input type="hidden" name="update-global">
                                <input type="hidden" name="interface-configuration">
                                <label for="default_language">Default language</label>
                                <select type="text" id="default_language" name="default_language">
                                    {foreach $languages as $language}
                                        <option value="{$language['language_short']}">{$language['language_long']}</option>
                                    {/foreach}
                                </select>

                                <smallSeperator></smallSeperator>

                                <label for="default_theme">Default theme</label>
                                <select type="text" id="default_theme" name="default_theme">
                                    <option value="default">default</option>
                                </select>

                                <smallSeperator></smallSeperator>

                                <input type="submit" value="Save">
                            </form>
                        </card>
                    </adminInterfaceContainer>

                    <smallSeperator></smallSeperator>

                    <!-- Torrent categories -->
                    <h2 id="torrent-categories">Torrent categories</h2>
                    <tinySeperator></tinySeperator>
                    <adminTorrentCategoryContainer class="invert-input-background-mobile">
                        <card>
                            <form method="POST" action="#torrent-categories">
                                <label for="new-category-name">New category name</label>
                                <input type="text" class="no-invert-input-background-mobile" id="new-category-name" name="new-category-name" placeholder="Category name">
                                <input type="submit" class="no-invert-input-background-mobile" value="Add">
                            </form>
                            <smallSeperator></smallSeperator>
                            <!-- Define the forms here. Without this, nesting forms results in categories being deleted
                                 upon clicking "Save". Reference: https://www.impressivewebs.com/html5-form-attribute/ -->
                            <form method="POST" action="#torrent-categories" id="form-torrent-categories"></form>
                            <input form="form-torrent-categories" type="hidden" name="update-categories">
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
                                        <td class="left-align group-name">
                                            <input form="form-torrent-categories" type="text" name="category_name-categoryID{$category['category_index']}" value="{$category['category_name']}">
                                        </td>
                                        <td>
                                            <label>Parent</label>
                                            <input form="form-torrent-categories" type="radio" name="is_parent-categoryID{$category['category_index']}" value=1 checked/>
                                        </td>
                                        <td>
                                            <label>Child</label>
                                            <input form="form-torrent-categories" type="radio" name="is_parent-categoryID{$category['category_index']}" value=0>
                                        </td>
                                        <td>
                                            <label>Child of</label>
                                            <select form="form-torrent-categories" name="is_child_of-categoryID{$category['category_index']}">
                                                {foreach $categories as $categorySelect}
                                                    <option value="{$categorySelect['category_index']}" {if $category['category_index'] eq $categorySelect['category_index']} selected{/if}>{$categorySelect['category_name']}</option>
                                                {/foreach}
                                            </select>
                                        </td>
                                        <td class="right-align">
                                            <form method="POST" action="#torrent-categories">
                                                <input type="hidden" name="delete-category" value="{$category['category_index']}">
                                                <input type="submit" value="Delete">
                                            </form>
                                        </td>
                                    </tr>

                                    {foreach $category['category_sub'] as $subcategory}
                                    <tr class="admin-category-child">
                                        <td class="left-align group-name">
                                            <input form="form-torrent-categories" type="text" name="category_name-categoryID{$subcategory['category_index']}" value="{$subcategory['category_name']}">
                                        </td>
                                        <td>
                                            <label>Parent</label>
                                            <input form="form-torrent-categories" type="radio" name="is_parent-categoryID{$subcategory['category_index']}" value=1>
                                        </td>
                                        <td>
                                            <label>Child</label>
                                            <input form="form-torrent-categories" type="radio" name="is_parent-categoryID{$subcategory['category_index']}" value=0 checked/>
                                        </td>
                                        <td>
                                            <label>Child of</label>
                                            <select form="form-torrent-categories" name="is_child_of-categoryID{$subcategory['category_index']}">
                                                {foreach $categories as $categorySelect}
                                                    <option value={$categorySelect['category_index']} {if $category['category_index'] eq $categorySelect['category_index']} selected{/if}>{$categorySelect['category_name']}</option>
                                                {/foreach}
                                            </select>
                                        </td>
                                        <td class="right-align">
                                            <form method="POST" action="#torrent-categories">
                                                <input type="hidden" name="delete-category" value="{$subcategory['category_index']}">
                                                <input type="submit" value="Delete">
                                            </form>
                                        </td>
                                    </tr>
                                    {/foreach}
                                {/foreach}

                            </table>

                            <smallSeperator></smallSeperator>

                            <input form="form-torrent-categories" class="no-invert-input-background-mobile" type="submit" value="Save">
                        </card>
                    </adminTorrentCategoryContainer>
                {/if}
            </main>
        </mainContainer>
    </body>
</html>