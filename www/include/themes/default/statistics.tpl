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
                    <h1>Statistics</h1>
                </titleBar>

                <statisticsSummary>
                    <card class="no-background">
                        <statisticsSummaryBoxContainer>
                            
                            <statisticsSummaryBox>
                                <div>
                                    <span>Total P2P traffic today<br></span>

                                    <card>
                                        3000TiB
                                    </card>
                                </div>
                            </statisticsSummaryBox>

                            
                            <statisticsSummaryBox>
                                <div>
                                    <span>Total P2P traffic this week<br></span>

                                    <card>
                                        6000TiB
                                    </card>
                                </div>
                            </statisticsSummaryBox>

                            
                            <statisticsSummaryBox>
                                <div>
                                    <span>New uploads this week<br></span>

                                    <card>
                                        {$totalWeekUploads}
                                    </card>
                                </div>
                            </statisticsSummaryBox>

                            
                            <statisticsSummaryBox>
                                <div>
                                    <span>Total peers<br></span>

                                    <card>
                                        {$totalPeers}
                                    </card>
                                </div>
                            </statisticsSummaryBox>
                        </statisticsSummaryBoxContainer>
                    </card>
                </statisticsSummary>

                <!-- Top 10 seeders -->
                <h2>Top 10 seeders</h2>
                <topTenSeeders>
                    <card class="rounded-top-corners margin-top-2mm">
                        <statisticsTableHeader>
                            <span class="username">Username</span>
                            <span>Ratio</span>
                            <span>Upload</span>
                            <span>Download</span>
                        </statisticsTableHeader>
                    </card>
                    {foreach $topTenSeeders as $user}
                        <card class="no-rounded-corners">
                            <statisticsTableHeader>
                                <span class="username">{$user['username']}</span>
                                <span>{$user['ratio']}</span>
                                <span>{$user['uploaded']}</span>
                                <span>{$user['downloaded']}</span>
                            </statisticsTableHeader>
                        </card>
                    {/foreach}
                </topTenSeeders>

                <smallSeperator></smallSeperator>

                <!-- Top 10 worst seeders -->
                <h2>Top 10 worst seeders</h2>
                <topTenWorstSeeders>
                    <card class="rounded-top-corners margin-top-2mm">
                        <statisticsTableHeader>
                            <span class="username">Username</span>
                            <span>Ratio</span>
                            <span>Upload</span>
                            <span>Download</span>
                        </statisticsTableHeader>
                    </card>
                    {foreach $topTenWorstSeeders as $user}
                        <card class="no-rounded-corners">
                            <statisticsTableHeader>
                                <span class="username">{$user['username']}</span>
                                <span>{$user['ratio']}</span>
                                <span>{$user['uploaded']}</span>
                                <span>{$user['downloaded']}</span>
                            </statisticsTableHeader>
                        </card>
                    {/foreach}
                </topTenWorstSeeders>
            </main>
        </mainContainer>
    </body>
</html>