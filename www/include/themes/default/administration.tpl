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
                                <input type="checkbox" name="login-required" id="login-required">

                                <smallSeperator></smallSeperator>

                                <label for="registration-enabled">Registration enabled</label>
                                <input type="checkbox" name="registration-enabled" id="registration-enabled">

                                <smallSeperator></smallSeperator>

                                <label for="registration-invite-only">Registration requires invite</label>
                                <input type="checkbox" name="registration-invite-only" id="registration-invite-only">

                                <smallSeperator></smallSeperator>

                                <label for="api-enabled">Enable API access</label>
                                <input type="checkbox" name="api-enabled" id="api-enabled">

                                <smallSeperator></smallSeperator>

                                <input type="submit" value="Save">
                            </form>
                        </card>
                    </adminAuthenticationContainer>

                    <smallSeperator></smallSeperator>

                    <!-- User groups -->
                    <h2> User groups</h2>
                    <tinySeperator></tinySeperator>
                    <adminGroupsContainer>
                        <card>
                            <form method="POST">
                                <label for="new-group-name">New group name</label>
                                <input type="text" id="new-group-name" name="new-group-name" placeholder="Group name">
                                <input type="submit" value="Add">
                            </form>

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
                                    <td><label>Colour</label>ffffff</td>
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
                                        <button>Update</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left-align group-name">User</td>
                                    <td><label>Colour</label>ffffff</td>
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
                                        <button>Update</button>
                                    </td>
                                </tr>
                            </table>
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
                                <input type="number" id="announcement-interval" name="announcement-interval" placeholder="Seconds">

                                <smallSeperator></smallSeperator>

                                <label for="announcement-allow-guests">Guests can connect</label>
                                <input type="checkbox" name="announcement-allow-guests" id="announcement-allow-guests">

                                <smallSeperator></smallSeperator>

                                <label for="announcement-url-default">Announcement interval (seconds)</label>
                                <input type="text" id="announcement-url-default" name="announcement-url-default" placeholder="URL">

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
                                    <option value="eng">English</option>
                                    <option value="cym">Cymraeg</option>
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
                    <adminTorrentCategoryContainer>
                        <card>
                            
                        </card>
                    </adminTorrentCategoryContainer>
                {/if}
            </main>
        </mainContainer>
    </body>
</html>