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
                    <h1>Browse</h1>
                </titleBar>

                <!-- Container for the search feature -->
                <searchEngine>
                    <card class="no-background">
                        <form method="GET">
                            <!-- "center" is deprecated, but I'm still going to name this DOM as such and add CSS to it. -->
                            <center>
                                <!-- Setting the "action" in the form to "/?p=browse&" doesn't work.
                                     Instead we add a hidden field named "p", to set the page. -->
                                <input type="hidden" name="p" value="browse">
                                <input type="text" name="query" id="search-query" placeholder="Search for something..." value="{$query}" autofocus>
                                <input type="submit" value="Search!">
                            </center>

                            <!-- Category selection area -->
                            <searchCategoriesContainer>
                                <!-- Parent container. The top-level checkbox, when checked,
                                     will check all child categories.
                                -->
                                {foreach $categories as $category}
                                <searchCategoryParent>
                                    <input type="checkbox" name="{$category['category_index']}" value="1"> {$category['category_name']}

                                    <searchCategoryChild>
                                        {foreach $category['category_sub'] as $subcategory}
                                        <input type="checkbox" name="{$subcategory['category_index']}" value="1"> {$subcategory['category_name']}<br>
                                        {/foreach}
                                    </searchCategoryChild>
                                </searchCategoryParent>
                                {/foreach}
                            </searchCategoriesContainer>
                        </form>
                    </card>
                </searchEngine>

                <!-- Torrent listing -->
                <h2>Latest torrents</h2>
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
                                <a href="/?p=viewtorrent&uuid={$torrent['torrent_uuid']}&bookmark"><i class="bookmark"></i></a>
                                <a href=""><i class="like"></i></a>
                                <a href="/?p=viewtorrent&uuid={$torrent['torrent_uuid']}"> <i class="info"></i></a>
                            </options>
                        </card>
                    {/foreach}
                </torrentBrowser>
            </main>
        </mainContainer>
    </body>
</html>