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
                                <input type="text" name="query" id="search-query" placeholder="Search for something...">
                                <input type="submit" value="Search!">
                            </center>

                            <!-- Category selection area -->
                            <searchCategoriesContainer>
                                <!-- Parent container. The top-level checkbox, when checked,
                                     will check all child categories.
                                -->
                                <searchCategoryParent>
                                    <input type="checkbox" name="1" value="1"> Meow parent

                                    <searchCategoryChild>
                                        <input type="checkbox" name="1" value="1"> Meow child<br>
                                        <input type="checkbox" name="1" value="1"> Meow child<br>
                                        <input type="checkbox" name="1" value="1"> Meow child<br>
                                        <input type="checkbox" name="1" value="1"> Meow child<br>
                                        <input type="checkbox" name="1" value="1"> Meow child<br>
                                        <input type="checkbox" name="1" value="1"> Meow child<br>
                                        <input type="checkbox" name="1" value="1"> Meow child<br>
                                        <input type="checkbox" name="1" value="1"> Meow child<br>
                                        <input type="checkbox" name="1" value="1"> Meow child<br>
                                    </searchCategoryChild>
                                </searchCategoryParent>
                                <searchCategoryParent>
                                    <input type="checkbox" name="1" value="1"> Meow

                                    <searchCategoryChild>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                    </searchCategoryChild>
                                </searchCategoryParent>
                                <searchCategoryParent>
                                    <input type="checkbox" name="1" value="1"> Meow

                                    <searchCategoryChild>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                    </searchCategoryChild>
                                </searchCategoryParent>
                                <searchCategoryParent>
                                    <input type="checkbox" name="1" value="1"> Meow

                                    <searchCategoryChild>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                        <input type="checkbox" name="1" value="1"> Meow<br>
                                    </searchCategoryChild>
                                </searchCategoryParent>
                            </searchCategoriesContainer>
                        </form>
                    </card>
                </searchEngine>

                <!-- Torrent listing -->
                <h2>Latest torrents</h2>
                <torrentBrowser>
                    <card>
                        <h3><a href="/?p=viewtorrent">Torrent title</a></h3>
                        <p>
                        Posted in ISO -> Linux 2 days ago by <a class="profile-link" href="/?p=profile&user=somerandomid">human</a><br>
                        2.3 GiB - 10 seeders, 5 leechers
                        </p>
                        <options>
                            <i class="download"></i>
                            <i class="bookmark"></i>
                            <i class="like"></i>
                            <i class="info"></i>
                        </options>
                    </card>

                    <card>
                        <h3><a href="/?p=viewtorrent">Torrent title</a></h3>
                        <p>
                        Posted in ISO -> Linux 2 days ago by <a class="profile-link" href="/?p=profile&user=somerandomid">human</a><br>
                        2.3 GiB - 10 seeders, 5 leechers
                        </p>
                        <options>
                            <i class="download"></i>
                            <i class="bookmark"></i>
                            <i class="like"></i>
                            <i class="info"></i>
                        </options>
                    </card>

                    <card>
                        <h3><a href="/?p=viewtorrent">Torrent title</a></h3>
                        <p>
                        Posted in ISO -> Linux 2 days ago by <a class="profile-link" href="/?p=profile&user=somerandomid">human</a><br>
                        2.3 GiB - 10 seeders, 5 leechers
                        </p>
                        <options>
                            <i class="download"></i>
                            <i class="bookmark"></i>
                            <i class="like"></i>
                            <i class="info"></i>
                        </options>
                    </card>

                    <card>
                        <h3><a href="/?p=viewtorrent">Torrent title</a></h3>
                        <p>
                        Posted in ISO -> Linux 2 days ago by <a class="profile-link" href="/?p=profile&user=somerandomid">human</a><br>
                        2.3 GiB - 10 seeders, 5 leechers
                        </p>
                        <options>
                            <i class="download"></i>
                            <i class="bookmark"></i>
                            <i class="like"></i>
                            <i class="info"></i>
                        </options>
                    </card>
                </torrentBrowser>
            </main>
        </mainContainer>
    </body>
</html>