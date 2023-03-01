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
                <h2>Latest torrents </h2>
                <torrentBrowser>
                    <card>
                        This is a card.
                    </card>
                </torrentBrowser>
            </main>
        </mainContainer>
    </body>
</html>