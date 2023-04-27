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
                    <h1>Account settings</h1>
                </titleBar>

                <smallSeperator></smallSeperator>

                <h2 id="privacy">Privacy</h2>
                <tinySeperator></tinySeperator>
                <accountSettingsPrivacyContainer>
                    <card>
                        <label for="profile-visibility">Profile visibility</label>
                        <select name="profile-visibility">
                            <option>Public</option>
                            <option>Private</option>
                        </select>
                        <tinySeperator></tinySeperator>

                        <label for="show-downloads">Show downloads</label>
                        <input type="checkbox" name="show-downloads">
                        <tinySeperator></tinySeperator>

                        <label for="show-uploads">Show uploads</label>
                        <input type="checkbox" name="show-downloads">
                        <tinySeperator></tinySeperator>

                        <input type="submit" value="Save"
                    </card>
                </accountSettingsPrivacyContainer>

                <smallSeperator></smallSeperator>

                <h2 id="security">Security</h2>
                <tinySeperator></tinySeperator>
                <accountSettingsSecurityContainer>
                    <card>
                        <form method="POST" action="#security">
                            <label for="current-password">Current password</label>
                            <input type="password" name="current-password">
                            <tinySeperator></tinySeperator>

                            <label for="new-password">New password</label>
                            <input type="password" name="new-password">
                            <tinySeperator></tinySeperator>

                            <label for="new-password-confirmation">New password confirmation</label>
                            <input type="password" name="new-password-confirmation">
                            <tinySeperator></tinySeperator>

                            <input type="submit" value="Save">
                        </form>

                        <smallSeperator></smallSeperator>

                        <label for="account-recovery-key">Account recovery key</label>
                        <input type="text" name="account-recovery-key" value="SomeRandomValue" readonly>
                        <input type="submit" value="Regenerate">
                        <tinySeperator></tinySeperator>
                        <smallLabel class="red">Make sure to note this down!</smallLabel>
                    </card>
                </accountSettingsSecurityContainer>

                <smallSeperator></smallSeperator>

                <h2 id="sessions">Sessions</h2>
                <tinySeperator></tinySeperator>
                <accountSettingsSessionsContainer>
                    <card>
                        <table>
                            <tr>
                                <th class="left-align">User agent</th>
                                <th>IP address</th>
                                <th>Last seen</th>
                                <th></th>
                            </tr>
                            <tr>
                                <td class="left-align">
                                    A web browser
                                </td>
                                <td>
                                    127.0.0.1
                                </td>
                                <td>
                                    Just now
                                </td>
                                <td>
                                    Delete
                                </td>
                            </tr>
                        </table>
                    </card>
                </accountSettingsSessionsContainer>

                <smallSeperator></smallSeperator>

                <h2 id="torrent-clients">Active torrents</h2>
                <tinySeperator></tinySeperator>
                <accountSettingsTorrentClientsContainer>
                    <card>
                        <table>
                            <tr>
                                <th class="left-align">User agent</th>
                                <th>IP address</th>
                                <th>Torrent</th>
                                <th></th>
                            </tr>
                            <tr>
                                <td class="left-align">
                                    Deluge
                                </td>
                                <td>
                                    127.0.0.1
                                </td>
                                <td>
                                    Some random torrent file
                                </td>
                                <td>
                                    Delete
                                </td>
                            </tr>
                        </table>
                    </card>
                </accountSettingsTorrentClientsContainer>
            {/if}
        </main>
    </mainContainer>
    </body>
</html>